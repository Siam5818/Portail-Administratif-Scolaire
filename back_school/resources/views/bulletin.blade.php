<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bulletin de Notes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .info-table td {
            padding: 4px 8px;
        }

        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .notes-table th,
        .notes-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">Bulletin de Notes</div>
        <div>Période : <strong>{{ ucfirst($periode) }}</strong> — Année : <strong>{{ $annee }}</strong></div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Élève :</strong> {{ $eleve->user->nom }} {{ $eleve->user->prenom }}</td>
            <td><strong>Classe :</strong> {{ $eleve->classe->libelle ?? 'Non définie' }}</td>
        </tr>
        <tr>
            <td><strong>Matricule :</strong> {{ $eleve->matricule }}</td>
            <td><strong>Date de naissance :</strong> {{ $eleve->date_naissance }}</td>
        </tr>
    </table>

    <table class="notes-table">
        <thead>
            <tr>
                <th>Matière</th>
                <th>Note</th>
                <th>Coefficient</th>
                <th>Note Pondérée</th>
                <th>Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notes as $note)
                <tr>
                    <td>{{ $note['matiere'] }}</td>
                    <td>{{ $note['note'] ?? '—' }}</td>
                    <td>{{ $note['coefficient'] }}</td>
                    <td>
                        @if ($note['note'] !== null)
                            {{ round($note['note'] * $note['coefficient'], 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $note['appreciation'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Moyenne Générale :</strong> {{ $moyenne }}</p>
        <p><strong>Mention :</strong> {{ $mention }}</p>
        <p>Fait le {{ now()->format('d/m/Y') }}</p>
    </div>

</body>

</html>
