<?php
session_start();


class LowonganTambahController {

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

        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        } else if ($_SESSION['user_role'] != 'company') {
            header("Location: /home");
            exit();
        }

        require_once '/var/www/app/views/pages/lowongan_tambah.php';
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $formData = [
                'posisi' => trim($_POST['posisi']),
                'deskripsi' => trim($_POST['deskripsi']),
                'jenis_pekerjaan' => trim($_POST['jenis_pekerjaan']),
                'jenis_lokasi' => trim($_POST['jenis_lokasi']),
                'is_open' => $_POST['is_open']
            ];
    
            $_SESSION['formData'] = $formData;
    
            $this->models['lowongan']->tambahLowongan([
                'company_id' => $_SESSION['user_id'],
                'posisi' => $formData['posisi'],
                'deskripsi' => $formData['deskripsi'],
                'jenis_pekerjaan' => $formData['jenis_pekerjaan'],
                'jenis_lokasi' => $formData['jenis_lokasi'],
                'is_open' => $formData['is_open']
            ]);
    
            $uploadDir = '/var/www/public/media/lowongan_attachment/';
            $newLowonganId = $this->models['lowongan']->getLastInsertedId();
            $filename = 'lowongan_' . $newLowonganId . '.' . pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    
            if ($_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                if (copy($_FILES['poster']['tmp_name'], $uploadDir . $filename)) {
                    $this->models['attachmentLowongan']->addAttachmentLowongan([
                        'lowongan_id' => $newLowonganId,
                        'file_path' => $filename
                    ]);
    
                    unset($_SESSION['formData']);
                    echo json_encode(['success' => true, 'lowongan_id' => $newLowonganId]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Gagal copy file']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Gagal upload file']);
            }
        } else {
            header("Location: /home");
            exit;
        }
    }
}