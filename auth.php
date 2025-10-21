<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pausa App</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>

    <div class="auth-layout">
        <!-- Columna Izquierda - Formulario -->
        <div class="auth-form-column">
            <div class="main-content login">
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
                            <button type="submit" class="btn-primary"><i data-feather="log-in"></i> Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Columna Derecha - Contenido Promocional -->
        <div class="auth-promo-column">
            <div class="promo-content">
                <div class="testimonial">
                    <blockquote>
                        "PausaApp ha transformado la gestión de descansos en nuestra empresa. 
                        La seguridad y el rendimiento mejoran todo y aumentan mi confianza en lo que estoy construyendo."
                    </blockquote>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <span>JS</span>
                        </div>
                        <div class="author-info">
                            <strong>@juanscott</strong>
                            <span>Director de Operaciones</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- NOTIFICATION CONTAINER -->
    <div id="notification-container"></div>
  <script>
  // Check if user is already logged in
  document.addEventListener('DOMContentLoaded', () => {
    const user = localStorage.getItem('currentUser');
    if (user) {
      window.location.href = 'dashboard.php';
    }
    
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  });

  async function handleLogin(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    try {
      const response = await fetch('api/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username, password })
      });

      const data = await response.json();
      
      if (data.success) {
        // Store user data in localStorage
        localStorage.setItem('currentUser', JSON.stringify(data.user));
        showNotification('success', '¡Inicio de sesión exitoso!');
        
        setTimeout(() => {
          window.location.href = 'dashboard.php';
        }, 4000);
      } else {
        showNotification('error', 'Error: ' + data.message);

      }
    } catch (error) {
      console.error('Error:', error);
      showNotification('error', 'Error al conectar con el servidor');
    }
  }
  </script>
   
    <script>
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
            feather.replace({ width: 18, height: 18 });
            
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
   
    </script>
</body>
</html>