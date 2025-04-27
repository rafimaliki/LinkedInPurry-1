<?php
session_start();

class LamaranController {
    private $model;
    private $user;

    public function __construct($pdo) {
        require_once '/var/www/app/models/Lowongan.php';
        require_once '/var/www/app/models/User.php';
        require_once '/var/www/app/models/Lamaran.php';

        $this->models = [
            'lowongan' => new Lowongan($pdo),
            'user' => new User($pdo),
            'lamaran' => new Lamaran($pdo)
        ];
    }

    public function index() {
    }

    public function buat() {

        if ($_SESSION['user_role'] != 'jobseeker') {
            header("Location: /home");
            return;
        }

        $lowongan_id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($lowongan_id == null) {
            header("Location: /home");
            return;
        }

        $sudah_melamar = $this->models['lamaran']->getLamaranByUId_LowId($_SESSION['user_id'], $lowongan_id);

        if ($sudah_melamar != null) {
            header("Location: /home");
            return;
        }

        $username = $_SESSION['user_name'];
        $email = $_SESSION['user_email'];

        $perusahaan = $this->models['lowongan']->getCompanyByLowId($lowongan_id);
        $lowongan = $this->models['lowongan']->getLowonganById($lowongan_id);

        $show_form = true;


        require_once '/var/www/app/views/pages/lamaran.php';
    }

    public function submit() {
        
        $lowongan_id = isset($_GET['id']) ? $_GET['id'] : null;
        
        $username = $_SESSION['user_name'];
        $email = $_SESSION['user_email'];

        $perusahaan = $this->models['lowongan']->getCompanyByLowId($lowongan_id);
        $lowongan = $this->models['lowongan']->getLowonganById($lowongan_id);

        $show_form = false;
    
        $pdfFile = null;
        $mp4File = null;
        $uploadedVideoPath = null;  
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdfFile']) && isset($_FILES['mp4File'])) {
            
            $pdfFile = $_FILES['pdfFile'];
            $mp4File = $_FILES['mp4File'];
    
            $pdfFileName = $pdfFile['name'];
            $mp4FileName = $mp4File['name'];
    
            $videoUploadDir = '/var/www/public/media/lamaran_video';
            $pdfUploadDir = '/var/www/public/media/lamaran_cv';

            $newFileName = 'lowongan_' . $lowongan_id . '_user_' . $_SESSION['user_id'];

            $videoFileName = $newFileName . '.mp4';
            $pdfFileName = $newFileName . '.pdf';

            $videoTargetPath = $videoUploadDir . '/' . $videoFileName;
            $pdfTargetPath = $pdfUploadDir . '/' . $pdfFileName;

            $uploadSuccess = false;

            if (copy($mp4File['tmp_name'], $videoTargetPath) && copy($pdfFile['tmp_name'], $pdfTargetPath)) {
                $uploadSuccess = true;
                $this->models['lamaran']->addLamaran($_SESSION['user_id'], $lowongan_id, $pdfFileName, $videoFileName);
            }
            

            require_once '/var/www/app/views/pages/lamaran.php';
        } else {
            header("Location: /home");
        }
    
    }
    

    private function jobseeker() {
        // Jobseeker related code
    }

    private function company() {
        // Company related code
    }
}