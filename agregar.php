<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

    <h1>Administración Reservas Ecoturismo Lemunantu</h1>
    
    <?php require_once 'nav.php'; ?>
    
    <p>A continuación puedes ver el formulario para agregar una reserva.</p>
    
    <section id="Agregar">
    
        <h2>Agregar Reserva</h2>
        <strong>Aquí ira el Formulario</strong>
        <form action="" method="post">
            
            <!-- Selección fecha de inicio y término de la reserca -->
            <label for="inicio_reserva">Fecha de inicio de la Reserva: </label><br>
            <input type="date" name="ini_date" id="inicio_reserva" required><br>
            <label for="fin_reserva">Fecha de término de la Reserva: </label><br>
            <input type="date" name="end_date" id="fin_reserva" required><br>

            <!-- Selección de las cabañas a reservar -->
            <label for="reserva">Cabañas a reservar: </label><br>
            <select name="reserva_0" id="reserva" required>
                <option value="C1">Cabaña 1</option>
                <option value="C2">Cabaña 2</option>
                <option value="C3">Cabaña 3</option>
                <option value="C4">Cabaña 4</option>
                <option value="C5">Cabaña 5</option>
                <option value="C6">Cabaña 6</option>
                <option value="C7">Cabaña 7</option>
                <option value="C8">Cabaña 8</option>
                <option value="C9">Cabaña 9</option>
                <option value="C10">Cabaña 10</option>
                <option value="C11">Cabaña 11</option>
                <option value="C12">Cabaña 12</option>
                <option value="C13">Cabaña 13</option>
                <option value="C14">Cabaña 14</option>
                <option value="C15">Cabaña 15</option>
                <option value="C16">Cabaña 16</option>
            </select>
            <p>Debería haber una opción para agregar otra cabaña</p>
            <?php

            ?>
            <!-- Datos del cliente -->
            <label for="cliente">Nombre del cliente: </label><br>
            <input type="text" name="cliente" id="cliente" required><br>

        </form>
    </section>
</body>
</html>