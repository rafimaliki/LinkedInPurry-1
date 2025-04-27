<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkInPurry</title>
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/page_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
</head>

<body>
    <?php include '/var/www/app/views/layout/header.php'; ?>

    <section class="jobseeker">
        <div class="jobseeker_profile">
            <div class="homeprofile">
                <h3>Profil</h3>
                <i class="fa fa-user-circle profile-pict" aria-hidden="true"></i>
                <?php if (isset($_SESSION['user_id'])): ?>
                <p><?= htmlspecialchars($user['nama']) ?></p>
                <p><?= htmlspecialchars($user['email']) ?></p>
                <!-- <p><?= htmlspecialchars($user['role']) ?></p> -->
                <?php else: ?>
                <p>Belum login</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="joblist">
            <div class="jobs">
                <?php if (count($jobs) > 0): ?>
                <?php foreach ($jobs as $job): ?>
                <div class="job">
                    <div class="circle">
                        <div class="rectangle one"></div>
                        <div class="rectangle two"></div>
                    </div>
                    <div class="job-left">
                        <h3><?= htmlspecialchars($job['posisi']) ?></h3>
                        <div class="job-genre">
                            <p class="job-genre-item"><?= htmlspecialchars($job['jenis_pekerjaan']) ?> </p>
                            <p class="job-genre-item"> <?= htmlspecialchars($job['jenis_lokasi']) ?></p>
                            <p class="job-genre-item"> <?= htmlspecialchars($job['is_open'] ? 'buka' : 'tutup') ?></p>
                        </div>
                        <div class="jobCompanyButton">
                            <a href="lowongan/detail?id=<?php echo $job['lowongan_id']; ?>">
                                <button type="button">Detail</button>
                            </a>

                        </div>
                    </div>

                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>Tidak ada hasil.</p>
                <?php endif; ?>
            </div>
            <div class="pagination">
                <?php
                $max_pages_to_show = 5;
                $start_page = max(1, $page - floor($max_pages_to_show / 2));
                $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);

                if ($end_page - $start_page + 1 < $max_pages_to_show) {
                    $start_page = max(1, $end_page - $max_pages_to_show + 1);
                }

                if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&jenis_pekerjaan=<?= urlencode($jenis_pekerjaan) ?>&jenis_lokasi=<?= urlencode($jenis_lokasi) ?>"
                    class="prev">«</a>
                <?php endif; ?>

                <?php
                for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?page=<?= $i ?>&jenis_pekerjaan=<?= urlencode($jenis_pekerjaan) ?>&jenis_lokasi=<?= urlencode($jenis_lokasi) ?>"
                    class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&jenis_pekerjaan=<?= urlencode($jenis_pekerjaan) ?>&jenis_lokasi=<?= urlencode($jenis_lokasi) ?>"
                    class="next">»</a>
                <?php endif; ?>
            </div>
        </div>

        </div>

        <div class="filterjob">
            <h3>Gunakan Filter</h3>
            <form action="/home" method="GET">
                <div class="filterform">
                    <input type="hidden" name="search"
                        value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES) ?>">

                    <p>Urutkan berdasarkan</p>
                    <div class="sort-order">
                        <button type="button" class="filter-button" data-filter="sort-order"
                            data-value="ASC">Terlama</button>
                        <button type="button" class="filter-button" data-filter="sort-order"
                            data-value="DESC">Terbaru</button>
                    </div>

                    <p>Jenis Pekerjaan</p>
                    <div class="jenis_pekerjaan">
                        <button type="button" class="filter-button" data-filter="jenis_pekerjaan"
                            data-value="full-time">Full Time</button>
                        <button type="button" class="filter-button" data-filter="jenis_pekerjaan"
                            data-value="part-time">Part Time</button>
                        <button type="button" class="filter-button" data-filter="jenis_pekerjaan"
                            data-value="contract">Kontrak</button>
                    </div>

                    <p>Jenis Lokasi</p>
                    <div class="jenis_lokasi">
                        <button type="button" class="filter-button" data-filter="jenis_lokasi"
                            data-value="on-site">On-Site</button>
                        <button type="button" class="filter-button" data-filter="jenis_lokasi"
                            data-value="hybrid">Hybrid</button>
                        <button type="button" class="filter-button" data-filter="jenis_lokasi"
                            data-value="remote">Remote</button>
                    </div>
                </div>
            </form>
        </div>
    </section>


    <script>
    function getSortOrder() {
        const sortSelect = document.getElementById('sort-order');
        return sortSelect.value;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-button');
        const selectedFilters = {
            'sort-order': [],
            'jenis_pekerjaan': [],
            'jenis_lokasi': []
        };

        const urlParams = new URLSearchParams(window.location.search);
        Object.keys(selectedFilters).forEach(filterType => {
            if (urlParams.has(filterType)) {
                selectedFilters[filterType] = urlParams.get(filterType).split(',');
                selectedFilters[filterType].forEach(value => {
                    const button = document.querySelector(
                        `.filter-button[data-filter="${filterType}"][data-value="${value}"]`
                    );
                    if (button) {
                        button.classList.add('selected');
                    }
                });
            }
        });

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter');
                const filterValue = this.getAttribute('data-value');

                if (filterType === 'sort-order') {
                    selectedFilters[filterType] = [filterValue];
                    document.querySelectorAll(`.filter-button[data-filter="sort-order"]`)
                        .forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                } else {
                    if (selectedFilters[filterType].includes(filterValue)) {
                        selectedFilters[filterType] = selectedFilters[filterType].filter(
                            value => value !== filterValue);
                        this.classList.remove('selected');
                    } else {
                        selectedFilters[filterType].push(filterValue);
                        this.classList.add('selected');
                    }
                }

                updateURL();
            });
        });

        function updateURL() {
            const searchParams = new URLSearchParams(window.location.search);

            Object.keys(selectedFilters).forEach(filterType => {
                if (selectedFilters[filterType].length > 0) {
                    searchParams.set(filterType, selectedFilters[filterType].join(','));
                } else {
                    searchParams.delete(filterType);
                }
            });

            window.location.search = searchParams.toString();
        }
    });
    </script>
</body>

</html>