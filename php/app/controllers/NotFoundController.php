<?php
session_start();


class NotFoundController {
    public function index() {
        ob_start();
        
        require_once '/var/www/app/views/pages/404.php';
        
        ob_end_flush();
    }
}
