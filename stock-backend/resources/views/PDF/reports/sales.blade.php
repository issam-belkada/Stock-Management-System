<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport de ventes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header .logo img {
            max-height: 60px;
        }

        .header .company-info {
            text-align: right;
            font-size: 12px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h1 {
            margin: 0;
            font-size: 24px;
            border-bottom: 2px solid #333;
            display: inline-block;
            padding-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info div {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #f2f2f2;
        }

        .totals {
            width: 100%;
            text-align: right;
            margin-top: 10px;
        }

        .totals span {
            display: block;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- En-tête société -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo Société">
            </div>
            <div class="company-info">
                <strong>TechStore DZ</strong><br>
                Rue de la Liberté, Chlef, Algérie<br>
                contact@techstore.dz | +213 55 55 55 55
            </div>
        </div>

        <!-- Titre du rapport -->
        <div class="title">
            <h1>Rapport de ventes</h1>
        </div>

        <!-- Informations période et mode -->
        <div class="info">
            <div><strong>Période :</strong> {{ $period['start'] }} au {{ $period['end'] }}</div>
            <div><strong>Mode :</strong> {{ $mode ?? 'N/A' }}</div>
        </div>

        <!-- Tableau des ventes -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Produit(s)</th>
                    <th>Quantité totale</th>
                    <th>Total (DZD)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $index => $sale)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                        <td>
                            @foreach($sale->saleItems as $item)
                                {{ $item->product->name ?? 'N/A' }} (x{{ $item->quantity ?? 0 }})<br>
                            @endforeach
                        </td>
                        <td>{{ $sale->saleItems->sum(fn($item) => $item->quantity) }}</td>
                        <td>{{ number_format($sale->total_amount ?? 0, 2) }}</td>
                        <td>{{ optional($sale->created_at)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucune vente pour cette période</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totaux -->
        @php
            $totalQuantity = $sales->sum(fn($sale) => $sale->saleItems->sum(fn($item) => $item->quantity));
        @endphp

        <div class="totals">
            <span>Total général : {{ number_format($totalSales ?? 0, 2) }} DZD</span>
            <span>Quantité totale vendue : {{ $totalQuantity }}</span>
        </div>
    </div>

    <div class="footer">
        Rapport généré le {{ now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>