<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan</title>

    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/page_lowongan_detail.css">
    <link rel="stylesheet" href="/css/debug.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
</head>

<body>
    <?php include '/var/www/app/views/layout/header.php'; ?>
    <!-- <div class="data_log">
        <p>user_id: <?= $_SESSION['user_id'] ?></p>
        <p>user_name: <?= $_SESSION['user_name'] ?></p>
        <p>user_role: <?= $_SESSION['user_role'] ?></p>
        <p>lowongan_id: <?= $_GET['id'] ?></p>
        <p>perusahaan_id: <?= $perusahaan['user_id'] ?></p>
    </div> -->

    <div class="container bg-white" id="profil">
        <div class="profile-header">
            <div class="profile-img-frame">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
            <p class="profile-company-name"><?= $perusahaan['nama']; ?></p>
        </div>
        <p class="job-title"><?= $lowongan['posisi']; ?></p>
        <p class="company-location-update">
            <span> <?= $companyDetail['lokasi']; ?> </span>
            <span class="separator">•</span>
            <span> <?= $updatedAt; ?> </span>
        </p>
        <div class="job-genre">
            <div class="job-genre-item"><?= $lowongan['jenis_lokasi']?></div>
            <div class="job-genre-item"><?= $lowongan['jenis_pekerjaan']?></div>
            <div class="job-genre-item" id="genre-status"><?= $statusTag?></div>
        </div>
        <div class="buttons">
            <button id="sunting" class="button bg-blue btn-active">
                Sunting
            </button>
            <button id="toggle-status" class="button bg-blue btn-active">
                <?= $isOpen ?>

            </button>

            <button class="button bg-red btn-active" id="delete-lowongan">
                <i class="fa fa-trash" aria-hidden="true"></i>
                Hapus
            </button>
        </div>
    </div>

    <div class="container bg-white" id="about_job">
        <p class="container-title">Tentang Pekerjaan</p>
        <!-- <p class="lampiran"> Lampiran:
            <a href="/media/lowongan_attachment/<?= htmlspecialchars($attachmentLowongan['file_path']) ?>"
                target="_blank">🔗 <span> <?= $attachmentLowongan['file_path']?></span></a>
        </p> -->
        <div class="rich-text-box">
            <p class="container-paragraf"><?= html_entity_decode(str_replace('"', '', $lowongan['deskripsi'])); ?></p>
        </div>
        <div class="img-container">
            <img src="/media/lowongan_attachment/<?= htmlspecialchars($attachmentLowongan['file_path']) ?>"
                alt="attachment">
        </div>
    </div>

    <div class="container bg-white" id="about_company">
        <p class="container-title">Tentang Perusahaan</p>
        <div class="profile-header">
            <div class="profile-img-frame">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
            </div>
            <p class="profile-company-name"><?= $perusahaan['nama']; ?></p>
        </div>
        <p class="container-paragraf"><?= $companyDetail['about']; ?> </p>

    </div>

    <?php if (!empty($listLamaran)): ?>
    <div class="container bg-white" id="daftar_pelamar">
        <div class="Header-with-exportButton">
            <p class="container-title">Daftar Pelamar</p>
            <button name="export_data_pelamar" id="export_data_pelamar" class="button bg-blue btn-active">
                Export ke CSV
            </button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listLamaran as $lamaran): ?>
                <tr id="<?= htmlspecialchars($lamaran['lamaran_id']) ?>">
                    <td>
                        <a class="table-href"
                            href="<?= "/lamaran/detail?id=" . htmlspecialchars($lamaran['lamaran_id']) ?>">
                            <?= htmlspecialchars($lamaran['nama']); ?>
                        </a>
                    </td>
                    <td>
                        <a class="table-href"
                            href="<?= "/lamaran/detail?id=" . htmlspecialchars($lamaran['lamaran_id']) ?>">
                            <?= htmlspecialchars($lamaran['status']); ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const button = document.getElementById('toggle-status');
        const tag = document.getElementById('genre-status');

        const lowonganId = <?= json_encode($_GET['id']); ?>;

        button.addEventListener('click', function() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/lowongan/toggle', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const payload = {
                id: lowonganId,
            };

            const params = new URLSearchParams(payload).toString();

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;

                if (xhr.status === 200) {
                    const res = JSON.parse(xhr.responseText);
                    button.textContent = res.newStatus == 1 ? 'Tutup' : 'Buka';
                    tag.textContent = res.newStatus == 1 ? 'open' : 'closed';
                }
            };

            xhr.send(params);
        });

    });

    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('export_data_pelamar');

        const lowonganId = <?= json_encode($_GET['id']); ?>;

        button.addEventListener('click', function() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/lowongan/export', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const payload = {
                id: lowonganId,
            };

            const params = new URLSearchParams(payload).toString();
            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;

                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    const csvData = new Blob([xhr.responseText], {
                        type: 'text/csv'
                    });

                    const downloadLink = document.createElement("a");
                    downloadLink.href = URL.createObjectURL(csvData);

                    downloadLink.download = 'data_lamaran.csv';

                    downloadLink.click();

                    URL.revokeObjectURL(downloadLink.href);
                }

            };


            xhr.send(params);
        });

    });

    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('sunting');

        button.addEventListener('click', function() {
            window.location.href = `/lowongan/edit?id=<?= $_GET['id'] ?>`;
        });

    });

    document.addEventListener('DOMContentLoaded', function() {

        const button = document.getElementById('delete-lowongan');
        const lowonganId = <?= json_encode($_GET['id']); ?>;

        button.addEventListener('click', function() {
            const confirmation = confirm('Apakah anda yakin ingin menghapus lowongan ini?');

            if (confirmation) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/lowongan/delete', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                const payload = {
                    id: lowonganId,
                };

                const params = new URLSearchParams(payload).toString();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState !== 4) return;

                    if (xhr.status === 200) {
                        console.log(xhr.responseText);

                        window.location.href = '/home';
                    }
                };

                xhr.send(params);
            }
        });
    });
    </script>
</body>

</html>