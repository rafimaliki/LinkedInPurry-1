<?php

class Lamaran {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getLamaranById($lamaran_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lamaran WHERE lamaran_id = :lamaran_id");
        $stmt->execute(array(":lamaran_id" => $lamaran_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result; 
    }
    public function getLamaranByUId_LowId($userid, $lowId) {
        $stmt = $this->pdo->prepare("SELECT * FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowId");
        $stmt->execute(array(":userid" => $userid, ":lowId" => $lowId));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result; 
    }

    public function getLamaranByLowowonganId($lowId) {
        $stmt = $this->pdo->prepare("SELECT * FROM lamaran l NATURAL JOIN user u WHERE lowongan_id = :lowId");
        $stmt->execute(array(":lowId" => $lowId));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result; 
    }

    public function addLamaran($userid, $lowid, $cvpath = NULL, $videopath = NULL) {

        $stmt = $this->pdo->prepare("SELECT * FROM lamaran WHERE user_id = :userid AND lowongan_id = :lowid");
        $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid));
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        }

        $stmt = $this->pdo->prepare("INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path) VALUES (:userid, :lowid, :cvpath, :videopath)");
        return $stmt->execute(array(":userid" => $userid, ":lowid" => $lowid, ":cvpath" => $cvpath, ":videopath" => $videopath));
        
    }

    public function getAllLamaranbyUserId($userid) {
        $stmt = $this->pdo->prepare("
            SELECT la.lamaran_id, la.lowongan_id, la.status, la.status_reason, la.created_at, lo.posisi, lo.jenis_pekerjaan, lo.jenis_lokasi, u.nama AS nama_perusahaan
            FROM lamaran la 
            JOIN lowongan lo ON la.lowongan_id = lo.lowongan_id 
            JOIN user u ON lo.company_id = u.user_id
            WHERE la.user_id = :userid 
            ORDER BY la.created_at DESC
        ");
        $stmt->execute(array(":userid" => $userid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result; 
    }

    public function updateLamaran( $lamaran_id, $status, $status_reason) {
        $allowed_tags = '<p><h1><h2><strong><em><u><ul><ol><li><br>';
        
        $status_reason = strip_tags($status_reason, $allowed_tags);
        $status_reason = htmlspecialchars($status_reason, ENT_QUOTES, 'UTF-8');

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $this->pdo->prepare("UPDATE lamaran SET status = :new_status, status_reason = :new_status_reason WHERE lamaran_id = :lamaran_id");
        return $stmt->execute(array(
            ":new_status" => $status,
            ":new_status_reason" => $status_reason,
            ":lamaran_id" => $lamaran_id
        ));
    }

    public function getLamaranCvByLowonganId($lowongan_id) {
        $stmt = $this->pdo->prepare("SELECT cv_path FROM lamaran WHERE lowongan_id = :lowongan_id AND cv_path IS NOT NULL");
        $stmt->execute(array(":lowongan_id" => $lowongan_id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result; 
    }

    public function getLamaranVideoByLowonganId($lowongan_id) {
        $stmt = $this->pdo->prepare("SELECT video_path FROM lamaran WHERE lowongan_id = :lowongan_id AND video_path IS NOT NULL");
        $stmt->execute(array(":lowongan_id" => $lowongan_id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $result; 
    }

    

    
    // public function getLamaranById($lamaran_id) {
    //     $stmt = $this->pdo->prepare(" 
    //         SELECT *
    //         FROM lamaran la
    //         WHERE la.lamaran_id = :lamaranid
    //     ");
    //     $stmt->execute(array(":lamaranid" => $lamaran_id));
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    public function updateStatus($lamaran_id, $statusLamaran, $status_reason = null) {
        $stmt = $this->pdo->prepare("
            UPDATE lamaran 
            SET status = :status, status_reason = :status_reason 
            WHERE lamaran_id = :lamaranid 
        ");
        $stmt->execute([
            ':status' => $statusLamaran,
            ':status_reason' => $status_reason,
            ':lamaranid' => $lamaran_id
        ]);
        return $stmt->rowCount() > 0;  
    }
    
}