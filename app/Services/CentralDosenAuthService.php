<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CentralDosenAuthService
{
    public function bearerToken(): string
    {
        $m2mApiKey = config('iae_integrations.sso.m2m_api_key');
        $userEmail = config('iae_integrations.sso.user_email');
        $userPassword = config('iae_integrations.sso.user_password');

        if ($m2mApiKey) {
            $payload = ['api_key' => $m2mApiKey];
        } elseif ($userEmail && $userPassword) {
            $payload = [
                'email' => $userEmail,
                'password' => $userPassword,
            ];
        } else {
            throw new \RuntimeException('SSO credential is not configured for central Dosen integration');
        }

        $response = Http::acceptJson()
            ->post(rtrim(config('iae_integrations.sso.base_url'), '/').'/api/v1/auth/token', $payload);

        if (! $response->successful()) {
            throw new \RuntimeException('Failed to request token from SSO Dosen');
        }

        $token = $response->json('access_token')
            ?? $response->json('token')
            ?? $response->json('data.access_token')
            ?? $response->json('data.token');

        if (! $token) {
            throw new \RuntimeException('SSO Dosen token response does not contain access_token');
        }

        return $token;
    }
}
