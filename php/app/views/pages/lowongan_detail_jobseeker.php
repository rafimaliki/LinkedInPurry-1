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
        <p>has_applied: <?= $hasApplied ? 'true' : 'false' ?></p>
        <?php if ($hasApplied): ?>
        <p>lamaran_id: <?= $lamaran['lamaran_id'] ?></p>
        <p>lamaran_status: <?= $lamaran['status'] ?></p>
        <?php endif; ?>
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
        <?php if ($isjobseeker): ?>
        <?php if (!$hasApplied): ?>
        <button class="button bg-blue btn-active" id="lamar-button">
            <i style="font-size:16px" class="fab fa-linkedin"></i>
            Lamar
        </button>
        <?php else: ?>
        <button class="button btn-inactive">
            <i style="font-size:16px" class="fab fa-linkedin"></i>
            Lamar
        </button>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($hasApplied): ?>
    <div class="container status-<?= htmlspecialchars($lamaran['status']); ?>">
        <?php if ($lamaran['status'] == 'waiting'): ?>
        <p class="container-title">Lamaran Anda sedang diperiksa oleh tim kami!</p>
        <?php elseif ($lamaran['status'] == 'accepted'): ?>
        <p class="container-title">Lamaran Anda diterima!</p>
        <?php elseif ($lamaran['status'] == 'rejected'): ?>
        <p class="container-title">Lamaran Anda ditolak!</p>


        <?php endif; ?>

        <?php if (isset($lamaran['status_reason'])): ?>
        <div class="rich-text-box">
            <?= html_entity_decode(str_replace('"', '', $lamaran['status_reason'])); ?>
        </div>
        <?php endif; ?>

        <p>
            <a href="/media/lamaran_cv/<?= htmlspecialchars($lamaran['cv_path']) ?>" target="_blank" download>
                🔗 <span>CV Anda</span>
            </a>
        </p>
        <p>
            <a href="/media/lamaran_video/<?= htmlspecialchars($lamaran['video_path']) ?>" target="_blank" download>
                🔗 <span>Video Anda</span>
            </a>
        </p>
    </div>
    <?php endif; ?>


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
    <script>
    const lamarButton = document.getElementById('lamar-button');
    lamarButton.addEventListener('click', () => {
        window.location.href = `/lamaran/buat?id=<?= $_GET['id'] ?>`;
    });
    </script>
</body>

</html>