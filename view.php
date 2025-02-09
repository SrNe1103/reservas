<?php
session_start();
require_once "pdo.php";

$search = $pdo->prepare("SELECT * FROM reservas WHERE reserva_id = :reserva_id");
$search->execute(array(':reserva_id' => $_GET['id']));
$reserva = $search->fetch(PDO::FETCH_ASSOC);

$search = $pdo->prepare("SELECT * FROM assign WHERE reserva_id = :reserva_id");
$search->execute(array(':reserva_id' => $_GET['id']));
$cabanas = []
while ($cab = $search->execute(array(':reserva_id' => $_GET['id']))){
    $name = $pdo->prepare("SELECT * FROM cab WHERE cab_id = :cab_id");
    $name->execute(array(':cab_id' => $cab['cab_id']));
    while ($cabanas[] = $name->fetch(PDO::FETCH_ASSOC)){}
}

$search = $pdo->prepare("SELECT * FROM clientes WHERE cliente_id = :cliente_id");
$search->execute(array(':cliente_id' => $reserva['cliente_id']));
$cliente = $search->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva <?=$_GET['id']?></title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <h1>Administración Reservas Ecoturismo Lemunantu</h1>
    <?php require_once 'nav.php'; ?>
    <p>A continuación puedes ver los datos de la reserva seleccionada.</p>
    <section id="calendar">
        <h2>Datos de reserva</h2>
        
        <table id="reserva" border='1'>
            <tr>
                <th>A nombre de:</th><td><?=$cliente['nombre']?></td>
                <th>Númeto:</th><td><?=$cliente['numero']?></td>
                <th>Inicio:</th><td><?=$reserva['inicio']?></td>
                <th>Final:</th><td><?=$reserva['final']?></td>
                <th>Inicio:</th><td><?=$reserva['inicio']?></td>

            </tr>

        </table>
        <script src="js/hoja.js"></script>
    </section>
</body>
</html>