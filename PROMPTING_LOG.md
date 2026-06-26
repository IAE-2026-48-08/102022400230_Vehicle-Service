# PROMPTING_LOG

## Identitas Tugas

- Nama: Tiara
- NIM/API Key: 102022400230
- Mata kuliah: Integrasi Aplikasi Enterprise
- Judul proses bisnis: Penugasan Perjalanan Dinas (Dispatching)
- Service: Vehicle-Service
- Resource: vehicles

## Rekap Prompting AI

Saya ditugaskan untuk membangun sebuah mini service mandiri bernama Vehicle-Service sebagai bagian dari pemenuhan tugas mata kuliah Integrasi Aplikasi Enterprise. Service ini dibuat untuk proses bisnis Penugasan Perjalanan Dinas (Dispatching), dan bagian yang menjadi tanggung jawab saya adalah Service Data Kendaraan dengan resource utama vehicles. Saya meminta project ini dibuat menggunakan Laravel 11 dan harus bisa berjalan sebagai REST API dengan prefix /api/v1.

Saya menjelaskan bahwa service ini digunakan pada tahap awal proses perjalanan dinas, yaitu ketika admin menerima request perjalanan, lalu mengecek daftar kendaraan yang tersedia di garasi. Data kendaraan ini digunakan untuk melihat kendaraan mana yang memiliki status Available, sehingga nantinya bisa dipakai oleh service lain seperti Service Penjadwalan Driver untuk membuat Surat Tugas Digital. Jika kendaraan sedang digunakan, maka status kendaraan dapat berubah menjadi In-Use, sedangkan jika kendaraan sedang perawatan maka statusnya menjadi Maintenance.

Saya meminta dibuatkan endpoint utama untuk Vehicle-Service, yaitu GET /api/v1/vehicles untuk mengambil daftar seluruh kendaraan beserta status ketersediaannya, GET /api/v1/vehicles/{id} untuk mengambil detail spesifik satu kendaraan, dan POST /api/v1/vehicles untuk menambahkan data kendaraan baru. Saya juga meminta agar endpoint tersebut menggunakan status code yang tepat, seperti 200 untuk GET berhasil, 201 untuk POST berhasil, 400 untuk validation error, 401 untuk API Key salah atau kosong, dan 404 untuk data tidak ditemukan.

Saya juga meminta agar semua response API menggunakan format JSON wrapper sesuai Standard Integration Contract. Untuk response sukses, formatnya harus berisi status, message, data, dan meta yang mencantumkan service_name yaitu Vehicle-Service dan api_version yaitu v1. Untuk response gagal, formatnya harus berisi status, message, dan errors, supaya response dari service ini konsisten dan mudah dipahami oleh service lain.

Untuk keamanan, saya meminta semua endpoint diproteksi menggunakan API Key yang dikirim melalui request header. Header yang digunakan adalah X-IAE-KEY dengan value 102022400230. Saya juga meminta value API Key tersebut disimpan di file .env sebagai X_IAE_KEY_VALUE=102022400230. Selain itu, saya meminta dibuatkan middleware bernama CheckApiKey, lalu karena project menggunakan Laravel 11, middleware tersebut didaftarkan di bootstrap/app.php menggunakan alias iaekey.

Pada bagian database, saya meminta dibuatkan table vehicles dengan field id, vehicle_code, plate_number, brand, model, vehicle_type, capacity, fuel_type, status, last_service_date, notes, created_at, dan updated_at. Saya juga menentukan bahwa status kendaraan minimal harus mendukung tiga nilai, yaitu Available, In-Use, dan Maintenance. Selain itu, saya meminta dibuatkan migration, model, controller, route, middleware, trait response, dan seeder data contoh agar project bisa langsung diuji setelah dijalankan.

Saya juga meminta validasi pada endpoint POST /api/v1/vehicles. Field vehicle_code wajib diisi dan harus unique, plate_number wajib diisi dan harus unique, brand, model, vehicle_type, fuel_type, dan status wajib diisi, capacity wajib berupa integer, status hanya boleh berisi Available, In-Use, atau Maintenance, sedangkan last_service_date boleh kosong tetapi harus berupa date jika diisi, dan notes boleh kosong berupa string.

Setelah REST API dibuat, saya meminta ditambahkan dokumentasi Swagger/OpenAPI menggunakan L5-Swagger. Saya meminta Swagger UI bisa diakses melalui /api/documentation, menampilkan seluruh endpoint vehicles, dan mendukung security API Key X-IAE-KEY agar endpoint bisa dites langsung melalui Swagger dengan tombol Authorize.

