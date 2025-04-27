<?php
session_start();

class DetailLamaranController {
    private $pdo;
    private $models;
    private $queryParams;

    public function __construct($pdo, $queryParams) {
        require_once '/var/www/app/models/User.php';
        require_once '/var/www/app/models/Lamaran.php';
        require_once '/var/www/app/models/Lowongan.php';
        require_once '/var/www/app/models/AttachmentLowongan.php';

        $this->models = [
            'user' => new User($pdo),
            'lamaran' => new Lamaran($pdo),
            'lowongan' => new Lowongan($pdo),
            'attachmentLowongan' => new AttachmentLowongan($pdo)
        ];

        $this->pdo = $pdo;
        $this->queryParams = $queryParams;
    }

    public function index() {
        $lamaran_id = $_GET['id'];
        $user_id = $_SESSION['user_id'];

        if (!isset($user_id)) {
            header("Location: /login");
            exit();
        }

        if (empty($lamaran_id) || !is_numeric($lamaran_id)) {
            header("Location: /home");
            exit();
        }

        $lamaran_id = (int) $lamaran_id;
        $lamaran = $this->models['lamaran']->getLamaranById($lamaran_id);
        if (!$lamaran) {
            header("Location: /home");
            exit();
        }

        $jobSeeker = $this->models['user']->getUserById($lamaran['user_id']);
        if (!$jobSeeker) {
            header("Location: /home");
            exit();
        }

        $attachment = $this->models['attachmentLowongan']->getAttachmentLowonganById($lamaran['lowongan_id']);

        $cv = $lamaran['cv_path'];
        $video = $lamaran['video_path'];

        $statusLamaran = ($lamaran['status'] == 1) ? "accept" : (($lamaran['status'] == 0) ? "reject" : "waiting");

        var_dump($lamaran);

        // Mengarahkan ke tampilan detailLamaran.php
        require_once '/var/www/app/views/pages/lamaran_detail_company.php';
    }
                                                                                                                                                                                                                                                                                                                                                                                                                                  
    public function handleApproval() {
        var_dump($_POST);
        $lamaran_id = $_POST['lamaran_id'];
        $statusLamaran = $_POST['status']; 
        
        if (empty($lamaran_id) || empty($statusLamaran)) {
            header("Location: /lamaran/detail?id=$lamaran_id");
            exit();
        }

        $lamaran = $this->models['lamaran']->getLamaranById($lamaran_id);

        // if ($lamaran && $lamaran['status'] == 'waiting') {
        //     $tindakLanjut = isset($_POST['status_reason']) ? $_POST['status_reason'] : null;

            $this->models['lamaran']->updateStatus($lamaran_id, $statusLamaran, $status_reason);

            $updatedLamaran = $this->models['lamaran']->getLamaranById($lamaran_id);

            // $statusLamaran = $updatedLamaran['status'];

            // header("Location: /lamaran/detail?id=$lamaran_id");
            // exit();
    //     } else {
    //         header("Location: /lamaran/detail?id=$lamaran_id");
    //         exit();
    //     }
    }
}
