<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/page_auth.css">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
</head>

<body class="register-body">
    <main>
        <header class="register-header">
            <div class="register-logo">
                <h2>Linked<span>
                        <i class="fab fa-linkedin"></i>
                    </span> Purry</h2>
            </div>
            <h1>Dapatkan manfaat maksimal dari dunia profesional Anda</h1>
        </header>

        <div class="container-register">
            <form action="?page=register" method="POST">
                <div class="input-form-login">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="role">Peran</label>
                    <select id="role" name="role" required>
                        <option value="jobseeker">Jobseeker</option>
                        <option value="company">Company</option>
                    </select>

                    <div id="company-details" style="display: none;">
                        <label for="location">Lokasi</label>
                        <input type="text" id="location" name="location">

                        <label for="about">Tentang Perusahaan</label>
                        <textarea id="about" name="about"></textarea>
                    </div>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit" name="register">Register</button>
                </div>
            </form>
            <p>Sudah punya akun? <a href="/login">Login di sini</a>.</p>
        </div>
    </main>
    <script>
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        const companyFields = document.getElementById('company-details');

        if (role === 'company') {
            companyFields.style.display = 'block';
        } else {
            companyFields.style.display = 'none';
        }
    });
    </script>
</body>

</html>