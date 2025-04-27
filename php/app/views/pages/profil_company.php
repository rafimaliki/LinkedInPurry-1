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
                    <p id="lokasi-text"><?= htmlspecialchars($company_detail['lokasi']) ?></p>
                </div>
                <div class="rightProfilBot">
                    <i class="fas fa-edit" id="edit-btn"></i>
                    <p id="about-text">About<br><br><?= htmlspecialchars($company_detail['about']) ?></p>
                </div>
            </div>
        </div>
    </section>

    <dialog id="editDialog">
        <div class="dialog-header">
            <h2>Edit Profile</h2>
            <button class="close-btn">&times;</button>
        </div>

        <form method="dialog">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" value="<?= htmlspecialchars($user['nama']) ?>" require>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" value="<?= htmlspecialchars($company_detail['lokasi']) ?>" require>
            </div>

            <div class="form-group">
                <label for="about">About</label>
                <textarea id="about"><?= htmlspecialchars($company_detail['about']) ?></textarea>
            </div>

            <div class="dialog-footer">
                <button id="save-btn">Simpan</button>
            </div>
        </form>
    </dialog>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const namaText = document.getElementById('nama-text');
        const lokasiText = document.getElementById('lokasi-text');
        const aboutText = document.getElementById('about-text');

        saveBtn.addEventListener('click', function() {

            const saveBtn = document.getElementById('save-btn');
            const userId = <?= json_encode($_SESSION['user_id']); ?>;
            const namaMasukan = document.getElementById('nama').value;
            const lokasiMasukan = document.getElementById('lokasi').value;
            const aboutMasukan = document.getElementById('about').value;

            const xhr = new XMLHttpRequest();
            console.log('clicked');
            xhr.open('POST', '/profil/update', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const payload = {
                id: userId,
                nama: namaMasukan,
                lokasi: lokasiMasukan,
                about: aboutMasukan
            };

            console.log(payload);

            const params = new URLSearchParams(payload).toString();

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;

                if (xhr.status === 200) {
                    console.log('Profile updated!');
                    console.log(xhr.responseText);

                    namaText.textContent = namaMasukan;
                    lokasiText.textContent = lokasiMasukan;
                    aboutText.textContent = aboutMasukan;
                }
            };

            xhr.send(params);
        });

    });

    const editBtn = document.getElementById('edit-btn');
    const editDialog = document.getElementById('editDialog');
    const closeBtn = document.querySelector('.close-btn');
    const saveBtn = document.getElementById('save-btn');

    editBtn.addEventListener('click', () => {
        editDialog.showModal();
    });

    closeBtn.addEventListener('click', () => {
        editDialog.close();
    });

    // saveBtn.addEventListener('click', () => {
    //     const nama = document.getElementById('nama').value;
    //     const lokasi = document.getElementById('lokasi').value;
    //     const about = document.getElementById('about').value;

    //     fetch('/profil/update', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({
    //             nama,
    //             lokasi,
    //             about
    //         })
    //     }).then(response => {
    //         if (response.ok) {
    //             editDialog.close();
    //             document.getElementById('nama-text').textContent = nama;
    //             document.getElementById('lokasi-text').textContent = lokasi;
    //             document.getElementById('about-text').textContent = about;
    //         }
    //     });
    // });
    </script>
</body>

</html>