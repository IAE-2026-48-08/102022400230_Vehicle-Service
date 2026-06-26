<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OAT;

class SsoProfileController extends Controller
{
    use ApiResponse;

    #[OAT\Get(
        path: '/api/v1/sso/profile',
        summary: 'Menangkap payload JWT SSO dan memetakan role lokal',
        security: [['ssoBearer' => []]],
        tags: ['Tugas 3 - SSO'],
        responses: [
            new OAT\Response(response: 200, description: 'Payload SSO berhasil ditangkap'),
            new OAT\Response(response: 401, description: 'Bearer JWT kosong atau tidak valid'),
        ]
    )]
    public function show(Request $request)
    {
        $ssoUser = $request->attributes->get('sso_user')->load('roles');

        return $this->successResponse([
            'sso_subject' => $ssoUser->sso_subject,
            'name' => $ssoUser->name,
            'email' => $ssoUser->email,
            'local_roles' => $ssoUser->roles->pluck('name')->values(),
            'last_login_at' => $ssoUser->last_login_at,
        ], 'SSO payload captured successfully');
    }
}
