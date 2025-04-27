<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/page_auth.css">
    <link rel="icon" href="/media/favicon_logo/linkedin.ico" type="image/x-icon">
</head>

<body>
    <div class="app_container">
        <header>
            <div class="logo">
                <h2>Linked<span>
                        <i class="fab fa-linkedin"></i>
                        Purry
                    </span></h2>
            </div>
        </header>
        <main>
            <div class="container-login">
                <div class="header-container">
                    <h1>Login</h1>
                    <p>Ikuti perkembangan terbaru dari dunia profesional Anda.</p>
                </div>
                <form action="/login" method="POST">
                    <input type="email" id="email" name="email" required placeholder="Email">
                    <input type="password" id="password" name="password" required placeholder="Password">
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
            <div class="Join-now">
                <p>Belum punya akun? <a href="/register">Register di sini</a>.</p>
            </div>
        </main>
    </div>
</body>

</html>