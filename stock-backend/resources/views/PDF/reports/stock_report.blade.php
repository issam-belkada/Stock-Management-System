<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport de Stock</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .totals {
            margin-top: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>

    <h2>Rapport des Mouvements de Stock</h2>

    <div class="period">
        Période : {{ $period['start'] }} → {{ $period['end'] }}
    </div>

    <div class="totals">
        Entrées Totales : {{ $total_in }}
        <br>
        Sorties Totales : {{ $total_out }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movements as $m)
                <tr>
                    <td>{{ $m->product->name }}</td>
                    <td>{{ strtoupper($m->type) }}</td>
                    <td>{{ $m->quantity }}</td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Rapport généré automatiquement par votre système de gestion de stock.
    </div>

</body>

</html>