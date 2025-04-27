<?php
session_start();


class AboutController {
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        require_once '/var/www/app/views/pages/about.php';
    }
}
