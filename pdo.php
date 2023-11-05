<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=evangdem_grackle', 
    'evangdem_admin', 'Eightisgr8!');
// $pdo = new PDO('mysql:host=localhost;port=3306;dbname=grackle', 
//    'evan', 'bruh');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);