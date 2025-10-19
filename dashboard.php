<?php require_once 'settings.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pausa App</title>
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
    
  <div class="container">
    <?php include 'partials/nav.php'; ?>
    <div class="user-info">
      <h1>Gestión de Pausas</h1>
      <div class="user-details">
        <span id="user-name"></span>
        <span id="user-role" class="role"></span>
        <span id="user-department" class="department"></span>
        <button onclick="logout()">Cerrar Sesión</button>
      </div>
    </div>
    
    <div id="pause-controls" class="pause-controls-container">
      <div class="loader-overlay">
        <div class="loader-container">
          <div class="loader"></div>
          <span class="loader-text">Cargando pausas...</span>
        </div>
      </div>
      <div class="controls-content">
        <select id="reason" required>
          <option value="" disabled selected>Selecciona una razón</option>
          <option value="lunch">Almuerzo</option>
          <option value="bathroom_outside">Baño afuera</option>
          <option value="bathroom_office">Baño oficina</option>
          <option value="break">Break 15 minutos</option>
        </select>
        <button id="start-pause" onclick="startPause()">Iniciar Pausa</button>
        <button id="stop-pause" onclick="stopPause()" disabled>Detener Pausa</button>
      </div>
    </div>
    
    <h2>Pausas Registradas</h2>
    <form class="range-container" id="filter-form">
        <label for="date-range">Rango de fechas:</label>
        <input type="date" id="start-date" value="<?php 
            $today = new DateTime('now', new DateTimeZone(TIMEZONE));
            echo $today->format('Y-m-d');
        ?>">
        <input type="date" id="end-date" value="<?php 
            echo $today->format('Y-m-d');
        ?>">
        <button type="submit" class="filter-button">Filtrar</button>
    </form>
    <div class="total-pause-time">
        <span id="total-pause-time">Total de pausas: </span>
    </div>

    <div id="pause-list"></div>
  </div>
  
  <script>
  // Get user data from localStorage
  const currentUser = JSON.parse(localStorage.getItem('currentUser'));
  
  // Update UI with user info
  document.addEventListener('DOMContentLoaded', () => {
    if (!currentUser) {
      window.location.href = 'auth.php';
      return;
    }
    
    document.getElementById('user-name').textContent = currentUser.name;
    document.getElementById('user-role').textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
    
    if (currentUser.department) {
      document.getElementById('user-department').textContent = currentUser.department;
    } else {
      document.getElementById('user-department').style.display = 'none';
    }
    
    // Pauses are loaded by script.js
  });
  
  function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'auth.php';
  }
  </script>
  
  <script src="assets/js/script.js"></script>
</body>
</html>