<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport Classe - {{ $classe->nom }}</title>
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
        .header h2 {
            margin: 10px 0 0;
            font-size: 18px;
            color: #3498db;
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
            padding: 8px;
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
        <h1>Rapport de Classe</h1>
        <h2>{{ $classe->nom }}</h2>
        <p>Etablissement: {{ $classe->etablissement->nom ?? 'N/A' }}</p>
        <p>Date de generation: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Statistiques de la Classe</div>
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
            <tr>
                <td>Cartes distribuees</td>
                <td>{{ $stats['cartes_distribuees'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Liste des Eleves</div>
        <table>
            <tr>
                <th>N</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prenoms</th>
                <th>Statut</th>
            </tr>
            @foreach($classe->eleves as $index => $eleve)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $eleve->matricule }}</td>
                <td>{{ $eleve->nom }}</td>
                <td>{{ $eleve->prenoms }}</td>
                <td>{{ $eleve->statut }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        Rapport genere automatiquement par le Systeme de Gestion des Cartes Scolaires
    </div>
</body>
</html>