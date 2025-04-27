<?php
session_start();

class LoginController {
    private $model;

    public function __construct($pdo) {
        require_once '/var/www/app/models/User.php';
        $this->model = new User($pdo);
    }

    public function index() {
        $error = null; 

        if(isset($_SESSION['user_id'])) {
            header("Location: /home");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                $error = "Email dan password wajib diisi.";
            } 
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid.";
            } else {
                
                $user = $this->model->getUserByEmail($email);
                
                $password = password_verify($password, $user['password']);
                if ($user) {
                    if ($password == $user['password']) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['user_name'] = $user['nama'];
                        header("Location: /home"); 
                        exit();
                    } else {
                        $error = "Password salah";
                    }
                } else {
                    $error = "Email belum terdaftar";
                }
            }
        }
        if (isset($error)) {
            echo "<div class='error-message'>$error</div>";
        }

        require_once '/var/www/app/views/pages/auth_login.php';
    }
}