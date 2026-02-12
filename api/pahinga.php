<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../functions.php';

$services = new UserServices();
$users = $services->getAllUsers();

echo json_encode($users, JSON_PRETTY_PRINT);