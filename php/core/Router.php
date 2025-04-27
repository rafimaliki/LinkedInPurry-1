<?php

require_once '/var/www/app/database/database.php'; 

class Router {
    private static $routes = [
        'login' => 'LoginController@index',
        'register' => 'RegisterController@index',
        'logout' => 'LogoutController@index',

        'home' => 'HomeController@index',
        'profil' => 'ProfilController@index',
        'profil/update' => 'ProfilController@update',
        'riwayat' => 'RiwayatController@index',
        
        'lowongan/detail' => 'LowonganDetailController@index',
        'lowongan/tambah' => 'LowonganTambahController@index',
        'lowongan/tambah/submit' => 'LowonganTambahController@submit',
        'lowongan/edit' => 'LowonganEditController@index',
        'lowongan/edit/submit' => 'LowonganEditController@submit',
        'lowongan/toggle' => 'LowonganDetailController@toggle',
        'lowongan/export' => 'LowonganDetailController@export',
        'lowongan/delete' => 'LowonganDetailController@delete',

        'lamaran/buat' => 'LamaranController@buat',
        'lamaran/submit' => 'LamaranController@submit',
        'lamaran/detail' => 'LamaranDetailController@index',
        'lamaran/update' => 'LamaranDetailController@update',
        
        'lamaran/approve' => 'DetailLamaranController@handleApproval',
    ];

    private static $pdo;
    private static $initialized = false; 
    private static $route;
    private static $queryParams;

    public static function init() {
        if (!self::$initialized) { 
            self::$pdo = $GLOBALS['pdo'];  
            self::$initialized = true; 
        }
    }

    public static function parseUrl() {
        self::$route = isset($_GET['url']) ? $_GET['url'] : '';

        self::$queryParams = isset($_GET) ? $_GET : [];
        unset(self::$queryParams['url']);    
    }

    public static function route() { 
        self::parseUrl(); 

        if (self::$route === '') {
            header("Location: /home"); 
            exit(); 
        }

        if (isset(self::$routes[self::$route])) {
            list($controller, $action) = explode('@', self::$routes[self::$route]);
            self::callAction($controller, $action, self::$queryParams); 
        } else {
            self::callAction('NotFoundController', 'index');
        }
    }

    private static function callAction($controller, $action, $queryParams = []) { 

        $found = false;
        $controllerFile = "/var/www/app/controllers/$controller.php";

        if (file_exists($controllerFile)) {

            require_once $controllerFile;
            $controllerInstance = new $controller(self::$pdo, $queryParams); 

            if (method_exists($controllerInstance, $action)) {
                $found = true;
                $controllerInstance->$action();
            } 
        } 

        if (!$found) {
            self::callAction('NotFoundController', 'index');
        }
    }
}