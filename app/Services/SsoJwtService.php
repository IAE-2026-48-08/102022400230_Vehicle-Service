<?php

namespace App\Services;

use App\Models\Role;
use App\Models\SsoUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;

class SsoJwtService
{
    public function captureUserFromRequest(Request $request): SsoUser
    {
        $token = $this->bearerToken($request);
        $payload = $this->decodePayload($token);

        if (($payload['exp'] ?? null) && Carbon::createFromTimestamp((int) $payload['exp'])->isPast()) {
            throw new InvalidArgumentException('SSO token is expired');
        }

        $subject = (string) ($payload['sub'] ?? '');
        if ($subject === '') {
            throw new InvalidArgumentException('SSO token subject is missing');
        }

        $ssoUser = SsoUser::updateOrCreate(
            ['sso_subject' => $subject],
            [
                'name' => $payload['name'] ?? $payload['preferred_username'] ?? null,
                'email' => $payload['email'] ?? null,
                'last_jwt_payload' => $payload,
                'last_login_at' => now(),
            ]
        );

        $roleNames = $this->extractRoles($payload);
        $roleIds = [];

        foreach ($roleNames as $roleName) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['description' => 'Role lokal hasil mapping dari payload SSO Dosen.']
            );
            $roleIds[] = $role->id;
        }

        $ssoUser->roles()->sync($roleIds);

        return $ssoUser->load('roles');
    }

    public function userHasAllowedRole(SsoUser $user): bool
    {
        $allowedRoles = config('iae_integrations.sso.allowed_roles', []);

        return $user->roles
            ->pluck('name')
            ->intersect($allowedRoles)
            ->isNotEmpty();
    }

    private function bearerToken(Request $request): string
    {
        $header = (string) $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            throw new InvalidArgumentException('Authorization Bearer token is missing');
        }

        return trim(substr($header, 7));
    }

    private function decodePayload(string $token): array
    {
        $segments = explode('.', $token);

        if (count($segments) < 2) {
            throw new InvalidArgumentException('SSO token format is invalid');
        }

        $payloadSegment = strtr($segments[1], '-_', '+/');
        $payloadSegment .= str_repeat('=', (4 - strlen($payloadSegment) % 4) % 4);
        $payloadJson = base64_decode($payloadSegment, true);

        if ($payloadJson === false) {
            throw new InvalidArgumentException('SSO token payload cannot be decoded');
        }

        $payload = json_decode($payloadJson, true);

        if (! is_array($payload)) {
            throw new InvalidArgumentException('SSO token payload is not valid JSON');
        }

        return $payload;
    }

    private function extractRoles(array $payload): array
    {
        $roles = $payload['roles']
            ?? $payload['role']
            ?? data_get($payload, 'realm_access.roles')
            ?? data_get($payload, 'resource_access.vehicle-service.roles')
            ?? ['viewer'];

        if (is_string($roles)) {
            $roles = array_map('trim', explode(',', $roles));
        }

        return array_values(array_unique(array_filter($roles)));
    }
}
