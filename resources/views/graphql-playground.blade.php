<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehicle-Service GraphQL Playground</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #172033;
        }
        main {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        textarea, pre, input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #c8d1e1;
            border-radius: 6px;
            background: #fff;
            color: #172033;
        }
        textarea {
            min-height: 420px;
            padding: 14px;
            font-family: Consolas, monospace;
            font-size: 14px;
        }
        pre {
            min-height: 420px;
            overflow: auto;
            padding: 14px;
            white-space: pre-wrap;
        }
        input {
            margin: 12px 0;
            padding: 10px 12px;
        }
        button {
            border: 0;
            border-radius: 6px;
            background: #1f6feb;
            color: #fff;
            cursor: pointer;
            padding: 10px 16px;
            font-weight: 700;
        }
        @media (max-width: 800px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main>
        <h1>Vehicle-Service GraphQL Playground</h1>
        <input id="apiKey" value="102022400230" aria-label="X-IAE-KEY">
        <div class="grid">
            <section>
                <textarea id="query">{
  vehicles {
    id
    vehicle_code
    plate_number
    brand
    model
    status
  }
}</textarea>
                <button id="run">Run Query</button>
            </section>
            <section>
                <pre id="result">Result will appear here.</pre>
            </section>
        </div>
    </main>
    <script>
        document.getElementById('run').addEventListener('click', async () => {
            const result = document.getElementById('result');
            result.textContent = 'Loading...';

            const response = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-IAE-KEY': document.getElementById('apiKey').value,
                },
                body: JSON.stringify({ query: document.getElementById('query').value }),
            });

            result.textContent = JSON.stringify(await response.json(), null, 2);
        });
    </script>
</body>
</html>
