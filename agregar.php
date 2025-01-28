<?php

require_once "pdo.php";
// restore data for the last submit try
$values = ['ini_date','end_date','reserva','cliente','telefono','n_personas','total','abono','notas','rut','correo'];
for ($i = 0 ; $i < count($values) ; $i++) {
    $item = $values[$i];
    $$item = isset($_POST[$values[$i]]) ? $_POST[$values[$i]] : '';
}

// check for new client
$check_cliente = false;
$datos_anteriores = ['nombre' => false,
    'telefono' => false,
    'rut' => false,
    'correo' => false,
    'nacionalidad' => false];
$actualizar_cliente = false;
$datos_antes = false;
$datos_anteriores = false;
$datos_nuevos = false;
$cliente_id = false;

if ((isset($_POST['Agregar']) || isset($_POST['Actualizar'])) && isset($_POST['cliente']) && isset($_POST['telefono'])){
    $check_cliente = $pdo->prepare("SELECT * FROM clientes WHERE nombre = :nombre");
    $check_cliente->execute(array(':nombre' => $_POST['cliente']));
    
    $datos_anteriores = $check_cliente->fetch(PDO::FETCH_ASSOC); // Verdadero si existe cliente

    if (isset($_POST['Actualizar'])){ //si se selecciona actualizar
        $cliente_id = $datos_anteriores['cliente_id']; //Recoger id de datos anteriores
        $sql = "UPDATE clientes SET telefono = :telefono WHERE cliente_id = :cliente_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':telefono' => $_POST['telefono'],
            ':cliente_id' => $cliente_id 
        ));
        // si hay rut, añadir rut
        if (isset($_POST['rut'])) {
            $sql = "UPDATE clientes SET rut = :rut WHERE cliente_id = :cliente_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':rut' => $_POST['rut'],
                ':cliente_id' => $cliente_id
                ));
        }
        // si hay correo, añadir correo
        if (isset($_POST['correo'])) {
            $sql = "UPDATE clientes SET correo = :correo WHERE cliente_id = :cliente_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':correo' => $_POST['correo'],
                ':cliente_id' => $cliente_id
                ));
        }
        // si hay nacionalidad, añadir nacionalidad
        if (isset($_POST['nacionalidad'])) {
            $sql = "UPDATE clientes SET nacionalidad = :nacionalidad WHERE cliente_id = :cliente_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':nacionalidad' => $_POST['nacionalidad'],
                ':cliente_id' => $cliente_id
                ));
        }
        $check_cliente = "El cliente existe y sus datos fueron actualizados exitosamente, se usará para esta reserva";
        
    } elseif ($datos_anteriores){ //si ya existe un cliente
        $datos_nuevos=[$_POST['telefono'],$_POST['telefono']];
        if ($datos_anteriores)
        $check_cliente = "El cliente existe ¿desea actualizarlo?";
        
        $actualizar_cliente = '<input type="submit" id="update_cliente" name="Actualizar" value="Actualizar">'; // agregar botón para actualizar
        
        // Mostrar datos anteriores y nuevos para poder corregir si hay errores
        $datos_antes = "\n-Datos Anteriores: Teléfono -> ".htmlentities($datos_anteriores['telefono']).
            "\nRut -> ".htmlentities($datos_anteriores['rut']).
            "\nCorreo -> ".htmlentities($datos_anteriores['correo']).
            "\nNacionalidad -> ".htmlentities($datos_anteriores['nacionalidad']);

        $datos_nuevos = "\n\n-Datos Nuevos: Teléfono -> ".htmlentities($_POST['telefono']).
            "\nRut -> ".htmlentities($_POST['rut']).
            "\nCorreo -> ".htmlentities($_POST['correo']).
            "\nNacionalidad -> ".htmlentities($_POST['nacionalidad']);

    } else { // si no existe el cliente, agregarlo
        $sql = "INSERT INTO clientes (nombre, telefono) VALUES (:nombre, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':nombre' => $_POST['cliente'],
            ':telefono' => $_POST['telefono']
        ));
        $check_cliente = $pdo->prepare("SELECT * FROM clientes WHERE nombre = :nombre");
        $check_cliente->execute(array(':nombre' => $_POST['cliente']));

        if ($row = $check_cliente->fetch(PDO::FETCH_ASSOC)){ //si se creó exitosamente
            $cliente_id = $row['cliente_id']; //guardar id de cliente
            // si hay rut, añadir rut
            if (isset($_POST['rut'])) {
                $sql = "UPDATE clientes SET rut = :rut WHERE cliente_id = :cliente_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':rut' => $_POST['rut'],
                    ':cliente_id' => $cliente_id
                    ));
            }
            // si hay correo, añadir correo
            if (isset($_POST['correo'])) {
                $sql = "UPDATE clientes SET correo = :correo WHERE cliente_id = :cliente_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':correo' => $_POST['correo'],
                    ':cliente_id' => $cliente_id
                    ));
            }
            // si hay nacionalidad, añadir nacionalidad
            if (isset($_POST['nacionalidad'])) {
                $sql = "UPDATE clientes SET nacionalidad = :nacionalidad WHERE cliente_id = :cliente_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':nacionalidad' => $_POST['nacionalidad'],
                    ':cliente_id' => $cliente_id
                    ));
            }

            $check_cliente = "Cliente añadido exitosamente\n";
        } else {
            $cliente_id = false;
            echo "ERROR IN CLIENT INSERTION";
        }
    
    }    
}



