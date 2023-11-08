<?php
session_start();

$stmt = $pdo->prepare('UPDATE users SET online = 0 WHERE id = :uid');
$stmt->execute(array(
    ':uid' => $_SESSION['id'];
));

session_destroy();
header('Location: index.php');