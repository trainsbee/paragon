<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
        <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-brand">Dashboard</div>
        
        <div class="navbar-center">
            <div class="search-container">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" class="search-input" placeholder="Buscar...">
            </div>
        </div>

        <div class="navbar-links" id="navbarLinks">
            <a href="#"><i data-feather="home"></i> <span>Inicio</span></a>
            <a href="#"><i data-feather="users"></i> <span>Usuarios</span></a>
            <a href="#"><i data-feather="settings"></i> <span>Config</span></a>
            <a href="#"><i data-feather="log-out"></i> <span>Salir</span></a>
        </div>

        <button class="menu-toggle" id="menuToggle">
            <i data-feather="menu"></i>
        </button>
    </nav>
</body>
</html>
<script>
    feather.replace();
</script>
<script>
          // Initialize Feather Icons
        feather.replace({ width: 16, height: 16 });

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const navbarLinks = document.getElementById('navbarLinks');

        menuToggle.addEventListener('click', () => {
            navbarLinks.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navbarLinks.classList.contains('active')) {
                icon.setAttribute('data-feather', 'x');
            } else {
                icon.setAttribute('data-feather', 'menu');
            }
            feather.replace({ width: 20, height: 20 });
        });

        navbarLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navbarLinks.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.setAttribute('data-feather', 'menu');
                feather.replace({ width: 20, height: 20 });
            });
        });

</script>