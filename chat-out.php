<?php
session_start();
require_once 'pdo.php';

header('Content-Type: application/json; charset=utf-8');

$message = json_decode(file_get_contents('php://input'));

// okay I still need to work on this

$stmt = $pdo->prepare('INSERT INTO messages (message, user_id) 
    VALUES (:message, :user_id)');
$stmt->execute(array(
    ':message' => $message->message,
    ':user_id' => $_SESSION['id']
));