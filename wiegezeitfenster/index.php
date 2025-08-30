<?php
// index.php – Formular mit Wahlmöglichkeiten
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Wiegefenster-Ausgabe</title>
</head>
<body>
    <h1>Wiegefenster-Ausgabe für Rinder</h1>
    <form action="verarbeiten.php" method="post" enctype="multipart/form-data">
        <label>Datum der Wiegung:</label><br>
        <input type="date" name="wiegung_datum" required><br><br>

        <label>CSV-Datei hochladen:</label><br>
        <input type="file" name="csv_datei" accept=".csv" required><br><br>

        <label>Maximales Alter (in Monaten):</label><br>
        <input type="number" name="max_alter" value="12" required><br><br>

        <label>Absetzfenster (Tage):</label><br>
        Von: <input type="number" name="absetz_von" value="150" required>
        Bis: <input type="number" name="absetz_bis" value="220" required><br><br>

        <label>Jahresfenster (Tage):</label><br>
        Von: <input type="number" name="jahres_von" value="320" required>
        Bis: <input type="number" name="jahres_bis" value="410" required><br><br>

        <label>Ausgabeformat:</label><br>
        <input type="checkbox" name="output_excel" value="1" checked> Excel<br>
        <input type="checkbox" name="output_pdf" value="1" checked> PDF<br><br>

        <button type="submit">Ausgabe erstellen</button>
    </form>
</body>
</html>
