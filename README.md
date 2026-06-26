# 102022400230_Tiara-Vehicle-Service

Mini-service Laravel 11 untuk **Service Data Kendaraan** pada proses bisnis **Penugasan Perjalanan Dinas (Dispatching)**.

Service ini digunakan admin untuk melihat armada kendaraan, mengecek status ketersediaan kendaraan, mengambil detail kendaraan, dan menambahkan kendaraan baru. Data kendaraan dengan status `Available` dapat dipakai oleh service lain, misalnya Service Penjadwalan Driver, untuk membuat Surat Tugas Digital. Saat kendaraan dipakai, status dapat berubah menjadi `In-Use`; saat perawatan, status menjadi `Maintenance`.

## Teknologi

- Laravel 11
- REST API prefix `/api/v1`
- SQLite default untuk pengujian lokal
- L5-Swagger untuk OpenAPI
- Lighthouse untuk GraphQL
- Docker dan Docker Compose

## API Key

Semua endpoint REST vehicles dan endpoint GraphQL dilindungi API Key.

- Header: `X-IAE-KEY`
- Value: `102022400230`
- Environment variable: `X_IAE_KEY_VALUE=102022400230`

## Struktur File Penting

```text
app/Http/Controllers/Api/V1/VehicleController.php
app/Http/Middleware/CheckApiKey.php
app/Models/Vehicle.php
app/Traits/ApiResponse.php
bootstrap/app.php
database/migrations/2026_06_02_000001_create_vehicles_table.php
database/seeders/DatabaseSeeder.php
database/seeders/VehicleSeeder.php
graphql/schema.graphql
routes/api.php
routes/web.php
resources/views/graphql-playground.blade.php
config/l5-swagger.php
config/lighthouse.php
Dockerfile
docker-compose.yml
PROMPTING_LOG.md
```

## Menjalankan Tanpa Docker

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan l5-swagger:generate
php artisan serve
```

Server berjalan di `http://127.0.0.1:8000`.

## Menjalankan Dengan Docker

```bash
docker compose up --build
```

Server berjalan di `http://localhost:8000`.

## Endpoint REST

| Method | Endpoint | Deskripsi |
| --- | --- | --- |
| GET | `/api/v1/vehicles` | Mengambil daftar seluruh kendaraan beserta status ketersediaannya |
| GET | `/api/v1/vehicles/{id}` | Mengambil detail spesifik satu kendaraan |
| POST | `/api/v1/vehicles` | Menambahkan data kendaraan baru |

## Standard Integration Contract

Response sukses:

```json
{
  "status": "success",
  "message": "Data retrieved successfully",
  "data": {},
  "meta": {
    "service_name": "Vehicle-Service",
    "api_version": "v1"
  }
}
```

Response gagal:

```json
{
  "status": "error",
  "message": "Detail pesan kesalahan",
  "errors": null
}
```

## Contoh Test API Key

Tanpa API Key akan menghasilkan `401`.

```bash
curl http://127.0.0.1:8000/api/v1/vehicles
```

Dengan API Key:

```bash
curl -H "X-IAE-KEY: 102022400230" http://127.0.0.1:8000/api/v1/vehicles
```

## Contoh GET Vehicles

Request:

```bash
curl -H "X-IAE-KEY: 102022400230" http://127.0.0.1:8000/api/v1/vehicles
```

Response:

```json
{
  "status": "success",
  "message": "Data retrieved successfully",
  "data": [
    {
      "id": 1,
      "vehicle_code": "VH-001",
      "plate_number": "B 1022 TIA",
      "brand": "Toyota",
      "model": "Avanza",
      "vehicle_type": "MPV",
      "capacity": 7,
      "fuel_type": "Gasoline",
      "status": "Available",
      "last_service_date": "2026-05-10",
      "notes": "Siap digunakan untuk perjalanan dinas dalam kota.",
      "created_at": "2026-06-02T00:00:00.000000Z",
      "updated_at": "2026-06-02T00:00:00.000000Z"
    }
  ],
  "meta": {
    "service_name": "Vehicle-Service",
    "api_version": "v1"
  }
}
```

## Contoh GET Vehicle Detail

```bash
curl -H "X-IAE-KEY: 102022400230" http://127.0.0.1:8000/api/v1/vehicles/1
```

Jika tidak ditemukan, response `404`:

```json
{
  "status": "error",
  "message": "Vehicle data not found",
  "errors": null
}
```

## Contoh POST Vehicle

