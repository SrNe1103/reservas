<?php
session_start();
header('Content-type: applications/json; charset=utf-8');
require_once "pdo.php";
date_default_timezone_set('America/Santiago');

$hoy = date('y-m-d'); // set today

$length = $_POST['dias']; // recieve an input of days since today
// $length = 7;
if ($length == 'todas'){ // if input is set to all, set a far enough date
    $dias = date('y-m-d', strtotime('+ 1 year'));
} else {
    $dias = date('y-m-d', strtotime('+ '.$length.' days')); // if it's numeric, set a date since today with that span
}

$search = $pdo->prepare("SELECT * FROM reservas WHERE final > :hoy AND inicio <= :dias ORDER BY inicio"); // search for reservations that start or end between the window selected.
$search->execute(array(':hoy' => $hoy, ':dias' => $dias));

$reservas = [];

while ($reserva = $search->fetch(PDO::FETCH_ASSOC)){
    $reservas[] = $reserva; //store all the reservations found
}
foreach ($reservas as &$reserva){ // loop through the reservations looking for the cabs associated to that reservation
    $search = $pdo->prepare("SELECT cab_id FROM assign WHERE reserva_id = :reserva_id");
    $search->execute(array(':reserva_id' => $reserva['reserva_id']));
    $n = 0;
    while ($cab = $search->fetch(PDO::FETCH_ASSOC)){
        $reserva['cab'][] = $cab['cab_id']; // make an array of the cabs
        $n += 1;
    }
    $search = $pdo->prepare("SELECT nombre FROM clientes WHERE cliente_id = :cliente_id"); // look for the associated client
    $search->execute(array(':cliente_id' => $reserva['cliente_id']));
    $nombre = $search->fetch(PDO::FETCH_ASSOC);
    $reserva['nombre'] = $nombre['nombre'];
}
echo (json_encode($reservas)); //send the information.





