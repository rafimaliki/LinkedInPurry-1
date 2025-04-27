<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registerUser($nama, $email, $hashed_password, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO user (email, password, role, nama) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$email, $hashed_password, $role, $nama]);
    }

    public function registerCompany($user_id, $location, $about) {
        $stmt = $this->pdo->prepare("INSERT INTO company_detail (user_id, lokasi, about) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $location, $about]);
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT user_id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserNameEmailById($id) {
        $stmt = $this->pdo->prepare("SELECT nama, email FROM user WHERE user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserIdByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT user_id FROM user WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastInsertedUserId() {
        $stmt = $this->pdo->query("SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1");
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? $result['user_id'] : null;
    }

    // public function hashAllPasswords() {
    //     $stmt = $this->pdo->prepare("SELECT user_id, password FROM user");
    //     $stmt->execute();
    //     $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     foreach ($users as $user) {
    //         $hashed_password = password_hash($user['password'], PASSWORD_BCRYPT);
    //         $stmt = $this->pdo->prepare("UPDATE user SET password = :password WHERE user_id = :id");
    //         $stmt->execute(['password' => $hashed_password, 'id' => $user['user_id']]);
    //     }
    // }
}