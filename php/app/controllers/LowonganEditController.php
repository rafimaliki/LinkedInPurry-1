<?php
session_start();

class LowonganEditController {

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
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }  
        
        $lowonganId = $_GET['id'];
        $lowonganData = $this->models['lowongan']->getLowonganById($lowonganId);

        if ($_SESSION['user_id'] != $lowonganData['company_id']) {
            header("Location: /home");
            exit();
        }

        require_once '/var/www/app/views/pages/lowongan_edit.php';

    }
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $formData = [
                'posisi' => trim($_POST['posisi']),
                'deskripsi' => trim($_POST['deskripsi']),
                'jenis_pekerjaan' => trim($_POST['jenis_pekerjaan']),
                'jenis_lokasi' => trim($_POST['jenis_lokasi']),
                'is_open' => $_POST['is_open'],
                'lowongan_id' => $_POST['lowongan_id']
            ];
    
            $_SESSION['formData'] = $formData;
    
            $this->models['lowongan']->updateLowongan($formData['lowongan_id'],[
                'posisi' => $formData['posisi'],
                'deskripsi' => $formData['deskripsi'],
                'jenis_pekerjaan' => $formData['jenis_pekerjaan'],
                'jenis_lokasi' => $formData['jenis_lokasi'],
                'is_open' => $formData['is_open']
            ]);
    
            $uploadDir = '/var/www/public/media/lowongan_attachment/';
            $filename = 'lowongan_' . $formData['lowongan_id'] . '.' . pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                    if (copy($_FILES['poster']['tmp_name'], $uploadDir . $filename)) {
            
                        unset($_SESSION['formData']);
                        echo json_encode(['success' => true, 'lowongan_id' => $formData['lowongan_id']]);
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Gagal copy file 1']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Gagal upload file 1']);
                }
            } else {
                echo json_encode(['success' => true, 'lowongan_id' => $formData['lowongan_id'],  'message' => 'Tidak ada attachment baru 1']);
            }
            
        } else {
            header("Location: /home");
            exit;
        }
    }
}