<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport Global - Carte Scolaire</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0;
            color: #7f8c8d;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #ecf0f1;
        }
        .stat-box {
            display: inline-block;
            width: 30%;
            text-align: center;
            padding: 15px;
            margin: 5px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-label {
            font-size: 11px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Global - Gestion des Cartes Scolaires</h1>
        <p>Date de generation: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Statistiques des Eleves</div>
        <table>
            <tr>
                <th>Indicateur</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>Total des eleves</td>
                <td>{{ $stats['total_eleves'] }}</td>
            </tr>
            <tr>
                <td>Eleves actifs</td>
                <td>{{ $stats['eleves_actifs'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Statistiques des Photos</div>
        <table>
            <tr>
                <th>Indicateur</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>Total des photos</td>
                <td>{{ $stats['total_photos'] }}</td>
            </tr>
            <tr>
                <td>Photos approuvees</td>
                <td>{{ $stats['photos_approuvees'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Statistiques des Cartes Scolaires</div>
        <table>
            <tr>
                <th>Indicateur</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>Total des cartes</td>
                <td>{{ $stats['total_cartes'] }}</td>
            </tr>
            <tr>
                <td>Cartes distribuees</td>
                <td>{{ $stats['cartes_distribuees'] }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Rapport genere automatiquement par le Systeme de Gestion des Cartes Scolaires
    </div>
</body>
</html>