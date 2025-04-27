<?php

class Lowongan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getLowonganById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lowongan NATURAL JOIN attachment_lowongan WHERE lowongan_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result; 
    }
    
    public function getLowonganCompanyById($id) {
        $stmt = $this->pdo->prepare("SELECT company_id FROM lowongan WHERE lowongan_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result; 
    }

    public function getLastInsertedId() {
        return $this->pdo->lastInsertId();
    }   
    
    public function tambahLowongan($data) {
        $allowed_tags = '<p><h1><h2><strong><em><u><ul><ol><li><br>';
        
        $deskripsi = strip_tags($data['deskripsi'], $allowed_tags);
        $deskripsi = htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8');

        $data['deskripsi'] = $deskripsi;    
        $stmt = $this->pdo->prepare("INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi, is_open) VALUES (:company_id, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi, :is_open)");
        $stmt->execute($data);
    }

    public function updateLowongan($id, $data) {
        $allowed_tags = '<p><h1><h2><strong><em><u><ul><ol><li><br>';
        
        $deskripsi = strip_tags($data['deskripsi'], $allowed_tags);
        $deskripsi = htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8');

        $data['deskripsi'] = $deskripsi;    

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $this->pdo->prepare("UPDATE lowongan SET posisi = :posisi, deskripsi = :deskripsi, jenis_pekerjaan = :jenis_pekerjaan, jenis_lokasi = :jenis_lokasi, is_open = :is_open WHERE lowongan_id = :id");
        return $stmt->execute(array_merge($data, ['id' => $id]));
    }

    public function toggleLowonganById($id) {
        $stmt = $this->pdo->prepare('SELECT is_open FROM lowongan WHERE lowongan_id = :id');
        $stmt->execute(['id' => $id]);
        $currentStatus = $stmt->fetchColumn();
    
        $newStatus = ($currentStatus == 1) ? 0 : 1;
    
        $stmt = $this->pdo->prepare('UPDATE lowongan SET is_open = :newStatus WHERE lowongan_id = :id');
        $success = $stmt->execute(['newStatus' => $newStatus, 'id' => $id]);
    
        return [
            'success' => $success,
            'newStatus' => $newStatus
        ];
    }
    
    
    public function getPaginatedJobs($page, $jobs_per_page, $jenis_pekerjaan = null, $jenis_lokasi = null, $search = '', $sort = 'DESC') {
        $offset = ($page - 1) * $jobs_per_page;
        $query = "SELECT * FROM lowongan WHERE 1 = 1";
        $params = [];

        if ($jenis_pekerjaan) {
            if ($jenis_pekerjaan === 'full-time,part-time,contract' || $jenis_pekerjaan === 'full-time,contract,part-time' || $jenis_pekerjaan === 'part-time,full-time,contract' || $jenis_pekerjaan === 'part-time,contract,full-time' || $jenis_pekerjaan === 'contract,full-time,part-time' || $jenis_pekerjaan === 'contract,part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'full-time,part-time' || $jenis_pekerjaan === 'part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time')";
            } elseif ($jenis_pekerjaan === 'full-time,contract' || $jenis_pekerjaan === 'contract,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'part-time,contract' || $jenis_pekerjaan === 'contract,part-time') {
                $query .= " AND jenis_pekerjaan IN ('part-time', 'contract')";
            } else {
                $query .= " AND jenis_pekerjaan = :jenis_pekerjaan";
                $params[':jenis_pekerjaan'] = $jenis_pekerjaan;
            }
        }

        if ($jenis_lokasi) {
            if ($jenis_lokasi === 'on-site,hybrid,remote' || $jenis_lokasi === 'on-site,remote,hybrid' || $jenis_lokasi === 'hybrid,on-site,remote' || $jenis_lokasi === 'hybrid,remote,on-site' || $jenis_lokasi === 'remote,on-site,hybrid' || $jenis_lokasi === 'remote,hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid', 'remote')";
            } elseif ($jenis_lokasi === 'on-site,hybrid' || $jenis_lokasi === 'hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid')";
            } elseif ($jenis_lokasi === 'on-site,remote' || $jenis_lokasi === 'remote,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'remote')";
            } elseif ($jenis_lokasi === 'hybrid,remote' || $jenis_lokasi === 'remote,hybrid') {
                $query .= " AND jenis_lokasi IN ('hybrid', 'remote')";
            } else {
                $query .= " AND jenis_lokasi = :jenis_lokasi";
                $params[':jenis_lokasi'] = $jenis_lokasi;
            }
        }

        if (!empty($search)) {
            $query .= " AND LOWER(posisi) LIKE LOWER(:search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query .= " ORDER BY created_at $sort";

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $jobs_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalJobs($jenis_pekerjaan = null, $jenis_lokasi = null, $search = '') {
        $query = "SELECT COUNT(*) FROM lowongan WHERE 1 = 1";
        $params = [];

        if ($jenis_pekerjaan) {
            if ($jenis_pekerjaan === 'full-time,part-time,contract' || $jenis_pekerjaan === 'full-time,contract,part-time' || $jenis_pekerjaan === 'part-time,full-time,contract' || $jenis_pekerjaan === 'part-time,contract,full-time' || $jenis_pekerjaan === 'contract,full-time,part-time' || $jenis_pekerjaan === 'contract,part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'full-time,part-time' || $jenis_pekerjaan === 'part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time')";
            } elseif ($jenis_pekerjaan === 'full-time,contract' || $jenis_pekerjaan === 'contract,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'part-time,contract' || $jenis_pekerjaan === 'contract,part-time') {
                $query .= " AND jenis_pekerjaan IN ('part-time', 'contract')";
            } else {
                $query .= " AND jenis_pekerjaan = :jenis_pekerjaan";
                $params[':jenis_pekerjaan'] = $jenis_pekerjaan;
            }
        }

        if ($jenis_lokasi) {
            if ($jenis_lokasi === 'on-site,hybrid,remote' || $jenis_lokasi === 'on-site,remote,hybrid' || $jenis_lokasi === 'hybrid,on-site,remote' || $jenis_lokasi === 'hybrid,remote,on-site' || $jenis_lokasi === 'remote,on-site,hybrid' || $jenis_lokasi === 'remote,hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid', 'remote')";
            } elseif ($jenis_lokasi === 'on-site,hybrid' || $jenis_lokasi === 'hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid')";
            } elseif ($jenis_lokasi === 'on-site,remote' || $jenis_lokasi === 'remote,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'remote')";
            } elseif ($jenis_lokasi === 'hybrid,remote' || $jenis_lokasi === 'remote,hybrid') {
                $query .= " AND jenis_lokasi IN ('hybrid', 'remote')";
            } else {
                $query .= " AND jenis_lokasi = :jenis_lokasi";
                $params[':jenis_lokasi'] = $jenis_lokasi;
            }
        }

        if (!empty($search)) {
            $query .= " AND LOWER(posisi) LIKE LOWER(:search)";
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPaginatedJobsCompany($page, $jobs_per_page, $jenis_pekerjaan = null, $jenis_lokasi = null, $search = '', $sort = 'DESC', $company_id) {
        $offset = ($page - 1) * $jobs_per_page;
        $query = "SELECT * FROM lowongan WHERE company_id = :company_id";
        $params = [':company_id' => $company_id];

        if ($jenis_pekerjaan) {
            if ($jenis_pekerjaan === 'full-time,part-time,contract' || $jenis_pekerjaan === 'full-time,contract,part-time' || $jenis_pekerjaan === 'part-time,full-time,contract' || $jenis_pekerjaan === 'part-time,contract,full-time' || $jenis_pekerjaan === 'contract,full-time,part-time' || $jenis_pekerjaan === 'contract,part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'full-time,part-time' || $jenis_pekerjaan === 'part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time')";
            } elseif ($jenis_pekerjaan === 'full-time,contract' || $jenis_pekerjaan === 'contract,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'part-time,contract' || $jenis_pekerjaan === 'contract,part-time') {
                $query .= " AND jenis_pekerjaan IN ('part-time', 'contract')";
            } else {
                $query .= " AND jenis_pekerjaan = :jenis_pekerjaan";
                $params[':jenis_pekerjaan'] = $jenis_pekerjaan;
            }
        }

        if ($jenis_lokasi) {
            if ($jenis_lokasi === 'on-site,hybrid,remote' || $jenis_lokasi === 'on-site,remote,hybrid' || $jenis_lokasi === 'hybrid,on-site,remote' || $jenis_lokasi === 'hybrid,remote,on-site' || $jenis_lokasi === 'remote,on-site,hybrid' || $jenis_lokasi === 'remote,hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid', 'remote')";
            } elseif ($jenis_lokasi === 'on-site,hybrid' || $jenis_lokasi === 'hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid')";
            } elseif ($jenis_lokasi === 'on-site,remote' || $jenis_lokasi === 'remote,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'remote')";
            } elseif ($jenis_lokasi === 'hybrid,remote' || $jenis_lokasi === 'remote,hybrid') {
                $query .= " AND jenis_lokasi IN ('hybrid', 'remote')";
            } else {
                $query .= " AND jenis_lokasi = :jenis_lokasi";
                $params[':jenis_lokasi'] = $jenis_lokasi;
            }
        }

        if (!empty($search)) {
            $query .= " AND LOWER(posisi) LIKE LOWER(:search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query .= " ORDER BY created_at $sort";

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $jobs_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalJobsCompany($jenis_pekerjaan = null, $jenis_lokasi = null, $search = '', $company_id) {
        $query = "SELECT COUNT(*) FROM lowongan WHERE company_id = :company_id";
        $params = [':company_id' => $company_id];

        if ($jenis_pekerjaan) {
            if ($jenis_pekerjaan === 'full-time,part-time,contract' || $jenis_pekerjaan === 'full-time,contract,part-time' || $jenis_pekerjaan === 'part-time,full-time,contract' || $jenis_pekerjaan === 'part-time,contract,full-time' || $jenis_pekerjaan === 'contract,full-time,part-time' || $jenis_pekerjaan === 'contract,part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'full-time,part-time' || $jenis_pekerjaan === 'part-time,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'part-time')";
            } elseif ($jenis_pekerjaan === 'full-time,contract' || $jenis_pekerjaan === 'contract,full-time') {
                $query .= " AND jenis_pekerjaan IN ('full-time', 'contract')";
            } elseif ($jenis_pekerjaan === 'part-time,contract' || $jenis_pekerjaan === 'contract,part-time') {
                $query .= " AND jenis_pekerjaan IN ('part-time', 'contract')";
            } else {
                $query .= " AND jenis_pekerjaan = :jenis_pekerjaan";
                $params[':jenis_pekerjaan'] = $jenis_pekerjaan;
            }
        }

        if ($jenis_lokasi) {
            if ($jenis_lokasi === 'on-site,hybrid,remote' || $jenis_lokasi === 'on-site,remote,hybrid' || $jenis_lokasi === 'hybrid,on-site,remote' || $jenis_lokasi === 'hybrid,remote,on-site' || $jenis_lokasi === 'remote,on-site,hybrid' || $jenis_lokasi === 'remote,hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid', 'remote')";
            } elseif ($jenis_lokasi === 'on-site,hybrid' || $jenis_lokasi === 'hybrid,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'hybrid')";
            } elseif ($jenis_lokasi === 'on-site,remote' || $jenis_lokasi === 'remote,on-site') {
                $query .= " AND jenis_lokasi IN ('on-site', 'remote')";
            } elseif ($jenis_lokasi === 'hybrid,remote' || $jenis_lokasi === 'remote,hybrid') {
                $query .= " AND jenis_lokasi IN ('hybrid', 'remote')";
            } else {
                $query .= " AND jenis_lokasi = :jenis_lokasi";
                $params[':jenis_lokasi'] = $jenis_lokasi;
            }
        }

        if (!empty($search)) {
            $query .= " AND LOWER(posisi) LIKE LOWER(:search)";
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function getLowonganByCompanyId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lowongan WHERE company_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result; 
    }

    public function getCompanyByLowId($id) {
        $stmt = $this->pdo->prepare("SELECT u.nama, u.user_id FROM user u JOIN lowongan l ON u.user_id = l.company_id WHERE l.lowongan_id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result; 
    }

    public function deleteLowongan($id) {
        $stmt = $this->pdo->prepare("DELETE FROM lowongan WHERE lowongan_id = :id");
        return $stmt->execute(['id' => $id]);
    }

}