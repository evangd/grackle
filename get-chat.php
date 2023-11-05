<?php
session_save_path('sessions');
session_start();
require_once 'pdo.php';

header('Content-Type: application/json; charset=utf-8');

$stmt = $pdo->prepare('SELECT * FROM messages 
    INNER JOIN users ON messages.user_id = users.id
    WHERE messages.id >' . ' ' . strval($_SESSION['start']));
$stmt->execute();
$return = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($return);