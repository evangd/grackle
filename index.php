<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egret</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id="grid-container">
        <textarea></textarea>
    </div>
</body>
</html>