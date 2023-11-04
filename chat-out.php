<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$message = json_decode($_POST['data']);

// okay I still need to work on this

$stmt = $pdo->prepare('INSERT INTO messages (message, user_id) 
    VALUES (:message, :user_id)');
$stmt->execute(array(
    ':message' => $_POST['message'],
    ':user_id' => $_SESSION['id']
));