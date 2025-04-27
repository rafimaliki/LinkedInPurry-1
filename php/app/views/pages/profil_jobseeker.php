<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/debug.css">
    <link rel="stylesheet" href="/css/page_profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.75">
</head>

<body>
    <?php include '/var/www/app/views/layout/header.php'; ?>
    <section class="profil">
        <div class="profilContent">
            <div class="profilTop">
            </div>
            <div class="imgprofil">
                <i class="fas fa-user"></i>
            </div>
            <div class="profilBot">
                <div class="leftProfilBot">
                    <h1 id="nama-text"><?= htmlspecialchars($user['nama']) ?></h1>
                </div>
                <div class="rightProfilBot"></div>
            </div>
        </div>
    </section>
</body>

</html>