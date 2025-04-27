<?php
session_start();


class LamaranDetailController {

    public function __construct($pdo) {

        require_once '/var/www/app/models/User.php';
        require_once '/var/www/app/models/Lowongan.php';
        require_once '/var/www/app/models/AttachmentLowongan.php';
        require_once '/var/www/app/models/CompanyDetail.php';
        require_once '/var/www/app/models/Lamaran.php';

        $this->models = [
            'user' => new User($pdo),
            'lowongan' => new Lowongan($pdo),
            'attachmentLowongan' => new AttachmentLowongan($pdo),
            'companyDetail' => new CompanyDetail($pdo),
            'lamaran' => new Lamaran($pdo)
        ];
    }
    public function index() {

        $lamaran_id = $_GET['id'];
        
        $lamaran = $this->models['lamaran']->getLamaranById($lamaran_id);
        $pelamar = $this->models['user']->getUserNameEmailById($lamaran['user_id']);
        $lowongan = $this->models['lowongan']->getLowonganById($lamaran['lowongan_id']);

        $json_data = json_encode($pelamar);
        


        require_once '/var/www/app/views/pages/lamaran_detail.php';
    }

    public function update(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = true;

            $lamaran_id = $_POST['lamaran_id'];
            $status = $_POST['status'];
            $status_reason = $_POST['status_reason'];

            $lamaran = $this->models['lamaran']->getLamaranById($lamaran_id);
            $lowongan_id = $lamaran['lowongan_id'];
            
            $success = $this->models['lamaran']->updateLamaran(
                $lamaran_id,
                $status,
                $status_reason
            );
            if ($success) {
                echo json_encode(['succes' => true, 'data' => $_POST, 'lowongan_id' => $lowongan_id]);
            } else {
                echo json_encode(['succes' => false]);
            }
        }
    }


}