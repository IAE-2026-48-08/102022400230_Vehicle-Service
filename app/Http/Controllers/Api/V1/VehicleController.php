<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;

/**
 * @OA\Info(
 *     title="Vehicle-Service API",
 *     version="1.0.0",
 *     description="REST API untuk Service Data Kendaraan pada proses Penugasan Perjalanan Dinas (Dispatching)."
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Vehicle-Service local server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="iaeApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-IAE-KEY",
 *     description="API Key untuk mengakses Vehicle-Service. Gunakan value 102022400230."
 * )
 *
 * @OA\Schema(
 *     schema="Vehicle",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="vehicle_code", type="string", example="VH-001"),
 *     @OA\Property(property="plate_number", type="string", example="B 1022 TIA"),
 *     @OA\Property(property="brand", type="string", example="Toyota"),
 *     @OA\Property(property="model", type="string", example="Avanza"),
 *     @OA\Property(property="vehicle_type", type="string", example="MPV"),
 *     @OA\Property(property="capacity", type="integer", example=7),
 *     @OA\Property(property="fuel_type", type="string", example="Gasoline"),
 *     @OA\Property(property="status", type="string", enum={"Available","In-Use","Maintenance"}, example="Available"),
 *     @OA\Property(property="last_service_date", type="string", nullable=true, format="date", example="2026-05-10"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Siap digunakan untuk perjalanan dinas."),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="SuccessVehicleListResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="message", type="string", example="Data retrieved successfully"),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vehicle")),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="service_name", type="string", example="Vehicle-Service"),
 *         @OA\Property(property="api_version", type="string", example="v1")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="Detail pesan kesalahan"),
 *     @OA\Property(property="errors", nullable=true)
 * )
 */
