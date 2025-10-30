<div id="navbar-container">
    <!-- El menú se cargará aquí dinámicamente con JavaScript -->
 
</div>

<script>
// Verificar si el usuario está autenticado
document.addEventListener('DOMContentLoaded', function() {

    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    const navbarContainer = document.getElementById('navbar-container');
    
    // Si no hay usuario autenticado, redirigir a la página de autenticación
    if (!currentUser) {
        window.location.href = 'auth.php';
        return;
    }
    
    // Determinar si el usuario es administrador
    const isAdmin = currentUser.role === 'admin';
    
    // Construir el menú de navegación
    let navbarHTML = `
            <nav class="navbar">
                <div class="navbar-brand">Dashboard</div>
                <div class="navbar-center">
                    <div class="search-container">
                        <i data-feather="search" class="search-icon"></i>
                        <input type="text" class="search-input" placeholder="Buscar...">
                    </div>
                </div>
                <div class="navbar-links" id="navbarLinks">
                    <a href="dashboard.php"><i data-feather="grid"></i> <span>Dashboard</span></a>
                     ${isAdmin ? `
                    <a href="pauses.php"><i data-feather="pause"></i> <span>Pausas</span></a>
                    ` : ''}
                ${isAdmin ? `
                    <a href="permissions.php"><i data-feather="lock"></i> <span>Permisos</span></a>
                    ` : ''}
                    <a href="profile.php"><i data-feather="user"></i> <span>Perfil</span></a>
                    <a href="#" onclick="logout()"><i data-feather="log-out"></i> <span>Salir</span></a>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i data-feather="menu"></i>
                </button>
            </nav>
    `;
    
    // Insertar el menú en el contenedor
    navbarContainer.innerHTML = navbarHTML;
    feather.replace();
});

// Función para cerrar sesión
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'auth.php';
}

</script>