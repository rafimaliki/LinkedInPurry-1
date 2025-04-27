<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>
    <link rel="stylesheet" href="/css/nav_bar.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/page_riwayat.css">
    <link rel="stylesheet" href="/css/debug.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=0.75">
</head>

<body>
    <?php include '/var/www/app/views/layout/header.php'; ?>

    <div class="page-title">
        Riwayat Lamaran
        <!-- <div id="filter-buttons" class="filter-buttons">
            <button id="filter-semua" value="all" class="button selected">Semua</button>
            <button id="filter-waiting" value="waiting" class="button">Menunggu</button>
            <button id="filter-accepted" value="accepted" class="button">Diterima</button>
            <button id="filter-rejected" value="rejected" class="button">Ditolak</button>
        </div> -->
    </div>
    <?php foreach ($list_lamaran as $lamaran): ?>
    <a class="atag" id="<?= $lamaran['lowongan_id'] ?>" href="/lowongan/detail?id=<?= $lamaran['lowongan_id'] ?>">

        <div class="history-card">
            <!-- <p><?= $lamaran['created_at'] ?></p> -->
            <div class="card-header">
                <p class="posisi"><?= $lamaran['posisi'] ?></p>
                <div class="card-header-row">

                    <p class="nama-perusahaan"><?= $lamaran['nama_perusahaan'] ?></p>
                    <div class="tags">
                        <p class="tag-item"><?= $lamaran['jenis_lokasi'] ?></p>
                        <p class="tag-item"><?= $lamaran['jenis_pekerjaan'] ?></p>
                    </div>
                </div>
            </div>
            <table class="status-table">
                <tr>
                    <td class="row-title">Waktu Submisi</td>
                    <td><?= $lamaran['created_at'] ?></td>
                </tr>
                <tr>
                    <td class="row-title">Status</td>
                    <td><?= $lamaran['status'] ?></td>
                </tr>
                <?php if ($lamaran['status_reason'] != null): ?>

                <tr>
                    <td class="row-title">Alasan</td>
                    <td>
                        <div class="status-box">
                            <?= html_entity_decode(str_replace('"', '', $lamaran['status_reason'])); ?>

                        </div>
                    </td>
                </tr>
                <?php endif; ?>

            </table>
        </div>
    </a>
    <?php endforeach; ?>

    <script>
    const listLamaran = JSON.parse('<?= addslashes($list_lamaran_json) ?>');
    console.log(listLamaran);
    </script>

    <script>
    const filterContainer = document.getElementById('filter-buttons');
    const buttons = filterContainer.querySelectorAll('.button');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('selected'));

            button.classList.add('selected');
            console.log(button.value + ' clicked');
        });
    });
    </script>


</body>

</html>