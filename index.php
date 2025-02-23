<?php
session_start();
require_once "pdo.php";

$hoy = date('y-m-d'); // set today

$proximas = $pdo->prepare("SELECT * FROM reservas WHERE inicio >= :hoy ORDER BY inicio LIMIT 5");
$proximas->execute(array(":hoy" => $hoy));

$datos = []
while ($reserva = $proximas->fetch(PDO::FETCH_ASSOC)){ //mientras hayan próximas reservas

    $cabs = []; // buscar cabañas reservadas
    $buscar = $pdo->prepare("SELECT cab_id FROM assign WHERE reserva_id = :reserva_id");
    $buscar->execute(array(":reserva_id" => $reserva["reserva_id"]));
    while ($cab = $buscar->fetch(PDO::FETCH_ASSOC)){
        $cabs[] = $cab["cab_id"];
    }
    $buscar = $pdo->prepare("SELECT nombre, telefono FROM clientes WHERE cliente_id = :cliente_id");
    $buscar->execute(array(":cliente_id" => $reserva["cliente_id"]));
    $cliente = $buscar->fetch(PDO::FETCH_ASSOC); //Buscar datos del cliente

    $datos[] = [$reserva, $cabs, $cliente];

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="favicon.ico">
</head>
<body>
    <h1>Administración Reservas - Ecoturismo Lemunantu</h1>
    <p>Esta es la página principal. De aquí puedes dirigirte, según corresponda, a la sección que necesites.</p>
    <h2>Selecciona la dónde quieres ir:</h2>
        <?php require_once 'nav.php'; ?>
    <section id="resumen">

        <!-- <h3>Resumen de las próximas reservas</h3>
        <div>
        <?php
        if (count($datos) == 0){
            echo "<p>No hay próximas reservas</p>";
        } else {
            echo "<table border='1'>";
            echo "<tr><th>Inicio</th><th>Final</th><th>Cabañas</th><th>Nombre</th><th>Número</th>"
            foreach ($datos as $reserva){
                echo "<tr><td>".$reserva[0]['inicio']."</td><td>".$reserva[0]['final']."</td><td>";
                foreach ($reserva[1] as $cabana){
                    if ($cabana == 17){
                        echo "Salón";
                    }
                    echo $cabana." ";
                }
            }
        }
        ?>
        </div> -->

    </section>
</body>
</html>