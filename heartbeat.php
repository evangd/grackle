<?php
session_save_path('sessions');
session_start();

require_once 'pdo.php';

$query = $pdo->prepare('UPDATE users SET last_online = :time WHERE id = :uid');
$query->execute(array(
    ':time' => time()
    ':uid' => $_SESSION['id']
));
