<?php
session_start();


class RiwayatController {

    public function __construct($pdo) {

        require_once '/var/www/app/models/User.php';
        require_once '/var/www/app/models/Lowongan.php';
        require_once '/var/www/app/models/AttachmentLowongan.php';
        require_once '/var/www/app/models/CompanyDetail.php';
        require_once '/var/www/app/models/Lamaran.php';

        $this->models = [
            'perusahaan' => new User($pdo),
            'lowongan' => new Lowongan($pdo),
            'attachmentLowongan' => new AttachmentLowongan($pdo),
            'companyDetail' => new CompanyDetail($pdo),
            'lamaran' => new Lamaran($pdo)
        ];
    }
    public function index() {

        if ($_SESSION['user_role'] != 'jobseeker') {
            header("Location: /home");
        } 

        $user_id = $_SESSION['user_id'];
        $list_lamaran = $this->models['lamaran']->getAllLamaranbyUserId($user_id);

        
        $list_lamaran_json = json_encode($list_lamaran, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        require_once '/var/www/app/views/pages/riwayat.php';
    }

}