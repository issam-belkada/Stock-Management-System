<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $sale->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #222;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .company {
            text-align: right;
        }

        .company h2 {
            margin: 0;
        }

        .invoice-details {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <h1>TechStore DZ</h1>
            <div>Rue de la Liberté, Chlef, Algérie</div>
            <div>contact@techstore.dz | +213 55 55 55 55</div>
        </div>
        <div class="company">
            <h2>INVOICE</h2>
            <div>Invoice #: <strong>{{ $sale->id }}</strong></div>
            <div>Date: {{ $date }}</div>
        </div>
    </div>

    <div class="invoice-details">
        <strong>Billed to:</strong>
        <div>Client name (if any)</div>
        <div>Client address (if any)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-right">Price (DZD)</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal (DZD)</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1;
            $grand = 0; @endphp
            @foreach($sale->saleItems as $item)
                @php $subtotal = $item->subtotal;
                $grand += $subtotal; @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ optional($item->product)->name ?? 'Product #' . $item->product_id }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">{{ number_format($grand, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 25px;">Thank you for your business.</p>

    <footer>
        TechStore DZ — Rue de la Liberté, Chlef, Algérie — contact@techstore.dz — +213 55 55 55 55
    </footer>
</body>

</html>