// Añadir nueva reserva

$check_reserva = false;
$reserva_duplicada = [false];
$cabs = [false];


if (isset($_POST['ini_date']) && isset($_POST['end_date']) && isset($_POST['reserva']) && isset($_POST['n_personas']) && isset($_POST['total']) && isset($_POST['abono'])) {
    $check_reserva = $pdo->prepare("SELECT * FROM reservas WHERE inicio = :ini_date AND final = :end_date AND cliente_id = :cliente_id AND n_personas = :n_personas");
    $check_reserva->execute(array(
        ':ini_date' => $_POST['ini_date'],
        ':end_date' => $_POST['end_date'],
        ':cliente_id' => $cliente_id,
        ':n_personas' => $_POST['n_personas']
        ));

    if ($row = $check_reserva->fetch(PDO::FETCH_ASSOC)){ //si existe la misma reserva

        $reserva_id = $row['reserva_id'];
        $reserva_duplicada = $row;
        $check_reserva = $pdo->prepare("SELECT cab_id FROM asign WHERE reserva_id = :reserva_id");
        $check_reserva->execute(array(':reserva_id' => $reserva_id));
        $cliente_asociado = $pdo->prepare("SELECT nombre FROM cliente WHERE cliente_id = :cliente_id");
        $cliente_asociado->execute(array(':cliente_id' =>  $cliente_id));
        $cliente_asociado = $cliente_asociado->fetch(PDO::FETCH_ASSOC);
        $cabs = $check_reserva->fetch(PDO::FETCH_ASSOC);
        $reserva_duplicada[] = $cliente_asociado['nombre'];
        $check_reserva = 'Esta reserva se encuentra duplicada, vaya a <a href="modificar.php">Modificar reservas</a>';

    } else{
        $check_reserva = "";
    }
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
            <p><label for="nacionalidad">Nacionalidad :</label>
            <select name="nacionalidad" id="nacionalidad">
                <option value="AF">Afghanistan</option>
                <option value="AX">�land Islands</option>
                <option value="AL">Albania</option>
                <option value="DZ">Algeria</option>
                <option value="AS">American Samoa</option>
                <option value="AD">Andorra</option>
                <option value="AO">Angola</option>
                <option value="AI">Anguilla</option>
                <option value="AQ">Antarctica</option>
                <option value="AG">Antigua and Barbuda</option>
                <option value="AR">Argentina</option>
                <option value="AM">Armenia</option>
                <option value="AW">Aruba</option>
                <option value="AU">Australia</option>
                <option value="AT">Austria</option>
                <option value="AZ">Azerbaijan</option>
                <option value="BS">Bahamas</option>
                <option value="BH">Bahrain</option>
                <option value="BD">Bangladesh</option>
                <option value="BB">Barbados</option>
                <option value="BY">Belarus</option>
                <option value="BE">Belgium</option>
                <option value="BZ">Belize</option>
                <option value="BJ">Benin</option>
                <option value="BM">Bermuda</option>
                <option value="BT">Bhutan</option>
                <option value="BO">Bolivia, Plurinational State of</option>
                <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                <option value="BA">Bosnia and Herzegovina</option>
                <option value="BW">Botswana</option>
                <option value="BV">Bouvet Island</option>
                <option value="BR">Brazil</option>
                <option value="IO">British Indian Ocean Territory</option>
                <option value="BN">Brunei Darussalam</option>
                <option value="BG">Bulgaria</option>
                <option value="BF">Burkina Faso</option>
                <option value="BI">Burundi</option>
                <option value="KH">Cambodia</option>
                <option value="CM">Cameroon</option>
                <option value="CA">Canada</option>
                <option value="CV">Cape Verde</option>
                <option value="KY">Cayman Islands</option>
                <option value="CF">Central African Republic</option>
                <option value="TD">Chad</option>
                <option value="CL" selected>Chile</option>
                <option value="CN">China</option>
                <option value="CX">Christmas Island</option>
                <option value="CC">Cocos (Keeling) Islands</option>
                <option value="CO">Colombia</option>
                <option value="KM">Comoros</option>
                <option value="CG">Congo</option>
                <option value="CD">Congo, the Democratic Republic of the</option>
                <option value="CK">Cook Islands</option>
                <option value="CR">Costa Rica</option>
                <option value="CI">C�te d'Ivoire</option>
                <option value="HR">Croatia</option>
                <option value="CU">Cuba</option>
                <option value="CW">Cura�ao</option>
                <option value="CY">Cyprus</option>
                <option value="CZ">Czech Republic</option>
                <option value="DK">Denmark</option>
                <option value="DJ">Djibouti</option>
                <option value="DM">Dominica</option>
                <option value="DO">Dominican Republic</option>
                <option value="EC">Ecuador</option>
                <option value="EG">Egypt</option>
                <option value="SV">El Salvador</option>
                <option value="GQ">Equatorial Guinea</option>
                <option value="ER">Eritrea</option>
                <option value="EE">Estonia</option>
                <option value="ET">Ethiopia</option>
                <option value="FK">Falkland Islands (Malvinas)</option>
                <option value="FO">Faroe Islands</option>
                <option value="FJ">Fiji</option>
                <option value="FI">Finland</option>
                <option value="FR">France</option>
                <option value="GF">French Guiana</option>
                <option value="PF">French Polynesia</option>
                <option value="TF">French Southern Territories</option>
                <option value="GA">Gabon</option>
                <option value="GM">Gambia</option>
                <option value="GE">Georgia</option>
                <option value="DE">Germany</option>
                <option value="GH">Ghana</option>
                <option value="GI">Gibraltar</option>
                <option value="GR">Greece</option>
                <option value="GL">Greenland</option>
                <option value="GD">Grenada</option>
                <option value="GP">Guadeloupe</option>
                <option value="GU">Guam</option>
                <option value="GT">Guatemala</option>
                <option value="GG">Guernsey</option>
                <option value="GN">Guinea</option>
                <option value="GW">Guinea-Bissau</option>
                <option value="GY">Guyana</option>
                <option value="HT">Haiti</option>
                <option value="HM">Heard Island and McDonald Islands</option>
                <option value="VA">Holy See (Vatican City State)</option>
                <option value="HN">Honduras</option>
                <option value="HK">Hong Kong</option>
                <option value="HU">Hungary</option>
                <option value="IS">Iceland</option>
                <option value="IN">India</option>
                <option value="ID">Indonesia</option>
                <option value="IR">Iran, Islamic Republic of</option>
                <option value="IQ">Iraq</option>
                <option value="IE">Ireland</option>
                <option value="IM">Isle of Man</option>
                <option value="IL">Israel</option>
                <option value="IT">Italy</option>
                <option value="JM">Jamaica</option>
                <option value="JP">Japan</option>
                <option value="JE">Jersey</option>
                <option value="JO">Jordan</option>
                <option value="KZ">Kazakhstan</option>
                <option value="KE">Kenya</option>
                <option value="KI">Kiribati</option>
                <option value="KP">Korea, Democratic People's Republic of</option>
                <option value="KR">Korea, Republic of</option>
                <option value="KW">Kuwait</option>
                <option value="KG">Kyrgyzstan</option>
                <option value="LA">Lao People's Democratic Republic</option>
                <option value="LV">Latvia</option>
                <option value="LB">Lebanon</option>
                <option value="LS">Lesotho</option>
                <option value="LR">Liberia</option>
                <option value="LY">Libya</option>
                <option value="LI">Liechtenstein</option>
                <option value="LT">Lithuania</option>
                <option value="LU">Luxembourg</option>
                <option value="MO">Macao</option>
                <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                <option value="MG">Madagascar</option>
                <option value="MW">Malawi</option>
                <option value="MY">Malaysia</option>
                <option value="MV">Maldives</option>
                <option value="ML">Mali</option>
                <option value="MT">Malta</option>
                <option value="MH">Marshall Islands</option>
                <option value="MQ">Martinique</option>
                <option value="MR">Mauritania</option>
                <option value="MU">Mauritius</option>
                <option value="YT">Mayotte</option>
                <option value="MX">Mexico</option>
                <option value="FM">Micronesia, Federated States of</option>
                <option value="MD">Moldova, Republic of</option>
                <option value="MC">Monaco</option>
                <option value="MN">Mongolia</option>
                <option value="ME">Montenegro</option>
                <option value="MS">Montserrat</option>
                <option value="MA">Morocco</option>
                <option value="MZ">Mozambique</option>
                <option value="MM">Myanmar</option>
                <option value="NA">Namibia</option>
                <option value="NR">Nauru</option>
                <option value="NP">Nepal</option>
                <option value="NL">Netherlands</option>
                <option value="NC">New Caledonia</option>
                <option value="NZ">New Zealand</option>
                <option value="NI">Nicaragua</option>
                <option value="NE">Niger</option>
                <option value="NG">Nigeria</option>
                <option value="NU">Niue</option>
                <option value="NF">Norfolk Island</option>
                <option value="MP">Northern Mariana Islands</option>
                <option value="NO">Norway</option>
                <option value="OM">Oman</option>
                <option value="PK">Pakistan</option>
                <option value="PW">Palau</option>
                <option value="PS">Palestinian Territory, Occupied</option>
                <option value="PA">Panama</option>
                <option value="PG">Papua New Guinea</option>
                <option value="PY">Paraguay</option>
                <option value="PE">Peru</option>
                <option value="PH">Philippines</option>
                <option value="PN">Pitcairn</option>
                <option value="PL">Poland</option>
                <option value="PT">Portugal</option>
                <option value="PR">Puerto Rico</option>
                <option value="QA">Qatar</option>
                <option value="RE">R�union</option>
                <option value="RO">Romania</option>
                <option value="RU">Russian Federation</option>
                <option value="RW">Rwanda</option>
                <option value="BL">Saint Barth�lemy</option>
                <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                <option value="KN">Saint Kitts and Nevis</option>
                <option value="LC">Saint Lucia</option>
                <option value="MF">Saint Martin (French part)</option>
                <option value="PM">Saint Pierre and Miquelon</option>
                <option value="VC">Saint Vincent and the Grenadines</option>
                <option value="WS">Samoa</option>
                <option value="SM">San Marino</option>
                <option value="ST">Sao Tome and Principe</option>
                <option value="SA">Saudi Arabia</option>
                <option value="SN">Senegal</option>
                <option value="RS">Serbia</option>
                <option value="SC">Seychelles</option>
                <option value="SL">Sierra Leone</option>
                <option value="SG">Singapore</option>
                <option value="SX">Sint Maarten (Dutch part)</option>
                <option value="SK">Slovakia</option>
                <option value="SI">Slovenia</option>
                <option value="SB">Solomon Islands</option>
                <option value="SO">Somalia</option>
                <option value="ZA">South Africa</option>
                <option value="GS">South Georgia and the South Sandwich Islands</option>
                <option value="SS">South Sudan</option>
                <option value="ES">Spain</option>
                <option value="LK">Sri Lanka</option>
                <option value="SD">Sudan</option>
                <option value="SR">Suriname</option>
                <option value="SJ">Svalbard and Jan Mayen</option>
                <option value="SZ">Swaziland</option>
                <option value="SE">Sweden</option>
                <option value="CH">Switzerland</option>
                <option value="SY">Syrian Arab Republic</option>
                <option value="TW">Taiwan, Province of China</option>
                <option value="TJ">Tajikistan</option>
                <option value="TZ">Tanzania, United Republic of</option>
                <option value="TH">Thailand</option>
                <option value="TL">Timor-Leste</option>
                <option value="TG">Togo</option>
                <option value="TK">Tokelau</option>
                <option value="TO">Tonga</option>
                <option value="TT">Trinidad and Tobago</option>
                <option value="TN">Tunisia</option>
                <option value="TR">Turkey</option>
                <option value="TM">Turkmenistan</option>
                <option value="TC">Turks and Caicos Islands</option>
                <option value="TV">Tuvalu</option>
                <option value="UG">Uganda</option>
                <option value="UA">Ukraine</option>
                <option value="AE">United Arab Emirates</option>
                <option value="GB">United Kingdom</option>
                <option value="US">United States</option>
                <option value="UM">United States Minor Outlying Islands</option>
                <option value="UY">Uruguay</option>
                <option value="UZ">Uzbekistan</option>
                <option value="VU">Vanuatu</option>
                <option value="VE">Venezuela, Bolivarian Republic of</option>
                <option value="VN">Viet Nam</option>
                <option value="VG">Virgin Islands, British</option>
                <option value="VI">Virgin Islands, U.S.</option>
                <option value="WF">Wallis and Futuna</option>
                <option value="EH">Western Sahara</option>
                <option value="YE">Yemen</option>
                <option value="ZM">Zambia</option>
                <option value="ZW">Zimbabwe</option>

            </select></p>

            <!-- Si hay datos anteriores, mostrarlos -->
            <span><?=htmlentities($check_cliente)?><br>
            <?= $actualizar_cliente ?>
            <input type="submit" id="add_cliente" name="Agregar" value="Agregar"></span>
            <pre><?= 
            htmlentities($datos_antes) .htmlentities($datos_nuevos)
            ?></pre>
            

        </form>

        <h3>Datos de la reserva:</h3>

        <form method="post">
            <!-- input oculto con los datos del cliente -->
            <input type="hidden" name="cliente_id" value=<?=$cliente_id?>>

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
            <span><?=$check_reserva?></span>
            <input type="submit" value="Enviar" id="submit">

        </form>
        <table border="1">
            <tr>
                <td>Inicio:</td>
                <td><span><?= htmlentities($reserva_duplicada['inicio']) ?></span></td>
            </tr>
            <tr>
                <td>Término: </td>
                <td><span><?= htmlentities($reserva_duplicada['final']) ?></span></td>
            </tr>
            <tr>
                <td>Cabañas a reservar:</td>
                <td><span><?php if (l) {foreach ($cabs as $cab){echo $cab.", ";}} ?></span></td>
            </tr>
            <tr>
                <td>Nombre del cliente: </td>
                <td><span><?= htmlentities($reserva_duplicada['']) ?></span></td>
            </tr>
            <tr>
                <td>Número de teléfono:</td>
                <td><span><?= htmlentities($reserva_duplicada['final']) ?></span></td>
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
    </section>
    <script src="js/agregar.js"></script>
</body>
</html>