#[OAT\Info(
    title: 'Vehicle-Service API',
    version: '1.0.0',
    description: 'REST API untuk Service Data Kendaraan pada proses Penugasan Perjalanan Dinas (Dispatching).'
)]
#[OAT\Server(url: '/', description: 'Vehicle-Service local server')]
#[OAT\SecurityScheme(
    securityScheme: 'iaeApiKey',
    type: 'apiKey',
    description: 'API Key untuk mengakses Vehicle-Service. Gunakan value 102022400230.',
    name: 'X-IAE-KEY',
    in: 'header'
)]
#[OAT\SecurityScheme(
    securityScheme: 'ssoBearer',
    type: 'http',
    description: 'Bearer JWT dari Cloud SSO Dosen.',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OAT\Schema(
    schema: 'Vehicle',
    type: 'object',
    properties: [
        new OAT\Property(property: 'id', type: 'integer', example: 1),
        new OAT\Property(property: 'vehicle_code', type: 'string', example: 'VH-001'),
        new OAT\Property(property: 'plate_number', type: 'string', example: 'B 1022 TIA'),
        new OAT\Property(property: 'brand', type: 'string', example: 'Toyota'),
        new OAT\Property(property: 'model', type: 'string', example: 'Avanza'),
        new OAT\Property(property: 'vehicle_type', type: 'string', example: 'MPV'),
        new OAT\Property(property: 'capacity', type: 'integer', example: 7),
        new OAT\Property(property: 'fuel_type', type: 'string', example: 'Gasoline'),
        new OAT\Property(property: 'status', type: 'string', enum: ['Available', 'In-Use', 'Maintenance'], example: 'Available'),
        new OAT\Property(property: 'last_service_date', type: 'string', format: 'date', nullable: true, example: '2026-05-10'),
        new OAT\Property(property: 'notes', type: 'string', nullable: true, example: 'Siap digunakan untuk perjalanan dinas.'),
        new OAT\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OAT\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OAT\Schema(
    schema: 'ErrorResponse',
    type: 'object',
    properties: [
        new OAT\Property(property: 'status', type: 'string', example: 'error'),
        new OAT\Property(property: 'message', type: 'string', example: 'Detail pesan kesalahan'),
        new OAT\Property(property: 'errors', nullable: true),
    ]
)]
class VehicleController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/api/v1/vehicles",
     *     tags={"Vehicles"},
     *     summary="Mengambil daftar seluruh kendaraan beserta status ketersediaannya",
     *     security={{"iaeApiKey":{}}},
     *     @OA\Response(response=200, description="Data kendaraan berhasil diambil", @OA\JsonContent(ref="#/components/schemas/SuccessVehicleListResponse")),
     *     @OA\Response(response=401, description="API Key salah atau kosong", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    #[OAT\Get(
        path: '/api/v1/vehicles',
        summary: 'Mengambil daftar seluruh kendaraan beserta status ketersediaannya',
        security: [['iaeApiKey' => []]],
        tags: ['Vehicles'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Data kendaraan berhasil diambil',
                content: new OAT\JsonContent(
                    type: 'object',
                    properties: [
                        new OAT\Property(property: 'status', type: 'string', example: 'success'),
                        new OAT\Property(property: 'message', type: 'string', example: 'Data retrieved successfully'),
                        new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(ref: '#/components/schemas/Vehicle')),
                        new OAT\Property(property: 'meta', type: 'object', properties: [
                            new OAT\Property(property: 'service_name', type: 'string', example: 'Vehicle-Service'),
                            new OAT\Property(property: 'api_version', type: 'string', example: 'v1'),
                        ]),
                    ]
                )
            ),
            new OAT\Response(response: 401, description: 'API Key salah atau kosong', content: new OAT\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function index()
    {
        $vehicles = Vehicle::query()
            ->orderBy('vehicle_code')
            ->get();

        return $this->successResponse($vehicles);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/vehicles/{id}",
     *     tags={"Vehicles"},
     *     summary="Mengambil detail spesifik satu kendaraan",
     *     security={{"iaeApiKey":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer"), example=1),
     *     @OA\Response(response=200, description="Detail kendaraan berhasil diambil", @OA\JsonContent(
     *         @OA\Property(property="status", type="string", example="success"),
     *         @OA\Property(property="message", type="string", example="Data retrieved successfully"),
     *         @OA\Property(property="data", ref="#/components/schemas/Vehicle"),
     *         @OA\Property(property="meta", type="object",
     *             @OA\Property(property="service_name", type="string", example="Vehicle-Service"),
     *             @OA\Property(property="api_version", type="string", example="v1")
     *         )
     *     )),
     *     @OA\Response(response=401, description="API Key salah atau kosong", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *     @OA\Response(response=404, description="Data tidak ditemukan", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    #[OAT\Get(
        path: '/api/v1/vehicles/{id}',
        summary: 'Mengambil detail spesifik satu kendaraan',
        security: [['iaeApiKey' => []]],
        tags: ['Vehicles'],
        parameters: [
            new OAT\Parameter(name: 'id', in: 'path', required: true, schema: new OAT\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Detail kendaraan berhasil diambil',
                content: new OAT\JsonContent(
                    type: 'object',
                    properties: [
                        new OAT\Property(property: 'status', type: 'string', example: 'success'),
                        new OAT\Property(property: 'message', type: 'string', example: 'Data retrieved successfully'),
                        new OAT\Property(property: 'data', ref: '#/components/schemas/Vehicle'),
                        new OAT\Property(property: 'meta', type: 'object', properties: [
                            new OAT\Property(property: 'service_name', type: 'string', example: 'Vehicle-Service'),
                            new OAT\Property(property: 'api_version', type: 'string', example: 'v1'),
                        ]),
                    ]
                )
            ),
            new OAT\Response(response: 401, description: 'API Key salah atau kosong', content: new OAT\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OAT\Response(response: 404, description: 'Data tidak ditemukan', content: new OAT\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function show(int $id)
    {
        $vehicle = Vehicle::find($id);

        if (! $vehicle) {
            return $this->errorResponse('Vehicle data not found', null, 404);
        }

        return $this->successResponse($vehicle);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/vehicles",
     *     tags={"Vehicles"},
     *     summary="Menambahkan data kendaraan baru",
     *     security={{"iaeApiKey":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"vehicle_code","plate_number","brand","model","vehicle_type","capacity","fuel_type","status"},
     *         @OA\Property(property="vehicle_code", type="string", example="VH-005"),
     *         @OA\Property(property="plate_number", type="string", example="B 9001 NEW"),
     *         @OA\Property(property="brand", type="string", example="Toyota"),
     *         @OA\Property(property="model", type="string", example="Innova"),
     *         @OA\Property(property="vehicle_type", type="string", example="MPV"),
     *         @OA\Property(property="capacity", type="integer", example=7),
     *         @OA\Property(property="fuel_type", type="string", example="Diesel"),
     *         @OA\Property(property="status", type="string", enum={"Available","In-Use","Maintenance"}, example="Available"),
     *         @OA\Property(property="last_service_date", type="string", nullable=true, format="date", example="2026-05-30"),
     *         @OA\Property(property="notes", type="string", nullable=true, example="Kendaraan baru untuk perjalanan dinas luar kota.")
     *     )),
     *     @OA\Response(response=201, description="Data kendaraan berhasil ditambahkan"),
     *     @OA\Response(response=400, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *     @OA\Response(response=401, description="API Key salah atau kosong", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    #[OAT\Post(
        path: '/api/v1/vehicles',
        summary: 'Menambahkan data kendaraan baru',
        security: [['iaeApiKey' => []]],
        tags: ['Vehicles'],
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(
                required: ['vehicle_code', 'plate_number', 'brand', 'model', 'vehicle_type', 'capacity', 'fuel_type', 'status'],
                properties: [
                    new OAT\Property(property: 'vehicle_code', type: 'string', example: 'VH-005'),
                    new OAT\Property(property: 'plate_number', type: 'string', example: 'B 9001 NEW'),
                    new OAT\Property(property: 'brand', type: 'string', example: 'Toyota'),
                    new OAT\Property(property: 'model', type: 'string', example: 'Innova'),
                    new OAT\Property(property: 'vehicle_type', type: 'string', example: 'MPV'),
                    new OAT\Property(property: 'capacity', type: 'integer', example: 7),
                    new OAT\Property(property: 'fuel_type', type: 'string', example: 'Diesel'),
                    new OAT\Property(property: 'status', type: 'string', enum: ['Available', 'In-Use', 'Maintenance'], example: 'Available'),
                    new OAT\Property(property: 'last_service_date', type: 'string', format: 'date', nullable: true, example: '2026-05-30'),
                    new OAT\Property(property: 'notes', type: 'string', nullable: true, example: 'Kendaraan baru untuk perjalanan dinas luar kota.'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OAT\Response(response: 201, description: 'Data kendaraan berhasil ditambahkan'),
            new OAT\Response(response: 400, description: 'Validation error', content: new OAT\JsonContent(ref: '#/components/schemas/ErrorResponse')),
            new OAT\Response(response: 401, description: 'API Key salah atau kosong', content: new OAT\JsonContent(ref: '#/components/schemas/ErrorResponse')),
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_code' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'vehicle_code')],
            'plate_number' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'plate_number')],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'vehicle_type' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1'],
            'fuel_type' => ['required', 'string', 'max:100'],
            'status' => ['required', Rule::in(Vehicle::STATUSES)],
            'last_service_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $vehicle = Vehicle::create($validator->validated());

        return $this->successResponse($vehicle, 'Vehicle data created successfully', 201);
    }
}
