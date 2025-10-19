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
        <nav>
            <div>
                <div>
                    <a href="dashboard.php">Sistema de Pausas</a>
                </div>
                
                <div>
                    <ul>
                        ${isAdmin ? `
                            <!-- Enlaces para administradores -->
                            <li>
                                <a href="dashboard.php">Dashboard</a>
                            </li>
                            <li>
                                <a href="pauses.php">Todas las Pausas</a>
                            </li>
                            <li>
                                <a href="users.php">Usuarios</a>
                            </li>
                            <li>
                                <a href="reports.php">Reportes</a>
                            </li>
                        ` : `
                            <!-- Enlaces para usuarios normales -->
                            <li>
                                <a href="dashboard.php">Mis Pausas</a>
                            </li>
                        `}
                        
                        <!-- Enlace de cierre de sesión común -->
                        <li>
                            <a href="#" onclick="logout()">Cerrar Sesión</a>
                        </li>
                    </ul>
                    
                    <!-- Información del usuario -->
                    <div>
                        <span>${currentUser.name || 'Usuario'}</span>
                        <span>
                            ${isAdmin ? 'Administrador' : 'Usuario'}
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    `;
    
    // Insertar el menú en el contenedor
    navbarContainer.innerHTML = navbarHTML;
});

// Función para cerrar sesión
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'auth.php';
}
</script>