Request:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/vehicles \
  -H "Content-Type: application/json" \
  -H "X-IAE-KEY: 102022400230" \
  -d "{\"vehicle_code\":\"VH-005\",\"plate_number\":\"B 9001 NEW\",\"brand\":\"Toyota\",\"model\":\"Innova\",\"vehicle_type\":\"MPV\",\"capacity\":7,\"fuel_type\":\"Diesel\",\"status\":\"Available\",\"last_service_date\":\"2026-05-30\",\"notes\":\"Kendaraan baru untuk perjalanan dinas luar kota.\"}"
```

Response `201`:

```json
{
  "status": "success",
  "message": "Vehicle data created successfully",
  "data": {
    "vehicle_code": "VH-005",
    "plate_number": "B 9001 NEW",
    "brand": "Toyota",
    "model": "Innova",
    "vehicle_type": "MPV",
    "capacity": 7,
    "fuel_type": "Diesel",
    "status": "Available",
    "last_service_date": "2026-05-30",
    "notes": "Kendaraan baru untuk perjalanan dinas luar kota.",
    "id": 5
  },
  "meta": {
    "service_name": "Vehicle-Service",
    "api_version": "v1"
  }
}
```

Validation error menggunakan status code `400`.

## Swagger/OpenAPI

Generate dokumentasi:

```bash
php artisan l5-swagger:generate
```

Akses Swagger UI:

```text
http://127.0.0.1:8000/api/documentation
```

Swagger menampilkan endpoint vehicles dan security API Key `X-IAE-KEY`.

## GraphQL

Endpoint GraphQL:

```text
POST http://127.0.0.1:8000/graphql
```

GraphQL Playground sederhana:

```text
http://127.0.0.1:8000/graphql-playground
```

Contoh query semua kendaraan:

```graphql
{
  vehicles {
    id
    vehicle_code
    plate_number
    brand
    model
    vehicle_type
    capacity
    fuel_type
    status
  }
}
```

Contoh query detail kendaraan:

```graphql
{
  vehicle(id: 1) {
    id
    vehicle_code
    plate_number
    status
    last_service_date
  }
}
```

Contoh cURL GraphQL:

```bash
curl -X POST http://127.0.0.1:8000/graphql \
  -H "Content-Type: application/json" \
  -H "X-IAE-KEY: 102022400230" \
  -d "{\"query\":\"{ vehicles { id vehicle_code plate_number status } }\"}"
```

## Status Code

- `200`: GET berhasil
- `201`: POST berhasil
- `400`: validation error
- `401`: API Key salah atau kosong
- `404`: data tidak ditemukan

## Tambahan Tugas 3

Tugas 3 menambahkan integrasi progress tugas besar:

- Federated SSO: membaca payload JWT dari header `Authorization: Bearer <token>` dan memetakan role ke tabel lokal.
- SOAP XML Client: transaksi dispatch kendaraan dikirim sebagai SOAP Envelope ke Legacy Audit dan menyimpan `ReceiptNumber`.
- AMQP Publisher: event JSON `vehicle.dispatched` dikirim ke RabbitMQ atau mode mock lokal.
- Dokumen analisis: `analisis_tugas_3.md`.

URL pusat dosen dari PDF tugas:

```text
SSO Base URL: https://iae-sso.virtualfri.id
JWKS: https://iae-sso.virtualfri.id/api/v1/auth/jwks
SOAP Audit: https://iae-sso.virtualfri.id/soap/v1/audit
Message Publish: https://iae-sso.virtualfri.id/api/v1/messages/publish
```

Endpoint SSO profile:

```text
GET /api/v1/sso/profile
```

Endpoint transaksi kritis:

```text
POST /api/v1/vehicles/{id}/dispatch
```

Contoh payload dispatch:

```json
{
  "trip_reference": "TRIP-T3-001",
  "requester_name": "Admin Perjalanan Dinas",
  "destination": "Kantor Cabang Bandung",
  "start_date": "2026-06-10",
  "end_date": "2026-06-11"
}
```

Konfigurasi lokal default memakai `LEGACY_AUDIT_MODE=mock`, `RABBITMQ_DRIVER=mock`, dan `RABBITMQ_ENABLED=false`, sehingga alur dapat diuji tanpa credential dosen. Jika ingin mencoba ke pusat dosen, isi credential SSO di `.env`, lalu ubah:

```env
LEGACY_AUDIT_MODE=live
RABBITMQ_DRIVER=http
RABBITMQ_ENABLED=true
SSO_M2M_API_KEY=isi_dari_dosen
```
