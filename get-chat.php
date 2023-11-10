<?php
session_save_path('sessions');
session_start();
require_once 'pdo.php';

header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->prepare('SELECT * FROM messages 
    INNER JOIN users ON messages.user_id = users.id
    WHERE messages.id >' . ' ' . strval($_SESSION['start']) . ' ' . 
    'ORDER BY messages.id ASC');
$stmt->execute();
$msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currUsers = $pdo->prepare('SELECT first_name, last_name 
    FROM users WHERE online > :time AND id <> :uid');
$currUsers->execute(array(
    ':time' => time() - 5
    ':uid' => $_SESSION['id']
));
$users = $currUsers->fetchAll(PDO::FETCH_ASSOC);

$return = array_merge($users, $msgs);

array_unshift($return, sizeof($users));

echo json_encode($return);