<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran</title>
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/page_lamaran.css">
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
    </div> -->
    <div class="container">
        <p class="title">
            Melamar ke <?= $perusahaan['nama']; ?>
        </p>
        <hr>
        <div class="profile-section">
            <p class="subtitle">Info kontak</p>
            <div class="profile">
                <i class="fa fa-user-circle" aria-hidden="true" style="font-size:30px"></i>
                <p class="profile-name"><?= $username; ?></p>
            </div>
        </div>
        <p class="form-title"> Alamat email*</p>
        <p class="fake-form"><?= $email; ?></p>

        <?php if ($show_form): ?>
        <form action="/lamaran/submit?id=<?= htmlspecialchars($lowongan_id) ?>" method="POST"
            enctype="multipart/form-data">
            <label for="pdfFile">Curriculum vitae*</label>
            <input type="file" name="pdfFile" accept=".pdf" required><br><br>

            <label for="mp4File">Video profil</label>
            <input type="file" name="mp4File" accept=".mp4" required><br><br>

            <input type="submit" name="upload" value="Submit">
        </form>
        <?php else: ?>
        <p class="form-title"> Curriculum vitae*</p>
        <a>🔗 <span> cv.pdf</span></a>
        <p class="form-title"> Video profil*</p>
        <a>🔗 <span> profil.mp4</span></a>
        <p class="success-msg">Lamaran berhasil dikirim!</p>
        <button class="button" id="back_to_lowongan">Kunjungi Lowongan</button>
        <?php endif; ?>
    </div>

    <!-- <?php if ($uploadSuccess): ?>
    <p class="title">Video Profil:</p>
    <video width="300" controls class="styled-video">
        <source src="<?= "/media/lamaran_video/" . htmlspecialchars($videoFileName) ?>" type="video/mp4">
    </video>

    <embed src="<?= "/media/lamaran_cv/" . htmlspecialchars($pdfFileName) ?>" type="application/pdf" class="styled-pdf"
        title="Embedded PDF Viewer" />
    <?php endif; ?> -->
    <script>
    const backToLowongan = document.getElementById('back_to_lowongan');
    if (backToLowongan) {
        backToLowongan.addEventListener('click', () => {
            window.location.href = `/lowongan/detail?id=<?= $lowongan_id ?>`;
        });
    }
    </script>

</body>

</html>