<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleDispatch;
use App\Services\BusinessEventPublisher;
use App\Services\LegacyAuditSoapClient;
use App\Services\SsoJwtService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;

class VehicleDispatchController extends Controller
{
    use ApiResponse;

    public function __construct(
        private LegacyAuditSoapClient $legacyAuditSoapClient,
        private BusinessEventPublisher $businessEventPublisher,
        private SsoJwtService $ssoJwtService
    ) {
    }

    #[OAT\Post(
        path: '/api/v1/vehicles/{id}/dispatch',
        summary: 'Transaksi kritis penugasan kendaraan dengan SSO, SOAP audit, dan RabbitMQ event',
        security: [['ssoBearer' => []]],
        tags: ['Tugas 3 - Dispatch'],
        parameters: [
            new OAT\Parameter(name: 'id', in: 'path', required: true, schema: new OAT\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                required: ['trip_reference', 'requester_name', 'destination', 'start_date'],
                properties: [
                    new OAT\Property(property: 'trip_reference', type: 'string', example: 'TRIP-T3-001'),
                    new OAT\Property(property: 'requester_name', type: 'string', example: 'Admin Perjalanan Dinas'),
                    new OAT\Property(property: 'destination', type: 'string', example: 'Kantor Cabang Bandung'),
                    new OAT\Property(property: 'start_date', type: 'string', format: 'date', example: '2026-06-10'),
                    new OAT\Property(property: 'end_date', type: 'string', format: 'date', nullable: true, example: '2026-06-11'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OAT\Response(response: 201, description: 'Dispatch berhasil, SOAP receipt tersimpan, event RabbitMQ dipublish'),
            new OAT\Response(response: 400, description: 'Validation error'),
            new OAT\Response(response: 401, description: 'Bearer JWT kosong atau tidak valid'),
            new OAT\Response(response: 403, description: 'Role SSO tidak diizinkan'),
            new OAT\Response(response: 404, description: 'Kendaraan tidak ditemukan'),
            new OAT\Response(response: 409, description: 'Kendaraan tidak Available'),
        ]
    )]
    public function store(Request $request, int $id)
    {
        $ssoUser = $request->attributes->get('sso_user')->load('roles');

        if (! $this->ssoJwtService->userHasAllowedRole($ssoUser)) {
            return $this->errorResponse('SSO user role is not allowed to dispatch vehicles', [
                'allowed_roles' => config('iae_integrations.sso.allowed_roles'),
                'user_roles' => $ssoUser->roles->pluck('name')->values(),
            ], 403);
        }

        $vehicle = Vehicle::find($id);

        if (! $vehicle) {
            return $this->errorResponse('Vehicle data not found', null, 404);
        }

        if ($vehicle->status !== Vehicle::STATUS_AVAILABLE) {
            return $this->errorResponse('Vehicle is not available for dispatch', [
                'current_status' => $vehicle->status,
            ], 409);
        }

        $validator = Validator::make($request->all(), [
            'trip_reference' => ['required', 'string', 'max:80', Rule::unique('vehicle_dispatches', 'trip_reference')],
            'requester_name' => ['required', 'string', 'max:120'],
            'destination' => ['required', 'string', 'max:160'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $dispatch = DB::transaction(function () use ($validator, $vehicle, $ssoUser) {
            $dispatchData = $validator->validated();
            $soapResult = $this->legacyAuditSoapClient->validateVehicleDispatch($vehicle, $dispatchData, $ssoUser);

            $vehicle->update(['status' => Vehicle::STATUS_IN_USE]);

            $dispatch = VehicleDispatch::create([
                ...$dispatchData,
                'vehicle_id' => $vehicle->id,
                'dispatch_status' => 'Dispatched',
                'approved_by_sso_user_id' => $ssoUser->id,
                'approved_role' => $ssoUser->roles->pluck('name')->intersect(config('iae_integrations.sso.allowed_roles'))->first(),
                'legacy_receipt_number' => $soapResult['receipt_number'],
                'legacy_xml_request' => $soapResult['xml_request'],
                'legacy_xml_response' => $soapResult['xml_response'],
            ]);

            $eventPayload = [
                'event_name' => 'vehicle.dispatched',
                'service_name' => 'Vehicle-Service',
                'api_version' => 'v1',
                'occurred_at' => now()->toIso8601String(),
                'dispatch_id' => $dispatch->id,
                'trip_reference' => $dispatch->trip_reference,
                'vehicle' => [
                    'id' => $vehicle->id,
                    'vehicle_code' => $vehicle->vehicle_code,
                    'plate_number' => $vehicle->plate_number,
                    'status' => Vehicle::STATUS_IN_USE,
                ],
                'legacy_receipt_number' => $dispatch->legacy_receipt_number,
                'approved_by' => [
                    'sso_subject' => $ssoUser->sso_subject,
                    'roles' => $ssoUser->roles->pluck('name')->values(),
                ],
            ];

            $publishStatus = $this->businessEventPublisher->publishVehicleDispatched($eventPayload);

            $dispatch->update([
                'published_event_payload' => $eventPayload,
                'published_event_status' => $publishStatus,
            ]);

            return $dispatch->load(['vehicle', 'approvedBy.roles']);
        });

        return $this->successResponse($dispatch, 'Vehicle dispatch transaction completed successfully', 201);
    }
}
