<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lamaran</title>
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/page_lamaran_detail.css">
    <link rel="stylesheet" href="/css/debug.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">

</head>

<body>
    <?php include '/var/www/app/views/layout/header.php'; ?>
    <div class="container">
        <p class="bold">Detail Lamaran</p>
        <table class="table">
            <tr>
                <td class="row-title">Nama</td>
                <td><?= $pelamar['nama'] ?></td>
            </tr>
            <tr>
                <td class="row-title">Email</td>
                <td><?= $pelamar['email'] ?></td>
            </tr>
            <tr>
                <td class="row-title">Posisi</td>
                <td><?= $lowongan['posisi'] ?></td>
            </tr>
        </table>

        <button id="toggleVideoButton" class="toggle-button">Tampilkan Video</button>
        <video width="300" controls class="video hidden">
            <source src="<?= "/media/lamaran_video/" . htmlspecialchars($lamaran['video_path']) ?>" type="video/mp4">
        </video>

        <button id="togglePdfButton" class="toggle-button">Tampilkan CV</button>
        <embed src="<?= "/media/lamaran_cv/" . htmlspecialchars($lamaran['cv_path']) ?>" type="application/pdf"
            class="pdf hidden" title="Embedded PDF Viewer" />

        <div class="status-container">
            <p class="status-text bold">Status Lamaran</p>
            <select id="statusSelect">
                <option value="">-- Pilih Status --</option>
                <option value="accepted">Terima</option>
                <option value="rejected">Tolak</option>
            </select>
        </div>

        <div class="editor-container">
            <p class="bold">Pesan Lamaran</p>
            <div id="editor"></div>
        </div>

        <button id="submitButton" class="submit-button">Submit</button>

        <script>
        const toggleVideoButton = document.getElementById('toggleVideoButton');
        const togglePdfButton = document.getElementById('togglePdfButton');

        const videoElement = document.querySelector('.video');
        const pdfElement = document.querySelector('.pdf');

        const submitButton = document.getElementById('submitButton');

        toggleVideoButton.addEventListener('click', function() {
            if (videoElement.classList.contains('hidden')) {
                videoElement.classList.remove('hidden');
                toggleVideoButton.textContent = 'Sembunyikan Video';
            } else {
                videoElement.classList.add('hidden');
                toggleVideoButton.textContent = 'Tampilkan Video';
            }
        });

        togglePdfButton.addEventListener('click', function() {
            if (pdfElement.classList.contains('hidden')) {
                pdfElement.classList.remove('hidden');
                togglePdfButton.textContent = 'Sembunyikan CV';
            } else {
                pdfElement.classList.add('hidden');
                togglePdfButton.textContent = 'Tampilkan CV';
            }
        });

        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Tulis pesan untuk lamaran ini...',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['clean']
                ]
            }
        });

        submitButton.addEventListener('click', function() {
            const status = document.getElementById('statusSelect').value;
            const reason = quill.root.innerHTML;

            if (status === '') {
                alert('Status harus dipilih!');
                return;
            }

            if (status === 'rejected' && reason.trim() === '<p><br></p>') {
                alert('Alasan harus diisi untuk penolakan.');
                return;
            }

            const payload = {
                lamaran_id: <?= $lamaran_id ?>,
                status_reason: status === 'accepted' && reason.trim() === '<p><br></p>' ? '' : reason,
                status: status
            };

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/lamaran/update', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const params = new URLSearchParams(payload).toString();

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) return;

                if (xhr.status === 200) {
                    const res = JSON.parse(xhr.responseText);
                    window.location.href = `/lowongan/detail?id=${res.lowongan_id}`;
                }
            }

            xhr.send(params);
        });
        </script>
    </div>
</body>


</html>