<?php
require_once("User.php");

class AuthController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function register($username, $password, $name, $phone, $email)
    {
        $registrationResult = $this->userModel->register($username, $password, $name, $phone, $email);

        if ($registrationResult) {
            $response = array("message" => "Registrasi berhasil");
            echo json_encode($response);
        } else {
            $response = array("message" => "Registrasi gagal");
            echo json_encode($response);
        }
    }

    public function login($username, $password)
    {
        $user = $this->userModel->login($username, $password);

        if ($user) {
            $selectedUser = array(
                "username" => $user["username"],
                "name" => $user["name"],
                "email" => $user["email"],
                "phone" => $user["phone"],
                "token" => $user["token"]
            );
            $response = array("message" => "Login berhasil", "data" => $selectedUser);
            echo json_encode($response);
        } else {
            $response = array("message" => "Login gagal");
            echo json_encode($response);
        }
    }

    public function logout($token)
    {
        $logoutResult = $this->userModel->logout($token);
        if ($logoutResult) {
            $response = array("message" => "Logout berhasil");
            echo json_encode($response);
        } else {
            $response = array("message" => "Logout gagal");
            echo json_encode($response);
        }
    }

    public function logoutAllDevice($uid)
    {
        $logoutResult = $this->userModel->logoutAllDevice($uid);
        if ($logoutResult) {
            $response = array("message" => "Logout berhasil");
            echo json_encode($response);
        } else {
            $response = array("message" => "Logout gagal");
            echo json_encode($response);
        }
    }

    public function edit($token, $username, $password, $name, $phone, $email)
    {
        $editResult = $this->userModel->edit($token, $username, $password, $name, $phone, $email);
        if ($editResult) {
            $selectedUser = array(
                "username" => $username,
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "token" => $token
            );
            $response = array(
                "message" => "Edit Profile berhasil berhasil",
                "data" => $selectedUser
            );
            echo json_encode($response);
        } else {
            $response = array("message" => "Edit Profile berhasil gagal");
            echo json_encode($response);
        }
    }
}
