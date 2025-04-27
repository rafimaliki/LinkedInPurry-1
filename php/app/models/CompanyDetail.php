<?php

class CompanyDetail {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getCompanyDetailById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM company_detail WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result; 
    }

    public function getNamebyUserId($id) {
        $stmt = $this->pdo->prepare("SELECT nama FROM user WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['nama']; 
    }

    public function getJobbyLowonganId($id) {
        $stmt = $this->pdo->prepare("SELECT posisi FROM lowongan WHERE lowongan_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['posisi']; 
    }

    public function getTanggalMelamar($userid, $lowid) {
        $stmt = $this->pdo->prepare("SELECT created_at FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowid");
        $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['created_at']; 
    }

    public function getCVPath($userid, $lowid) {
        $stmt = $this->pdo->prepare("SELECT cv_path FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowid");
        $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['cv_path']; 
    }

    public function getVideoPath($userid, $lowid) {
        $stmt = $this->pdo->prepare("SELECT video_path FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowid");
        $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['video_path']; 
    }

    public function getStatusLamaran($userid, $lowid) {
        $stmt = $this->pdo->prepare("SELECT status FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowid");
        $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['status']; 
    }

    public function getCompanyIdByLowonganId($lowongan_id) {
        $stmt = $this->pdo->prepare("SELECT company_id FROM lowongan WHERE lowongan_id = :lowongan_id");
        $stmt->execute(['lowongan_id' => $lowongan_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['company_id']; 
    }

    public function getLamaranByLowonganId($lowongan_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lamaran WHERE lowongan_id = :lowongan_id");
        $stmt->execute(['lowongan_id' => $lowongan_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result; 
    }


    public function exportLowonganToCSV($lowongan_id) {     
        $company_id = $this->getCompanyIdByLowonganId($lowongan_id);
        $namaPekerjaan = $this->getJobbyLowonganId($lowongan_id) ?? 'Unknown';
        $data_lamaran = $this->getLamaranByLowonganId($lowongan_id);
        $companyName = $this->getCompanyDetailById($company_id)['nama'] ?? 'Unknown';
    
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data_lamaran_' . $companyName . '_posisi_' . $namaPekerjaan . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
    
        $output = fopen('php://output', 'w');
        if ($data_lamaran) {
            fputcsv($output, ['Nama_Pelamar','Pekerjaan', 'Tanggal_Melamar', 'CV_Path', 'Video_Path', 'Status_Lamaran']);
            foreach ($data_lamaran as $row) {
                $namaPelamar = $this->getNamebyUserId($row['user_id']);
                $tanggalMelamar = $this->getTanggalMelamar($row['user_id'], $lowongan_id);
                $cvPath = $this->getCVPath($row['user_id'], $lowongan_id);
                $videoPath = $this->getVideoPath($row['user_id'], $lowongan_id);
                $statusLamaran = $this->getStatusLamaran($row['user_id'], $lowongan_id);
                fputcsv($output, [$namaPelamar,$namaPekerjaan, $tanggalMelamar, $cvPath, $videoPath, $statusLamaran]);
            }
        fclose($output);
            return true;
        }
        fclose($output);
        return false;
        
        exit();
    }

    public function updateCompanyDetail($id, $nama, $lokasi, $about) {
        $stmt = $this->pdo->prepare("UPDATE company_detail SET lokasi = :lokasi, about = :about WHERE user_id = :id");

       $query1 =  $stmt->execute([
            'id' => $id,
            'lokasi' => $lokasi,
            'about' => $about
        ]);
        $stmt = $this->pdo->prepare("UPDATE user SET nama = :nama WHERE user_id = :id");
        $query2 = $stmt->execute([
            'id' => $id,
            'nama' => $nama
        ]);

        return $query1 && $query2;
    }
}