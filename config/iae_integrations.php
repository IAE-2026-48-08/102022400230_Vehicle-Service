<?php

return [
    'sso' => [
        'base_url' => env('SSO_BASE_URL', 'https://iae-sso.virtualfri.id'),
        'issuer' => env('SSO_ISSUER', 'https://iae-sso.virtualfri.id'),
        'audience' => env('SSO_AUDIENCE', 'vehicle-service'),
        'allowed_roles' => array_filter(array_map('trim', explode(',', env('SSO_ALLOWED_ROLES', 'fleet_admin,dispatch_admin')))),
        'm2m_api_key' => env('SSO_M2M_API_KEY'),
        'user_email' => env('SSO_USER_EMAIL'),
        'user_password' => env('SSO_USER_PASSWORD'),
    ],

    'legacy_audit' => [
        'mode' => env('LEGACY_AUDIT_MODE', 'mock'),
        'endpoint' => env('LEGACY_AUDIT_SOAP_URL', env('SSO_BASE_URL', 'https://iae-sso.virtualfri.id').'/soap/v1/audit'),
        'team_id' => env('IAE_TEAM_ID', '102022400230'),
        'activity_name' => env('LEGACY_AUDIT_ACTIVITY_NAME', 'VehicleDispatched'),
    ],

    'rabbitmq' => [
        'driver' => env('RABBITMQ_DRIVER', 'mock'),
        'enabled' => (bool) env('RABBITMQ_ENABLED', false),
        'host' => env('RABBITMQ_HOST', '127.0.0.1'),
        'port' => (int) env('RABBITMQ_PORT', 5672),
        'username' => env('RABBITMQ_USERNAME', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost' => env('RABBITMQ_VHOST', '/'),
        'exchange' => env('RABBITMQ_EXCHANGE', 'iae.dispatching'),
        'routing_key' => env('RABBITMQ_ROUTING_KEY', 'vehicle.dispatched'),
        'http_publish_url' => env('RABBITMQ_HTTP_PUBLISH_URL', env('SSO_BASE_URL', 'https://iae-sso.virtualfri.id').'/api/v1/messages/publish'),
    ],
];
