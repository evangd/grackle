<?php
session_save_path('sessions');
session_start();

require_once 'pdo.php';

$stmt = $pdo->prepare('UPDATE users SET online = 0 WHERE id = :uid');
$stmt->execute(array(
    ':uid' => $_SESSION['id']
));

session_destroy();
header('Location: index.php');