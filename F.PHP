<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Admin Dashboard</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="assets/css/main.css">
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

    <div class="main-content">
        <!-- LOGIN FORM WITH HEADER, BODY, FOOTER -->
        <div class="form-container">
            <div class="form-header">
                <h2><i data-feather="log-in"></i> Iniciar Sesión</h2>
            </div>
            <form onsubmit="handleLogin(event)">
                <div class="form-body">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" id="username" placeholder="Ingrese su usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" placeholder="Ingrese su contraseña" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button type="button" class="btn-secondary"><i data-feather="x"></i> Cancelar</button>
                    <button type="submit"><i data-feather="log-in"></i> Entrar</button>
                </div>
            </form>
        </div>

        <!-- USER MANAGEMENT FORM -->
        <div class="form-container">
            <div class="form-header">
                <h2><i data-feather="users"></i> Gestión de Usuarios</h2>
            </div>
            <div class="form-body">
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                    Administre los usuarios del sistema desde aquí.
                </p>
            </div>
            <div class="form-footer">
                <button onclick="openSideModal()"><i data-feather="user-plus"></i> Agregar Usuario</button>
            </div>
        </div>

        <!-- USERS TABLE WITH HEADER, BODY, FOOTER -->
        <div class="table-container">
            <div class="table-header">
                <h3><i data-feather="database"></i> Lista de Usuarios</h3>
                <div class="table-actions">
                    <button onclick="showNotification('info', 'Actualizando datos...')">
                        <i data-feather="refresh-cw"></i> Actualizar
                    </button>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>usuario1</td>
                            <td>usuario1@ejemplo.com</td>
                            <td>Admin</td>
                            <td>
                                <button onclick="openCenterModal()"><i data-feather="eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>usuario2</td>
                            <td>usuario2@ejemplo.com</td>
                            <td>Usuario</td>
                            <td>
                                <button onclick="openCenterModal()"><i data-feather="eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>usuario3</td>
                            <td>usuario3@ejemplo.com</td>
                            <td>Moderador</td>
                            <td>
                                <button onclick="openCenterModal()"><i data-feather="eye"></i> Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>usuario4</td>
                            <td>usuario4@ejemplo.com</td>
                            <td>Usuario</td>
                            <td>
                                <button onclick="openCenterModal()"><i data-feather="eye"></i> Ver</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">Total: 4 usuarios registrados</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- CENTER MODAL WITH HEADER, BODY, FOOTER -->
        <div class="modal-overlay" id="center-overlay" onclick="closeCenterModal()"></div>
        <div class="modal-center" id="user-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i data-feather="user"></i> Detalles del Usuario</h2>
                    <button class="close" onclick="closeCenterModal()">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="profile">
                        <div class="info-row">
                            <span class="info-label">Usuario:</span>
                            <span class="info-value">usuario1</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">usuario1@ejemplo.com</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Rol:</span>
                            <span class="info-value">Administrador</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Estado:</span>
                            <span class="info-value" style="color: var(--success);">Activo</span>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 1.5rem; margin-bottom: 1rem;">Actividad Reciente</h3>
                    <div class="table-container" style="margin: 0;">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Inicio de sesión</td>
                                        <td>2024-01-15</td>
                                    </tr>
                                    <tr>
                                        <td>Actualización de perfil</td>
                                        <td>2024-01-14</td>
                                    </tr>
                                    <tr>
                                        <td>Cambio de contraseña</td>
                                        <td>2024-01-10</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">3 actividades recientes</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" onclick="closeCenterModal()">
                        <i data-feather="x"></i> Cerrar
                    </button>
                    <button class="btn-success">
                        <i data-feather="edit"></i> Editar
                    </button>
                </div>
            </div>
        </div>

        <!-- SIDE MODAL WITH HEADER, BODY, FOOTER -->
        <div class="modal-overlay" id="side-overlay" onclick="closeSideModal()"></div>
        <div class="modal-side" id="add-user-modal">
            <div class="modal-header">
                <h2><i data-feather="user-plus"></i> Agregar Usuario</h2>
                <button class="close" onclick="closeSideModal()">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form onsubmit="handleAddUser(event)" style="display: flex; flex-direction: column; flex: 1;">
                <div class="modal-body" style="flex: 1;">
                    <div class="form-group">
                        <label for="new-username">Usuario</label>
                        <input type="text" id="new-username" placeholder="Nombre de usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="new-email">Email</label>
                        <input type="email" id="new-email" placeholder="correo@ejemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">Contraseña</label>
                        <input type="password" id="new-password" placeholder="Contraseña segura" required>
                    </div>
                    <div class="form-group">
                        <label for="new-role">Rol</label>
                        <select id="new-role">
                            <option>Usuario</option>
                            <option>Administrador</option>
                            <option>Moderador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new-bio">Biografía</label>
                        <textarea id="new-bio" placeholder="Información adicional del usuario..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeSideModal()">
                        <i data-feather="x"></i> Cancelar
                    </button>
                    <button type="submit">
                        <i data-feather="user-plus"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>

        <!-- BUTTONS -->
        <h2>Componentes de Botones</h2>
        <div class="button-group">
            <button><i data-feather="plus"></i> Default</button>
            <button class="btn-success"><i data-feather="check"></i> Éxito</button>
            <button class="btn-danger"><i data-feather="x"></i> Peligro</button>
            <button class="btn-warning"><i data-feather="alert-triangle"></i> Advertencia</button>
            <button class="btn-info"><i data-feather="info"></i> Info</button>
            <button class="btn-secondary"><i data-feather="circle"></i> Secundario</button>
        </div>

        <!-- CONTROLS -->
        <h2>Controles</h2>
        <div class="form-container">
            <div class="form-body">
                <div class="form-group">
                    <label>Selector</label>
                    <select>
                        <option>Opción 1</option>
                        <option>Opción 2</option>
                        <option>Opción 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Interruptor</label>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- PROFILES -->
        <h2>Perfiles</h2>
        <div class="admin-content">
            <div class="profile">
                <h3>Perfil Admin</h3>
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">Administrador</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rol:</span>
                    <span class="info-value">Admin del Sistema</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">admin@ejemplo.com</span>
                </div>
            </div>
            <div class="profile">
                <h3>Perfil Usuario</h3>
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">Usuario Estándar</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rol:</span>
                    <span class="info-value">Usuario</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">usuario@ejemplo.com</span>
                </div>
            </div>
        </div>

        <!-- CARDS -->
        <h2>Tarjetas</h2>
        <div class="admin-content">
            <div class="card">
                <h3>Estadísticas</h3>
                <div class="info-row">
                    <span class="info-label">Total de usuarios:</span>
                    <span class="info-value">1,234</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Usuarios activos:</span>
                    <span class="info-value">856</span>
                </div>
            </div>
            <div class="card">
                <h3>Actividad</h3>
                <div class="info-row">
                    <span class="info-label">Sesiones hoy:</span>
                    <span class="info-value">342</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nuevos registros:</span>
                    <span class="info-value">28</span>
                </div>
            </div>
        </div>

        <!-- NOTIFICATIONS TEST -->
        <h2>Sistema de Notificaciones</h2>
        <div class="button-group">
            <button class="btn-success" onclick="showNotification('success', '¡Operación completada exitosamente!')">
                <i data-feather="check-circle"></i> Éxito
            </button>
            <button class="btn-danger" onclick="showNotification('error', 'Error: No se pudo completar la operación')">
                <i data-feather="x-circle"></i> Error
            </button>
            <button class="btn-warning" onclick="showNotification('warning', 'Advertencia: Revise los datos ingresados')">
                <i data-feather="alert-triangle"></i> Advertencia
            </button>
            <button class="btn-info" onclick="showNotification('info', 'Información: Proceso iniciado correctamente')">
                <i data-feather="info"></i> Info
            </button>
        </div>
    </div>

    <!-- NOTIFICATION CONTAINER -->
    <div id="notification-container"></div>

    <script>
        // Initialize Feather Icons
        feather.replace();

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
            feather.replace();
        });

        navbarLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navbarLinks.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.setAttribute('data-feather', 'menu');
                feather.replace();
            });
        });

        // Center Modal Functions
        function openCenterModal() {
            const modal = document.getElementById('user-modal');
            const overlay = document.getElementById('center-overlay');
            
            overlay.classList.add('show');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => feather.replace(), 100);
        }

        function closeCenterModal() {
            const modal = document.getElementById('user-modal');
            const overlay = document.getElementById('center-overlay');
            
            modal.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Side Modal Functions
        function openSideModal() {
            const modal = document.getElementById('add-user-modal');
            const overlay = document.getElementById('side-overlay');
            
            overlay.classList.add('show');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => feather.replace(), 100);
        }

        function closeSideModal() {
            const modal = document.getElementById('add-user-modal');
            const overlay = document.getElementById('side-overlay');
            
            modal.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modals with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeCenterModal();
                closeSideModal();
            }
        });

        // Handle Login Form
        function handleLogin(event) {
            event.preventDefault();
            showNotification('success', '¡Inicio de sesión exitoso!');
            event.target.reset();
        }

        // Handle Add User Form
        function handleAddUser(event) {
            event.preventDefault();
            showNotification('success', '¡Usuario creado exitosamente!');
            closeSideModal();
            event.target.reset();
        }

        // Enhanced Notification System
        let notificationTimeout;
        
        function showNotification(type, message) {
            const container = document.getElementById('notification-container');
            container.innerHTML = '';
            
            if (notificationTimeout) {
                clearTimeout(notificationTimeout);
            }
            
            const icons = {
                'success': 'check-circle',
                'error': 'x-circle',
                'warning': 'alert-triangle',
                'info': 'info'
            };
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<i data-feather="${icons[type]}"></i> <span>${message}</span>`;
            
            container.appendChild(notification);
            feather.replace();
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            notificationTimeout = setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }, 3500);
        }

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            if (searchTerm) {
                console.log('Buscando:', searchTerm);
            }
        });

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
