<?php
session_start();

class RegisterController {
    private $model;

    public function __construct($pdo) {
        require_once '/var/www/app/models/User.php'; 
        $this->model = new User($GLOBALS['pdo']);
    }

    public function index() {

        $error = null; 

        if(isset($_SESSION['user_id'])) {
            header("Location: /home");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {

            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $role = $_POST['role'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $user_id = $this->model->getLastInsertedUserId();
            if ($role === 'company') {
                $location = $_POST['location'];
                $about = $_POST['about'];
            }
          
            if (empty($nama) || empty($email) || empty($role) || empty($password) || empty($confirm_password)) {
                $error = "Semua kolom wajib diisi.";
            } elseif ($role === 'company' && (empty($location) || empty($about))) {
                $error = "Silakan lengkapi semua detail perusahaan.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid.";
            } elseif ($password !== $confirm_password) {
                $error = "Password dan konfirmasi password tidak cocok.";
            } elseif ($this->model->emailExists($email)) {
                $error = "Email sudah terdaftar.";            
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                if ($this->model->registerUser($nama, $email, $hashed_password, $role, $lokasi, $about)) {
                    $user_id = $this->model->getLastInsertedUserId();

                    if ($role === 'company') {
                        if (!$this->model->registerCompany($user_id, $location, $about)) {
                            $error = "Gagal untuk menyimpan detail company";
                        }
                    }

                    if (!$error) {
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_name'] = $nama;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_role'] = $role;

                        header("Location: /home"); 
                        exit();
                    }
                }
                $error = "Terdapat sebuah error. Silakan coba lagi.";
            }
        }
        if (isset($error)) {
            echo "<div class='error-message'>$error</div>";
        }

        require_once '/var/www/app/views/pages/auth_register.php'; 
    }
}