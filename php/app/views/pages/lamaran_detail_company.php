<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lamaran</title>
    <link rel="stylesheet" href="/css/page_detail_lamaran.css">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
</head>

<body>
    <h1>Detail Lamaran</h1>

    <div class="box">
        <h2>Data Pelamar</h2>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($jobSeeker['nama']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($jobSeeker['email']); ?></p>
        <p><strong>CV:</strong> <a href="/media/lamaran_cv/<?php echo htmlspecialchars($lamaran['cv_path']); ?>"
                target="_blank">Lihat CV</a></p>
        <p><strong>Video Perkenalan:</strong> <a
                href="/media/lamaran_video/<?php echo htmlspecialchars($lamaran['video_path']); ?>"
                target="_blank">Tonton Video</a></p>
    </div>

    <div class="box">
        <h2>Status Lamaran</h2>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($lamaran['status']); ?></p>

        <h2>Tindak Lanjut</h2>
        <form method="POST" action="/lamaran/approve">
            <input type="hidden" name="lamaran_id" value="<?php echo htmlspecialchars($lamaran_id); ?>">
            <label for="status">Ubah Status:</label>
            <select name="status" id="status">
                <option value="accept">Accept</option>
                <option value="reject">Reject</option>
                <option value="waiting">Waiting</option>
            </select>

            <div id="statusReasonContainer" style="display: none;">
                <label for="status_reason">Alasan Tindak Lanjut:</label>
                <input type="text" name="status_reason" id="status_reason">
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
    document.getElementById('status').addEventListener('change', function() {
        var status = this.value;
        var reasonContainer = document.getElementById('statusReasonContainer');
        if (status === 'waiting') {
            reasonContainer.style.display = 'block';
        } else {
            reasonContainer.style.display = 'none';
        }
    });
    </script>
</body>

</html>