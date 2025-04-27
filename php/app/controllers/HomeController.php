<?php
session_start();

class HomeController {
    private $model;
    private $user;

    public function __construct($pdo) {
        require_once '/var/www/app/models/Lowongan.php';
        require_once '/var/www/app/models/User.php';
        $this->model = new Lowongan($pdo);
        $this->user = new User($pdo);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->jobseeker();
            return;
        }

        $user = $this->user->getUserById($_SESSION['user_id']);

        if ($user === false) {
            echo "User not found.";
            exit();
        }

        if ($user['role'] === 'jobseeker') {
            $this->jobseeker();
        } elseif ($user['role'] === 'company') {
            $this->company();
        } else {
            echo "Unknown user role.";
            exit();
        }
    }

    private function jobseeker() {
        $user = isset($_SESSION['user_id']) ? $this->user->getUserById($_SESSION['user_id']) : null;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $jobs_per_page = 4;
        $jenis_pekerjaan = isset($_GET['jenis_pekerjaan']) ? $_GET['jenis_pekerjaan'] : null;
        $jenis_lokasi = isset($_GET['jenis_lokasi']) ? $_GET['jenis_lokasi'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sort = isset($_GET['sort-order']) ? $_GET['sort-order'] : 'DESC';

        $jobs = $this->model->getPaginatedJobs($page, $jobs_per_page, $jenis_pekerjaan, $jenis_lokasi, $search, $sort);
        $total_jobs = $this->model->getTotalJobs($jenis_pekerjaan, $jenis_lokasi, $search);
        $total_pages = ceil($total_jobs / $jobs_per_page);

        require_once '/var/www/app/views/pages/home_jobseeker.php';
    }

    private function company() {
        $user = $this->user->getUserById($_SESSION['user_id']);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $jobs_per_page = 4;
        $jenis_pekerjaan = isset($_GET['jenis_pekerjaan']) ? $_GET['jenis_pekerjaan'] : null;
        $jenis_lokasi = isset($_GET['jenis_lokasi']) ? $_GET['jenis_lokasi'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sort = isset($_GET['sort-order']) ? $_GET['sort-order'] : 'DESC';


        $jobs = $this->model->getPaginatedJobsCompany($page, $jobs_per_page, $jenis_pekerjaan, $jenis_lokasi, $search, $sort, $user['user_id']);
        $total_jobs = $this->model->getTotalJobsCompany($jenis_pekerjaan, $jenis_lokasi, $search, $user['user_id']);
        $total_pages = ceil($total_jobs / $jobs_per_page);

        require_once '/var/www/app/views/pages/home_company.php';
    }
}