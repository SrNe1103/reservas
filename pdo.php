<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=lemunantu','vicente','Seagull&Sand');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
?>