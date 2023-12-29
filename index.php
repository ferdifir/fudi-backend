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
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $authController->register($username, $password, $name, $phone, $email);
                break;
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                $authController->login($username, $password);
                break;
            case 'logout':
                $token = $_POST['token'];
                $authController->logout($token);
                break;
            case 'logoutall':
                $id = $_POST['id'];
                $authController->logoutAllDevice($id);
                break;
            default:
                break;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $authController = new AuthController($conn);

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'edit':
                $token = $_POST['token'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $authController->edit($token, $username, $password, $name, $phone, $email);
                break;
            default:
                break;
        }
    }
}