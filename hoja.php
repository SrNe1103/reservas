<?php
session_start();
require_once "pdo.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/hoja.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="icon" href="favicon.ico">
</head>
<body>
    <h1>Administración Reservas Ecoturismo Lemunantu</h1>
    <?php require_once 'nav.php'; ?>
    <p>A continuación puedes ver el calendario de reservas.</p>
    <section id="calendar">
        <h2>Hoja de reservas</h2>
        <strong>Aquí ira el calendario</strong>
        <p>
            <label for="dias">Dias a mostrar</label>
            <select name="dias" id="dias" onchange="hoja_reservas()">
                <option value="7">7 días</option>
                <option value="14">14 días</option>
                <option value="30">30 días</option>
                <option value="todas">Todas</option>
            </select>
        </p>
        <table id="hoja" border='1'>
            <tr>
                <th>Fecha</th><th>C1</th><th>C2</th><th>C3</th><th>C4</th><th>C5</th><th>C6</th><th>C7</th><th>C8</th><th>C9</th><th>C10</th><th>C11</th><th>C12</th><th>C13</th><th>C14</th><th>C15</th><th>C16</th><th>Salón</th>
            </tr>

        </table>
        <script src="js/hoja.js"></script>
    </section>
</body>
</html>