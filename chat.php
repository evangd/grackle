<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

echo json_encode($_SESSION['chat']);