<?php

class AttachmentLowongan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAttachmentLowonganById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM attachment_lowongan WHERE lowongan_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result; 
    }

    public function addAttachmentLowongan($data) {
        $stmt = $this->pdo->prepare("INSERT INTO attachment_lowongan (lowongan_id, file_path) VALUES (:lowongan_id, :file_path)");
        $stmt->execute($data);
    }
}