<?php

define("PASSWORD_SALT", '$6$rounds=1000000$NJy4rIPjpOaU$0ACEYGg/aKCY3v8O8AfyiO7CTfZQ8/W231Qfh2tRLmfdvFD6XfHk12u6hMr9cYIA4hnpjLNSTRtUwYr9km9Ij/');

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $password, $name, $phone, $email)
    {
        if ($this->isUsernameUnique($username) && $this->isEmailUnique($email) && $this->isPhoneUnique($phone)) {
            $hashed_password = crypt($password, PASSWORD_SALT);
            $sql = "INSERT INTO users (username, password, name, phone, email) VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssss", $username, $hashed_password, $name, $phone, $email);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function isUsernameUnique($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }

    private function isEmailUnique($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }

    private function isPhoneUnique($phone)
    {
        $sql = "SELECT * FROM users WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }


    public function login($username, $password)
    {
        $hashed_password = crypt($password, PASSWORD_SALT);
        $sql = "SELECT * FROM users WHERE username=? AND password=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $token = $this->getToken($row['id']);
            if (password_verify($password, $row['password'])) {
                $token = array("token" => $token);
                $row = array_merge($row, $token);
                return $row;
            }
        }
        return null;
    }

    private function getToken($id)
    {
        $uuid = $this->format_uuidv4(random_bytes(16));
        $sql = "INSERT INTO token (uuid, uid) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $uuid, $id);
        $stmt->execute();
        return $uuid;
    }

    private function format_uuidv4($data)
    {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function logout($token)
    {
        $sql = "DELETE FROM token WHERE uuid = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function logoutAllDevice($uid)
    {
        $sql = "DELETE FROM token WHERE uid = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $uid);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($token, $username, $password, $name, $phone, $email)
    {
        $sql = "SELECT uid FROM token WHERE uuid=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            $hashed_password = crypt($password, PASSWORD_SALT);
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $sql = "UPDATE users SET username=?, password=?, name=?, email=?, phone=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssi", $username, $hashed_password, $name, $email, $phone, $row['id']);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
