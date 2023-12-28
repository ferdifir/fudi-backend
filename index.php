<?php
require_once('database.php');
require_once('AuthController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController($conn);
    
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'register':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $authController->register($username, $password);
                break;
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $authController->login($username, $password);
                break;
            default:
                break;
        }
    }
}
