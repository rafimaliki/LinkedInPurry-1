<?php
session_start();


class LowonganDetailController {

    public function __construct($pdo, $queryParams) {

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
        

        $this->queryParams = $queryParams;
    }

    public function index() {
        ob_start();

        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $lowongan_id = isset($this->queryParams['id']) ? $this->queryParams['id'] : null;  
        
        if (count($this->queryParams) > 1 && isset($lowongan_id)) {
            header("Location: /lowongan/detail?id=" . $lowongan_id);
            exit();
        }
    
        $lowongan = $this->models['lowongan']->getLowonganById($lowongan_id);
            
        if (!$lowongan) {
            header("Location: /home"); 
            exit(); 
        }

        if ($_SESSION['user_role'] == 'company' && $_SESSION['user_id'] == $lowongan['company_id']) {
            $this->company($lowongan); 
        } else if ($_SESSION['user_role'] == 'company' && $_SESSION['user_id'] != $lowongan['company_id']) {
            $this->jobseeker($lowongan, false);
        }
        else {
            $this->jobseeker($lowongan, true);
        }

        ob_end_flush();
    }

    private function jobseeker($lowongan, $isjobseeker) {

        $perusahaan = $this->models['perusahaan']->getUserById($lowongan['company_id']);
        $attachmentLowongan = $this->models['attachmentLowongan']->getAttachmentLowonganById($lowongan['lowongan_id']);
        $companyDetail = $this->models['companyDetail']->getCompanyDetailById($lowongan['company_id']);
        $updatedAt = $this->timeAgo($lowongan['updated_at']);

        $lamaran = $this->models['lamaran']->getLamaranByUId_LowId($_SESSION['user_id'], $lowongan['lowongan_id']);
        $hasApplied = !!$lamaran;
        $isOpen = $lowongan['is_open'] == 1 ? "Tutup" : "Buka";
        $statusTag = $lowongan['is_open'] == 1 ? "open" : "closed";

        require_once '/var/www/app/views/pages/lowongan_detail_jobseeker.php';
    }

    private function company($lowongan){

        $perusahaan = $this->models['perusahaan']->getUserById($lowongan['company_id']);
        $attachmentLowongan = $this->models['attachmentLowongan']->getAttachmentLowonganById($lowongan['lowongan_id']);
        $companyDetail = $this->models['companyDetail']->getCompanyDetailById($lowongan['company_id']);
        $updatedAt = $this->timeAgo($lowongan['updated_at']);

        // $listLowongan = $this->models['lowongan']->getLowonganByCompanyId($_SESSION['user_id']);
        $listLamaran = $this->models['lamaran']->getLamaranByLowowonganId($lowongan['lowongan_id']);
        $isOpen = $lowongan['is_open'] == 1 ? "Tutup" : "Buka";
        $statusTag = $lowongan['is_open'] == 1 ? "open" : "closed";
        
        require_once '/var/www/app/views/pages/lowongan_detail_company.php';
    }
    
    private function timeAgo($date) {

        $lastUpdated = new DateTime($date);
        $now = new DateTime(); 
    
        $interval = $now->diff($lastUpdated);
    
        if ($interval->d > 0) {
            return $interval->d . ' hari yang lalu';
        } elseif ($interval->h > 0) {
            return $interval->h . ' jam yang lalu';
        } elseif ($interval->i > 0) {
            return $interval->i . ' menit yang lalu';
        } else {
            return 'Baru saja'; 
        }
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            $succes = $this->models['lowongan']->toggleLowonganById($id);
            
            if ($succes['success']) {
                echo json_encode(['succes' => true, 'newStatus' => $succes['newStatus']]);
            } else {
                echo json_encode(['succes' => false]);
            }
        }
    }

    public function export() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $exportLowongan = new CompanyDetail($GLOBALS['pdo']);
            $success = $exportLowongan->exportLowonganToCSV($id);
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lowongan_id = $_POST['id'];

            $attachmentLowongan_path = $this->models['attachmentLowongan']->getAttachmentLowonganById($lowongan_id)['file_path'];
            $lamaran_cv = $this->models['lamaran']->getLamaranCvByLowonganId($lowongan_id);
            $lamaran_video = $this->models['lamaran']->getLamaranVideoByLowonganId($lowongan_id);

            var_dump($attachmentLowongan_path);
            var_dump($lamaran_cv);
            var_dump($lamaran_video);

            $attachmentDir = 'media/lowongan_attachment/';
            $cvDir = 'media/lamaran_cv/';
            $videoDir = 'media/lamaran_video/';
    
            $success = true;
    
            if ($attachmentLowongan_path) {
                $attachmentFilePath = $attachmentDir . $attachmentLowongan_path;
                if (file_exists($attachmentFilePath)) {
                    $success &= unlink($attachmentFilePath);
                }
            }
    
            foreach ($lamaran_cv as $cv) {
                $cvFilePath = $cvDir . $cv['cv_path'];
                if (file_exists($cvFilePath)) {
                    $success &= unlink($cvFilePath);
                }
            }
    
            foreach ($lamaran_video as $video) {
                $videoFilePath = $videoDir . $video['video_path'];
                if (file_exists($videoFilePath)) {
                    $success &= unlink($videoFilePath);
                }
            }

            $success &= $this->models['lowongan']->deleteLowongan($lowongan_id);
    
    
            echo json_encode(['success' => $success]);
           
        }
    }
}