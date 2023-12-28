<?php

define("PASSWORD_SALT", '$6$rounds=1000000$NJy4rIPjpOaU$0ACEYGg/aKCY3v8O8AfyiO7CTfZQ8/W231Qfh2tRLmfdvFD6XfHk12u6hMr9cYIA4hnpjLNSTRtUwYr9km9Ij/');

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password) : bool {
        $hashed_password = crypt($password, PASSWORD_SALT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            return true; 
        } else {
            return false; 
        }
    }

    public function login($username, $password) {
        $hashed_password = crypt($password, PASSWORD_SALT);
        $sql = "SELECT * FROM users WHERE username=? AND password=?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                return $row; 
            }
        }
        return null; 
    }
}
