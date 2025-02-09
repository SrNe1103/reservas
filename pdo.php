<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=lemunantu','vicente','Seagull&8');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
?>