<?php
require_once "pdo.php";

    $values = ['ini_date','end_date','reserva','cliente','telefono','n_personas','total','abono','notas'];
    for ($i = 0 ; $i < 9 ; $i++) {
        $item = $values[$i];
        $$item = isset($_POST[$values[$i]]) ? $_POST[$values[$i]] : '';
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/agregar.css">
</head>
<body>

    <h1>Administración Reservas Ecoturismo Lemunantu</h1>
    
    <?php require_once 'nav.php'; ?>
    
    <p>A continuación puedes ver el formulario para agregar una reserva.</p>
    
    <section id="agregar">
    
        <h2>Agregar Reserva</h2>
        <div id="preview">
            <h3>Vista previa</h3>
            <p>Inicio: <span id="ini_date_p"></span></p>
            <p>Término: <span id="end_date_p"></span></p>
            <p>Cabañas a reservar: <span id="reserva_p"></span></p>
            <p>Nombre del cliente: <span id="cliente_p"></span></p>
            <p>Número de teléfono: <span id="telefono_p"></span></p>
            <p>Número de personas: <span id="n_personas_p"></span></p>
            <p>Monto total: <span id="total_p"></span></p>
            <p>Abono: <span id="abono_p"></span></p>
            <p>Saldo: <span id="saldo_p"></span></p>
            <p>Información adicional: <span id="notas_p"></span></p>
        </div>
        
        <form action="" method="post">
            
            <!-- Selección fecha de inicio y término de la reserca -->
            <p><label for="ini_date">Fecha de inicio de la Reserva: </label><br>
            <input type="date" name="ini_date" id="ini_date" value="<?= htmlentities($ini_date)?>" required></p>
            <p><label for="end_date">Fecha de término de la Reserva: </label><br>
            <input type="date" name="end_date" id="end_date" value="<?= htmlentities($end_date)?>" required></p>

            <!-- Selección de las cabañas a reservar -->
            <div><label for="reserva">Cabañas a reservar: </label><br>
                <select name="reserva" id="reserva" value="<?= htmlentities($reserva)?>" required>
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
                    <option value="S">Salón</option>
                </select>
            <div id="add_section" class="hidden"></div></div>
            <button type="button" id="add_reserva">Añadir otra cabaña</button>
            
            <!-- Datos del cliente -->
            
            <p><label for="cliente">Nombre del cliente: </label><br>
            <input type="text" name="cliente" id="cliente" size="25" value="<?= htmlentities($cliente)?>" required></p>
            <p><label for="telefono">Número de teléfono: </label><br>
            <input type="tel" name="telefono" id="telefono" maxlength="9" size="9" placeholder="912345678" pattern="[0-9]{9}" value="<?= htmlentities($telefono)?>" required></p>
            <!-- Datos de la reserva -->
            <p><label for="n_personas">Número de personas: </label><br>
            <input type="number" name="n_personas" id="n_personas" min="1" max="60" value="<?= htmlentities($n_personas)?>" required></p>
            <p><label for="total">Monto total: </label><br>
            <input type="number" name="total" id="total" maxlength="8" min="0" max="99999999" pattern="[0-9]{8}" value="<?= htmlentities($total)?>" required></p>
            <p><label for="abono">Abono: </label><br>
            <input type="number" name="abono" id="abono" maxlength="8" min="0" max="99999999" value="<?= htmlentities($abono)?>"></p>
            <p><label for="notas">Información adicional: </label><br>
            <textarea name="notas" id="notas" maxlength="255" cols="50" rows="10"><?= htmlentities($notas)?></textarea>
            </p>
            
            <input type="submit" value="Enviar" id="submit">

        </form>
    </section>
    <script src="js/agregar.js"></script>
</body>
</html>