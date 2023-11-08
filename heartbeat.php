<?php
session_save_path('sessions');
session_start();

require_once 'pdo.php';

$prev_beat = $_SESSION['last_beat'];

$_SESSION['last_beat'] = time();

if ($_SESSION['last_beat'] - $prev_beat > 10) {
    $query = $pdo->prepare('UPDATE users SET online = 0 WHERE id = :uid');
    $query->execute(array(
        ':uid' => $_SESSION['id']
    ));
}
