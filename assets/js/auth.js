const users = [];

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

function logout() {
  localStorage.removeItem('currentUser');
  window.location.href = 'auth.php';
}

function checkAuth() {
  const user = JSON.parse(localStorage.getItem('currentUser'));
  if (!user && window.location.pathname.includes('dashboard.php')) {
    window.location.href = 'auth.php';
  } else if (user && window.location.pathname.includes('auth.php')) {
    window.location.href = 'dashboard.php';
  }
}

document.addEventListener('DOMContentLoaded', checkAuth);