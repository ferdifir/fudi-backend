<?php
require_once("User.php");

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function register($username, $password) {
        $registrationResult = $this->userModel->register($username, $password);

        if ($registrationResult) {
            $response = array("message" => "Registrasi berhasil");
            echo json_encode($response);
        } else {
            $response = array("message" => "Registrasi gagal");
            echo json_encode($response);
        }
    }

    public function login($username, $password) {
        $user = $this->userModel->login($username, $password);
        
        if ($user) {
            $response = array("message" => "Login berhasil", "user" => $user);
            echo json_encode($response);
        } else {
            $response = array("message" => "Login gagal");
            echo json_encode($response);
        }
    }
}
