<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiara Vehicle-Service</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --ink: #152238;
            --muted: #64748b;
            --line: #d8e0ec;
            --blue: #2563eb;
            --green: #15803d;
            --amber: #b45309;
            --red: #b91c1c;
            --violet: #6d28d9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            background: #172033;
            color: #fff;
            padding: 24px;
        }

        .wrap {
            width: min(1160px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mark {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border-radius: 8px;
            background: #f97316;
            font-weight: 800;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
        }

        .subtitle {
            margin: 4px 0 0;
            color: #cbd5e1;
            font-size: 14px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        a.button, button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            border: 0;
            border-radius: 6px;
            padding: 9px 13px;
            background: var(--blue);
            color: #fff;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
        }

        a.button.secondary {
            background: #334155;
        }

        main {
            padding: 24px 0 40px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 16px;
        }

        .label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .value {
            margin-top: 8px;
            font-size: 30px;
            font-weight: 800;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 16px;
            align-items: start;
        }

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
        }

        th, td {
            border-bottom: 1px solid var(--line);
            padding: 12px 10px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background: #eef3f9;
            color: #334155;
            font-size: 12px;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .badge {
            display: inline-flex;
            border-radius: 999px;
            padding: 4px 9px;
            font-size: 12px;
            font-weight: 800;
        }

        .Available {
            background: #dcfce7;
            color: var(--green);
        }

        .In-Use {
            background: #fef3c7;
            color: var(--amber);
        }

        .Maintenance {
            background: #fee2e2;
            color: var(--red);
        }

        form {
            display: grid;
            gap: 10px;
        }

        input, select, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 11px;
            color: var(--ink);
            font: inherit;
        }

        textarea {
            min-height: 76px;
            resize: vertical;
        }

        .hint {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .message {
            display: none;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
            line-height: 1.45;
        }

        .message.ok {
            display: block;
            background: #dcfce7;
            color: var(--green);
        }

        .message.fail {
            display: block;
            background: #fee2e2;
            color: var(--red);
        }

        .api-key {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            border: 1px solid #475569;
            border-radius: 6px;
            padding: 8px 10px;
            color: #e2e8f0;
            font-size: 13px;
        }

        code {
            font-family: Consolas, monospace;
        }

        @media (max-width: 940px) {
            .topbar, .layout {
                grid-template-columns: 1fr;
                display: grid;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 620px) {
            .stats {
                grid-template-columns: 1fr;
            }

            th:nth-child(4), td:nth-child(4),
            th:nth-child(5), td:nth-child(5) {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="wrap">
            <div class="topbar">
                <div class="brand">
                    <div class="mark">VS</div>
                    <div>
                        <h1>Tiara Vehicle-Service</h1>
                        <p class="subtitle">Service Data Kendaraan untuk Penugasan Perjalanan Dinas (Dispatching)</p>
                    </div>
                </div>
                <nav class="actions">
                    <a class="button" href="/api/documentation">Swagger</a>
                    <a class="button secondary" href="/graphql-playground">GraphQL</a>
                    <a class="button secondary" href="/api/v1/vehicles">REST API</a>
                </nav>
            </div>
            <div class="api-key">
                <span>API Key</span>
                <code>X-IAE-KEY: 102022400230</code>
            </div>
        </div>
    </header>

    <main>
        <div class="wrap">
            <section class="stats">
                <div class="card">
                    <div class="label">Total Kendaraan</div>
                    <div class="value" id="totalCount">0</div>
                </div>
                <div class="card">
                    <div class="label">Available</div>
                    <div class="value" id="availableCount">0</div>
                </div>
                <div class="card">
                    <div class="label">In-Use</div>
                    <div class="value" id="inUseCount">0</div>
                </div>
                <div class="card">
                    <div class="label">Maintenance</div>
                    <div class="value" id="maintenanceCount">0</div>
                </div>
            </section>

            <section class="layout">
                <div class="card">
                    <div class="panel-title">
                        <h2>Daftar Kendaraan</h2>
                        <button id="refreshBtn" type="button">Refresh</button>
                    </div>
                    <div id="tableStatus" class="hint">Memuat data kendaraan...</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Plat Nomor</th>
                                <th>Kendaraan</th>
                                <th>Kapasitas</th>
                                <th>BBM</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="vehicleRows"></tbody>
                    </table>
                </div>

                <aside class="card">
                    <h2>Tambah Kendaraan</h2>
                    <p class="hint">Form ini mengirim data ke endpoint <code>POST /api/v1/vehicles</code> memakai API Key otomatis.</p>
                    <div id="formMessage" class="message"></div>
                    <form id="vehicleForm">
                        <input name="vehicle_code" placeholder="Vehicle Code, contoh VH-005" required>
                        <input name="plate_number" placeholder="Plate Number, contoh B 9001 NEW" required>
                        <input name="brand" placeholder="Brand, contoh Toyota" required>
                        <input name="model" placeholder="Model, contoh Innova" required>
                        <input name="vehicle_type" placeholder="Vehicle Type, contoh MPV" required>
                        <input name="capacity" type="number" min="1" placeholder="Capacity" required>
                        <input name="fuel_type" placeholder="Fuel Type, contoh Diesel" required>
                        <select name="status" required>
                            <option value="Available">Available</option>
                            <option value="In-Use">In-Use</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                        <input name="last_service_date" type="date">
                        <textarea name="notes" placeholder="Notes"></textarea>
                        <button type="submit">Simpan Kendaraan</button>
                    </form>
                </aside>
            </section>
        </div>
    </main>

    <script>
        const apiKey = '102022400230';
        const headers = {
            'Content-Type': 'application/json',
            'X-IAE-KEY': apiKey,
        };

        async function loadVehicles() {
            const status = document.getElementById('tableStatus');
            const rows = document.getElementById('vehicleRows');
            status.textContent = 'Memuat data kendaraan...';
            rows.innerHTML = '';

            const response = await fetch('/api/v1/vehicles', { headers });
            const payload = await response.json();

            if (!response.ok) {
                status.textContent = payload.message || 'Gagal memuat data kendaraan.';
                return;
            }

            const vehicles = payload.data || [];
            const counts = {
                Available: vehicles.filter((vehicle) => vehicle.status === 'Available').length,
                'In-Use': vehicles.filter((vehicle) => vehicle.status === 'In-Use').length,
                Maintenance: vehicles.filter((vehicle) => vehicle.status === 'Maintenance').length,
            };

            document.getElementById('totalCount').textContent = vehicles.length;
            document.getElementById('availableCount').textContent = counts.Available;
            document.getElementById('inUseCount').textContent = counts['In-Use'];
            document.getElementById('maintenanceCount').textContent = counts.Maintenance;

            rows.innerHTML = vehicles.map((vehicle) => `
                <tr>
                    <td><strong>${escapeHtml(vehicle.vehicle_code)}</strong></td>
                    <td>${escapeHtml(vehicle.plate_number)}</td>
                    <td>${escapeHtml(vehicle.brand)} ${escapeHtml(vehicle.model)}<br><span class="hint">${escapeHtml(vehicle.vehicle_type)}</span></td>
                    <td>${vehicle.capacity}</td>
                    <td>${escapeHtml(vehicle.fuel_type)}</td>
                    <td><span class="badge ${vehicle.status}">${escapeHtml(vehicle.status)}</span></td>
                </tr>
            `).join('');

            status.textContent = vehicles.length ? 'Data kendaraan berhasil dimuat.' : 'Belum ada data kendaraan.';
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        document.getElementById('refreshBtn').addEventListener('click', loadVehicles);

        document.getElementById('vehicleForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const message = document.getElementById('formMessage');
            const formData = new FormData(form);
            const body = Object.fromEntries(formData.entries());
            body.capacity = Number(body.capacity);
            body.last_service_date = body.last_service_date || null;
            body.notes = body.notes || null;

            message.className = 'message';
            message.textContent = '';

            const response = await fetch('/api/v1/vehicles', {
                method: 'POST',
                headers,
                body: JSON.stringify(body),
            });
            const payload = await response.json();

            if (!response.ok) {
                const errors = payload.errors ? Object.values(payload.errors).flat().join(' ') : payload.message;
                message.className = 'message fail';
                message.textContent = errors || 'Gagal menyimpan kendaraan.';
                return;
            }

            message.className = 'message ok';
            message.textContent = 'Data kendaraan berhasil ditambahkan.';
            form.reset();
            await loadVehicles();
        });

        loadVehicles();
    </script>
</body>
</html>
