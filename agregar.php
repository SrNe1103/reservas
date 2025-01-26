<?php
require_once "pdo.php";
// restore data for the last submit try
$values = ['ini_date','end_date','reserva','cliente','telefono','n_personas','total','abono','notas','rut','correo'];
for ($i = 0 ; $i < count($values) ; $i++) {
    $item = $values[$i];
    $$item = isset($_POST[$values[$i]]) ? $_POST[$values[$i]] : '';
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir reservas</title>
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
            <table border="1">
                <tr>
                    <td>Inicio:</td>
                    <td><span id="ini_date_p"></span></td>
                </tr>
                <tr>
                    <td>Término: </td>
                    <td><span id="end_date_p"></span></td>
                </tr>
                <tr>
                    <td>Cabañas a reservar:</td>
                    <td><span id="reserva_p"></span></td>
                </tr>
                <tr>
                    <td>Nombre del cliente: </td>
                    <td><span id="cliente_p"></span></td>
                </tr>
                <tr>
                    <td>Número de teléfono:</td>
                    <td><span id="telefono_p"></span></td>
                </tr>
                <tr>
                    <td>Número de personas: </td>
                    <td><span id="n_personas_p"></span></td>
                </tr>
                <tr>
                    <td>Monto total:</td>
                    <td><span id="total_p"></span></td>
                </tr>
                <tr>
                    <td>Abono: </td>
                    <td><span id="abono_p"></span></td>
                </tr>
                <tr>
                    <td>saldo: </td>
                    <td><span id="saldo_p"></span></td>
                </tr>
                <tr>
                    <td>Información adicional: </td>
                    <td><span id="notas_p"></span></td>
                </tr>
            </table>
        </div>

        <h3>Datos del cliente:</h3>

        <form method="post">
            
            <!-- Datos del cliente -->
            <p><label for="cliente">Nombre del cliente: </label><br>
            <input type="text" name="cliente" id="cliente" size="20" value="<?= htmlentities($cliente)?>" required></p>
            <p><label for="telefono">Número de teléfono: </label><br>
            <input type="tel" name="telefono" id="telefono" maxlength="9" size="9" placeholder="912345678" pattern="[0-9]{9}" value="<?= htmlentities($telefono)?>" required></p>
            <p><label for="rut">Rut/pasaporte (opcional): </label><br>
            <input type="text" name="rut" id="rut" placeholder="11222333k"  size="13" maxlength="9" value="<?= htmlentities($rut)?>"></p>
            <p><label for="correo">Correo (opcional): </label><br>
            <input type="email" name="correo" id="correo" size="20" value="<?= htmlentities($correo)?>"></p>

            <input type="submit" id="add_cliente" value="Agregar">

        </form>

        <h3>Datos de la reserva:</h3>

        <form method="post">
            
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
                </select><br>
            <span id="add_section" class="hidden"></span>
            <button type="button" id="add_reserva">Añadir otra cabaña</button>
            </div>
            
            

            <!-- Datos de la reserva -->
            <p><label for="n_personas">Número de personas: </label><br>
            <input type="number" name="n_personas" id="n_personas" min="1" max="60" value="<?= htmlentities($n_personas)?>" required></p>
            <p><label for="total">Monto total: </label><br>
            <input type="number" name="total" id="total" maxlength="8" min="0" max="99999999" pattern="[0-9]{8}" value="<?= htmlentities($total)?>" required></p>
            <p><label for="abono">Abono: </label><br>
            <input type="number" name="abono" id="abono" maxlength="8" min="0" max="99999999" value="<?= htmlentities($abono)?>"></p>
            <p><label for="notas">Información adicional: </label><br>
            <textarea name="notas" id="notas" maxlength="255" cols="40" rows="10"><?= htmlentities($notas)?></textarea>
            </p>
            
            <input type="submit" value="Enviar" id="submit">

        </form>
        
    </section>
    <script src="js/agregar.js"></script>
</body>
</html>