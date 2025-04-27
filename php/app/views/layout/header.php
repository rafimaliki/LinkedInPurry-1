<header>
    <nav class="navbar">
        <div class="left-nav">
            <i style="font-size:46px" class="fab fa-linkedin"></i>
            <div class="search-box">
                <span class="icon"><i class="fas fa-search"></i></span>
                <input type="text" id="search-input" placeholder="Posisi, keahlian, atau perusahaan"
                    value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
            </div>
        </div>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="right-nav" id="nav-menu">
            <li><a href="/"><i class="fas fa-home"></i>Utama</a></li>
            <li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/profil"><i class="fas fa-user-circle"></i>Saya</a>
                <?php else: ?>
                <a href="#"><i class="fas fa-user-circle"></i>Belum Login</a>
                <?php endif; ?>
            </li>
            <?php if (isset($_SESSION['user_id']) && $user['role'] === 'jobseeker'): ?>
                <li>
                    <a href="/riwayat"><i class="fas fa-history"></i>Riwayat</a>
                </li>
            <?php endif; ?>
            <li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i>Keluar</a>
                <?php else: ?>
                <a href="/login"><i class="fas fa-sign-in-alt"></i>Masuk</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>
<script>
document.getElementById('hamburger').addEventListener('click', function() {
    var navMenu = document.getElementById('nav-menu');
    if (navMenu.style.display === 'block') {
        navMenu.style.display = 'none';
    } else {
        navMenu.style.display = 'block';
    }
});

function debounce(func, delay) {
    let debounceTimer;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

function updateURL() {
    const searchInput = document.getElementById('search-input');
    const query = searchInput.value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', query);
    window.location.href = url.toString();
}

const searchInput = document.getElementById('search-input');
searchInput.addEventListener('input', debounce(updateURL, 1000));
searchInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        updateURL();
    }
});

const filterForm = document.querySelector('.filterform');
if (filterForm)
    filterForm.addEventListener('change', updateURL);
</script>