Saya juga meminta ditambahkan GraphQL menggunakan Lighthouse. GraphQL endpoint harus menggunakan /graphql, dan minimal memiliki query vehicles untuk mengambil semua data kendaraan serta vehicle(id: ID) untuk mengambil satu kendaraan berdasarkan id. Saya juga meminta contoh query GraphQL agar bisa diuji dengan memilih field yang dibutuhkan oleh client.

Selain itu, saya meminta dibuatkan Dockerfile dan docker-compose.yml agar project bisa dijalankan menggunakan Docker. Saya juga meminta dibuatkan README.md lengkap yang berisi deskripsi service, penjelasan proses bisnis Penugasan Perjalanan Dinas, daftar endpoint REST, cara menjalankan project tanpa Docker, cara menjalankan project dengan Docker, cara test API Key, cara akses Swagger, cara akses GraphQL, serta contoh request dan response JSON.

Setelah project selesai dibuat, saya mencoba menjalankannya menggunakan php artisan serve, tetapi saat membuka http://127.0.0.1:8000/ yang muncul masih halaman default Laravel. Saya kemudian menjelaskan bahwa saya tidak ingin tampilan default Laravel karena terlihat membingungkan, dan saya ingin project langsung menampilkan tampilan seperti punya teman saya. Awalnya saya meminta dibuatkan tampilan dashboard untuk Vehicle-Service, tetapi setelah saya tunjukkan contoh, saya menjelaskan bahwa yang saya maksud adalah saat project dijalankan langsung masuk ke halaman Swagger UI.

Akhirnya saya meminta agar halaman utama / diarahkan langsung ke Swagger UI seperti project teman saya. Setelah itu, route utama project diubah agar ketika membuka http://127.0.0.1:8000/, halaman langsung redirect ke /api/documentation#/Vehicles, sehingga tampilan pertama yang muncul adalah dokumentasi Swagger Vehicle-Service, bukan halaman default Laravel.

Untuk Tugas 3, saya meminta AI membantu melanjutkan project Vehicle-Service berdasarkan proses bisnis yang sama, yaitu Penugasan Perjalanan Dinas atau Dispatching. Saya menjelaskan bahwa Tugas 3 meminta progress tugas besar yang harus terintegrasi dengan layanan pusat, yaitu Federated SSO, Legacy SOAP/XML, dan RabbitMQ.

Saya meminta AI menentukan transaksi kritis yang paling sesuai untuk Vehicle-Service. Transaksi yang dipilih adalah penugasan kendaraan untuk perjalanan dinas, karena transaksi ini mengubah status kendaraan dari Available menjadi In-Use. Saya meminta transaksi ini dilindungi SSO, divalidasi ke sistem audit legacy SOAP/XML, dan setelah berhasil mengirim event bisnis ke RabbitMQ.

Saya meminta AI menambahkan middleware SSO untuk membaca JWT dari header Authorization: Bearer token, mengambil payload user, dan memetakan role dari SSO ke tabel role lokal. Saya juga meminta role lokal seperti fleet_admin dan dispatch_admin digunakan sebagai role yang boleh melakukan transaksi dispatch.

Saya meminta AI menambahkan SOAP client yang mengubah data JSON transaksi dispatch menjadi XML Envelope kaku, lalu menyimpan ReceiptNumber dari response SOAP. Untuk kebutuhan lokal, saya meminta mode mock agar alur tetap bisa diuji jika endpoint dosen belum tersedia.

Saya juga meminta AI menambahkan publisher RabbitMQ untuk mengirim event JSON vehicle.dispatched setelah transaksi berhasil. Jika RabbitMQ dosen belum tersedia, publisher dibuat memiliki mode mock agar tidak memicu error lokal, tetapi tetap memperlihatkan payload event yang akan dikirim.

Terakhir, saya meminta AI membuat dokumen analisis_tugas_3.md berisi justifikasi transaksi kritis, sequence diagram internal, penjelasan interaksi dengan SSO, SOAP, RabbitMQ, dan ringkasan capaian teknis sesuai rubrik Tugas 3.

Setelah itu saya memberikan PDF dari dosen yang berisi URL dan akun Tugas IAE. Dari PDF tersebut, saya meminta AI menyesuaikan konfigurasi Tugas 3 agar mengarah ke layanan pusat dosen, yaitu `https://iae-sso.virtualfri.id`, endpoint SOAP `/soap/v1/audit`, dan endpoint message publish `/api/v1/messages/publish`. Saya juga meminta SOAP Envelope mengikuti tag wajib dosen yaitu TeamID, ActivityName, dan LogContent.
