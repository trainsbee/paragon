<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pausa App</title>
  <link rel="stylesheet" href="assets/css/app.css">

</head>
<body>
  <div class="container">
    <h1>Iniciar Sesión</h1>
    <form id="login-form" onsubmit="login(event)">
      <input type="text" id="username" name="username" placeholder="Usuario" required>
      <input type="password" id="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Iniciar Sesión</button>
      <p id="error-message" style="color: red; display: none;"></p>
    </form>
  </div>
  <script>
  // Check if user is already logged in
  document.addEventListener('DOMContentLoaded', () => {
    const user = localStorage.getItem('currentUser');
    if (user) {
      window.location.href = 'dashboard.php';
    }
  });

  async function login(event) {
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
        window.location.href = 'dashboard.php';
      } else {
        errorMessage.textContent = data.message || 'Usuario o contraseña incorrectos';
        errorMessage.style.display = 'block';
      }
    } catch (error) {
      console.error('Error:', error);
      errorMessage.textContent = 'Error al conectar con el servidor';
      errorMessage.style.display = 'block';
    }
  }
  </script>
</body>
</html>