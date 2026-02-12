<?php
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    
    if (!empty($username)) {
        $services = new UserServices();
        if ($services->addUser($username)) {
            header("Location: ../index.php?status=success");
            exit;
        } else {
            header("Location: ../index.php?status=error");
            exit;
        }
    }
}
header("Location: ../index.php");