<?php
session_start();
require_once 'pdo.php';

header('Content-Type: application/json; charset=utf-8');

$_SESSION['count'] += 1;

$message = json_decode(file_get_contents('php://input'));

$stmt = $pdo->prepare('INSERT INTO messages (message, user_id, time) 
    VALUES (:message, :user_id, :time)');
$stmt->execute(array(
    ':message' => $message->message,
    ':user_id' => $_SESSION['id'],
    ':time' => $message->time
));

echo true; // Make sure AJAX call gets reponse