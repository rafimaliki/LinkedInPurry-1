<?php
session_start();

class ProfilController {
    private $model;
    private $user;

    public function __construct($pdo) {
        require_once '/var/www/app/models/User.php';
        require_once '/var/www/app/models/CompanyDetail.php';
        $this->user = new User($pdo);
        $this->company_detail = new CompanyDetail($pdo);
        // $this->lowongan = new Lowongan($pdo);
    }

    public function index() {
        $user = $this->user->getUserById($_SESSION['user_id']);
        $company_detail = $this->company_detail->getCompanyDetailById($_SESSION['user_id']);

        if ($user['role'] === 'jobseeker') {
            $this->jobseeker();
        } elseif ($user['role'] === 'company') {
            $this->company();
        } else {
            echo "Unknown user role.";
            exit();
        }
    }

    private function jobseeker() {
        $user = $this->user->getUserById($_SESSION['user_id']);
        $company_detail = $this->company_detail->getCompanyDetailById($_SESSION['user_id']);

        require_once '/var/www/app/views/pages/profil_jobseeker.php';
    }

    private function company() {
        $user = $this->user->getUserById($_SESSION['user_id']);
        $company_detail = $this->company_detail->getCompanyDetailById($_SESSION['user_id']);

        require_once '/var/www/app/views/pages/profil_company.php';   
    }

   public function update(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nama = $_POST['nama'] ?? '';
            $lokasi = $_POST['lokasi'] ?? '';
            $about = $_POST['about'] ?? '';
            $success = $this->company_detail->updateCompanyDetail($id, $nama, $lokasi, $about);
            echo json_encode(['success' => $_POST]);
        }

        
        // $success = true;

        // $success = $this->CompanyDetail->updateUser(
        //     $id,
        //     $nama,
        //     $lokasi,
        //     $about
        // );
        // if ($success) {
        //     echo json_encode(['succes' => true, 'data' => $_POST]);
        // } else {
        //     echo json_encode(['succes' => false]);
        // }
    }
}