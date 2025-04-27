<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan</title>
    <link rel="stylesheet" href="/css/page_lowongan_tambah_edit.css">
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/page_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
</head>

<body>

    <?php include '/var/www/app/views/layout/header.php'; ?>
    <div class="container">
        <p class="container-title">Tambah Lowongan</p>


        <p for="posisi">Posisi:</p>
        <input class="input-posisi" type="text" id="posisi" name="posisi" required><br>

        <p for="deskripsi">Deskripsi:</p>
        <div class="editor-container">
            <div id="editor"></div>
        </div>

        <p for="jenis_pekerjaan">Jenis Pekerjaan:</p>
        <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
            <option value="full-time">full-time</option>
            <option value="part-time">part-time</option>
            <option value="contract">contract</option>
        </select>

        <p for="jenis_lokasi">Jenis Lokasi:</p>
        <select id="jenis_lokasi" name="jenis_lokasi" required>
            <option value="on-site">on-site</option>
            <option value="hybrid">hybrid</option>
            <option value="remote">remote</option>
        </select><br>

        <p for="poster">Lampiran (.jpg):</p>
        <input type="file" name="poster" accept=".jpg" required><br><br>

        <p for="is_open">Lowongan Dibuka:</p>
        <div class="radio">
            <p for="open_true">Ya</p>
            <input type="radio" id="open_true" name="is_open" value="1" required checked>
        </div>
        <div class="radio">
            <p for="open_false">Tidak</p>
            <input type="radio" id="open_false" name="is_open" value="0" required>
        </div>


        <button type="submit" id="submit-button">Submit</button>
    </div>

    <script>
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Deskripsi lowongan',
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
    </script>
    <script>
    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', function() {

        const posisi = document.getElementById('posisi').value;
        const deskripsi = quill.root.innerHTML;
        const jenis_pekerjaan = document.getElementById('jenis_pekerjaan').value;
        const jenis_lokasi = document.getElementById('jenis_lokasi').value;
        const is_open = document.querySelector('input[name="is_open"]:checked');
        const posterFile = document.querySelector('input[type="file"]').files[0];

        if (!posisi || !deskripsi || !jenis_pekerjaan || !jenis_lokasi || !is_open || !posterFile) {
            alert('Pastikan semua data terisi');
            return;
        }

        const formData = new FormData();
        formData.append('posisi', posisi);
        formData.append('deskripsi', deskripsi);
        formData.append('jenis_pekerjaan', jenis_pekerjaan);
        formData.append('jenis_lokasi', jenis_lokasi);
        formData.append('is_open', is_open.value);
        formData.append('poster', posterFile);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/lowongan/tambah/submit', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (xhr.status !== 200) return;

            const res = JSON.parse(xhr.responseText);
            console.log(res);

            if (res.success) {
                alert('Berhasil menambahkan lowongan');
                window.location.href = `/lowongan/detail?id=${res.lowongan_id}`;
            } else {
                alert('Gagal menambahkan lowongan');
                window.location.href = '/lowongan/tambah';
            }
        }

        xhr.send(formData);
    });
    </script>

</body>

</html>