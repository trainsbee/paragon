<!-- PAUSES -->
<?php 
require_once 'settings.php';
require_once 'db.php';

// Pass users data to JavaScript - we'll handle auth client-side
$users_json = json_encode($users);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Pausas - Administrador</title>
  <link rel="stylesheet" href="assets/css/app.css">
  <script src="https://unpkg.com/feather-icons"></script>
<style>
  /* Importing existing styles */
:root {
  --bg-primary: #0a0a0a;
  --bg-secondary: #141414;
  --bg-tertiary: #1a1a1a;
  --border-color: #2a2a2a;
  --text-primary: #e5e5e5;
  --text-secondary: #a0a0a0;
  --text-muted: #6b6b6b;
  --accent-primary: #6366f1;
  --accent-hover: #4f46e5;
  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background-color: var(--bg-primary);
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

nav {
  background-color: var(--bg-secondary);
  padding: 1rem 2rem;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.nav-left h1 {
  font-size: 1.25rem;
  font-weight: 500;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.875rem;
}

.user-info span {
  color: var(--text-secondary);
}

.logout-btn {
  background: none;
  border: none;
  color: var(--text-muted);
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  padding: 0.5rem;
  transition: color 0.2s;
}

.logout-btn:hover {
  color: var(--text-primary);
}

.container {
  max-width: 600px;
  width: 100%;
  margin: 2rem auto;
  padding: 0 1rem;
}

.pause-controls {
  background-color: var(--bg-secondary);
  padding: 1.5rem;
  border-radius: 0.5rem;
  border: 1px solid var(--border-color);
  position: relative;
  margin-bottom: 2rem;
}

.controls-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

select, button {
  padding: 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  border: 1px solid var(--border-color);
  background-color: var(--bg-tertiary);
  color: var(--text-primary);
  transition: all 0.2s;
}

select:focus, button:focus {
  outline: none;
  border-color: var(--accent-primary);
}

button {
  background-color: var(--accent-primary);
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

button:hover:not(:disabled) {
  background-color: var(--accent-hover);
}

button:disabled {
  background-color: var(--bg-tertiary);
  color: var(--text-muted);
  cursor: not-allowed;
}

#stop-pause.active {
  background-color: var(--danger);
}

#stop-pause.active:hover:not(:disabled) {
  background-color: #dc2626;
}

.loader-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(20, 20, 20, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: opacity 0.3s ease;
  z-index: 100;
}

.loader-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.loader {
  border: 3px solid var(--border-color);
  border-top: 3px solid var(--accent-primary);
  border-radius: 50%;
  width: 2rem;
  height: 2rem;
  animation: spin 1s linear infinite;
}

.loader-text {
  color: var(--text-secondary);
  font-size: 0.875rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.range-container {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  background-color: var(--bg-secondary);
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid var(--border-color);
  margin-bottom: 1rem;
}

.range-container label {
  color: var(--text-secondary);
  font-size: 0.875rem;
}

.range-container input[type="date"] {
  padding: 0.5rem;
  border: 1px solid var(--border-color);
  border-radius: 0.375rem;
  background-color: var(--bg-tertiary);
  color: var(--text-primary);
  font-size: 0.875rem;
}

.filter-button {
  background-color: var(--accent-primary);
  color: var(--text-primary);
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  cursor: pointer;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-button:hover {
  background-color: var(--accent-hover);
}

.filter-button:disabled {
  background-color: var(--bg-tertiary);
  color: var(--text-muted);
}

.total-pause-time, .total-pause-time-two {
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin-bottom: 1rem;
}

.total-pause-time-two.green {
  color: var(--success);
}

.total-pause-time-two.orange {
  color: var(--warning);
}

.total-pause-time-two.red {
  color: var(--danger);
}

h2 {
  font-size: 1.25rem;
  font-weight: 500;
  margin-bottom: 1rem;
  color: var(--text-primary);
}

.date-header {
  font-size: 1rem;
  color: var(--text-secondary);
  margin: 1.5rem 0 0.5rem;
}

.card {
  background-color: var(--bg-secondary);
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid var(--border-color);
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.card.in-progress {
  border-left: 3px solid var(--accent-primary);
}

.pause-info {
  display: grid;
  gap: 0.25rem;
}

.pause-info .label {
  color: var(--text-muted);
  margin-right: 0.5rem;
}

/* New styles for the admin pauses management view */
.container {
  max-width: 1000px; /* Override for wider content in admin view */
}

table {
  width: 100%;
  border-collapse: collapse;
  margin: 1rem 0;
  background-color: var(--bg-secondary);
  border: 1px solid var(--border-color);
  border-radius: 0.375rem;
}

th, td {
  padding: 0.75rem;
  font-size: 0.875rem;
  border-bottom: 1px solid var(--border-color);
}

th {
  background-color: var(--bg-tertiary);
  color: var(--text-secondary);
  font-weight: 500;
  text-align: left;
}

th.center {
  text-align: center;
}

th.right {
  text-align: right;
}

td {
  color: var(--text-primary);
}

td.center {
  text-align: center;
}

td.right {
  text-align: right;
}

.btn, .icon-button {
  background-color: var(--accent-primary);
  color: var(--text-primary);
  border: none;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: background-color 0.2s;
}

.btn:hover, .icon-button:hover {
  background-color: var(--accent-hover);
}

.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 10, 10, 0.8);
  z-index: 1000;
}

.modal-content {
  background: var(--bg-secondary);
  margin: 3rem auto;
  padding: 1.5rem;
  width: 90%;
  max-width: 800px;
  max-height: 80vh;
  overflow-y: auto;
  border-radius: 0.5rem;
  border: 1px solid var(--border-color);
}

.modal-header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 500;
  color: var(--text-primary);
}

.modal-close {
  background: none;
  border: none;
  color: var(--text-muted);
  font-size: 1.5rem;
  cursor: pointer;
  transition: color 0.2s;
}

.modal-close:hover {
  color: var(--text-primary);
}

.date-filter {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 1rem 0;
  background-color: var(--bg-secondary);
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid var(--border-color);
}

.date-filter label {
  color: var(--text-secondary);
  font-size: 0.875rem;
}

.date-filter input[type="date"] {
  padding: 0.5rem;
  border: 1px solid var(--border-color);
  border-radius: 0.375rem;
  background-color: var(--bg-tertiary);
  color: var(--text-primary);
  font-size: 0.875rem;
}

.date-filter button {
  background-color: var(--accent-primary);
  color: var(--text-primary);
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  cursor: pointer;
}

.date-filter button:hover {
  background-color: var(--accent-hover);
}

.active-pauses-container {
  margin: 1rem 0;
}

.active-pauses-table, .pause-table, .pause-summary-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0.5rem 0;
}

.active-pauses-table th, .pause-table th {
  background-color: var(--bg-tertiary);
  padding: 0.75rem;
}

.active-pauses-table td, .pause-table td {
  padding: 0.75rem;
}

.clickable-row:hover {
  background-color: var(--bg-tertiary);
  cursor: pointer;
}

.active-pause {
  color: var(--danger);
  font-weight: 500;
}

.inactive-pause {
  color: var(--success);
  font-weight: 500;
}

.pause-active {
  color: var(--success);
  font-weight: 500;
}

.pause-completed {
  color: var(--text-muted);
}

.monospace {
  font-family: 'Courier New', Courier, monospace;
}

.icon-small {
  width: 14px;
  height: 14px;
}

.pauses-summary {
  margin-bottom: 1rem;
}

.pause-summary-container {
  background-color: var(--bg-secondary);
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid var(--border-color);
}

.pause-summary-title {
  font-size: 1rem;
  font-weight: 500;
  color: var(--text-primary);
  margin: 0 0 0.75rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--border-color);
}

.pause-summary-table td {
  padding: 0.5rem;
}

.pause-summary-label {
  color: var(--text-secondary);
  font-weight: 500;
}

.pause-summary-value {
  color: var(--text-primary);
  text-align: right;
  font-weight: 500;
}

.history-container {
  margin-top: 1rem;
}

.overflow-auto {
  overflow-x: auto;
}

.loading-cell {
  text-align: center;
  padding: 1rem;
  color: var(--text-secondary);
}

.error-cell, .error-text {
  text-align: center;
  color: var(--danger);
}

h1 {
  font-size: 1.5rem;
  font-weight: 500;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

h3 {
  font-size: 1rem;
  font-weight: 500;
  color: var(--text-primary);
  margin: 1rem 0 0.5rem;
}
</style>
</head>
<body>
  <div class="container">
    <?php include 'partials/nav.php'; ?>
    
    <h1>Cargando...</h1>
    <p>Por favor espere...</p>
    
    <div class="date-filter">
      <label for="start-date">Fecha de inicio:</label>
      <input type="date" id="start-date" value="<?php echo date('Y-m-d'); ?>">
      
      <label for="end-date">Fecha de fin:</label>
      <input type="date" id="end-date" value="<?php echo date('Y-m-d'); ?>">
      
      <button onclick="loadEmployees()">Filtrar</button>
    </div>
    
    <!-- Sección de Pausas Activas -->
    <div id="active-pauses-summary" class="active-pauses-container">
      <h2>Pausas Activas</h2>
      <div id="active-pauses-list">
        <p>Cargando pausas activas...</p>
      </div>
    </div>
    
    <h2>Empleados</h2>
    <table id="employees-table" class="employees-table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>ID</th>
          <th class="center">Pausas Activas</th>
          <th class="center">Total Pausas</th>
          <th class="center">Tiempo Total</th>
          <th class="right">Acciones</th>
        </tr>
      </thead>
      <tbody id="employees-list">
        <tr>
          <td colspan="6" class="center">Cargando empleados...</td>
        </tr>
      </tbody>
    </table>
  </div>
  
  <!-- Pauses Modal -->
  <div id="pauses-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header-container">
        <h2 class="modal-title">Pausas de <span id="employee-name"></span></h2>
        <button onclick="closeModal()" class="modal-close">&times;</button>
      </div>
      
      <!-- Resumen de Pausas -->
      <div id="pauses-summary" class="pauses-summary">
        <p>Cargando resumen...</p>
      </div>
      
      <h3>Pausas Activas</h3>
      <div id="active-pauses">
        <p>Cargando pausas activas...</p>
      </div>
      
      <div class="history-container">
        <h3>Historial</h3>
        <div id="pauses-history">
          <p>Cargando historial de pausas...</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Global variables
    const users = <?php echo $users_json; ?>;
    let currentUser = null;
    let currentEmployeeId = '';

const reasons = {
  break: 'Break 15 minutos',
  lunch: 'Almuerzo',
  bathroom_outside: 'Baño afuera',
  bathroom_office: 'Baño oficina',
  meeting_manager: 'Reunión con gerente',
  meeting_rrhh: 'Reunión con RRHH',
  meeting_country_manager: 'Reunión con gerente de pais',
};

    // Check authentication
    function checkAuth() {
      const userData = localStorage.getItem('currentUser');
      if (!userData) {
        window.location.href = 'auth.php';
        return false;
      }
      
      try {
        currentUser = JSON.parse(userData);
        
        // Verify user exists in our database and is an admin
        let userFound = false;
        let isAdmin = currentUser.role === 'admin';
        
        for (const managerId in users) {
          const manager = users[managerId];
          
          if (manager.id === currentUser.id) {
            userFound = true;
            currentUser.employees = manager.employees || [];
            break;
          }
          
          const employee = manager.employees.find(emp => emp.id === currentUser.id);
          if (employee) {
            userFound = true;
            currentUser.department = manager.DEPARTMENT || '';
            break;
          }
        }
        
        if (!userFound || !isAdmin) {
          window.location.href = 'dashboard.php';
          return false;
        }
        
        return true;
      } catch (e) {
        console.error('Error parsing user data:', e);
        window.location.href = 'auth.php';
        return false;
      }
    }
    
    // Logout function
    function logout() {
      localStorage.removeItem('currentUser');
      window.location.href = 'auth.php';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      if (checkAuth()) {
        document.querySelector('h1').textContent = `Gestión de Pausas - ${currentUser.DEPARTMENT || currentUser.department || 'Administración'}`;
        document.querySelector('p').textContent = `Usuario: ${currentUser.name} (${currentUser.role === 'admin' ? 'Administrador' : 'Empleado'})`;
        
        loadEmployees();
        feather.replace();
      }
    });
    
    async function loadEmployees() {
      const startDate = document.getElementById('start-date').value;
      const endDate = document.getElementById('end-date').value;
      const employeesList = document.getElementById('employees-list');
      const activePausesList = document.getElementById('active-pauses-list');
      
      // Get employees for the current manager
      let employees = [];
      for (const managerId in users) {
        const manager = users[managerId];
        if (manager.id === currentUser.id) {
          employees = manager.employees || [];
          break;
        }
        if (manager.employees.some(e => e.id === currentUser.id)) {
          employees = manager.employees;
          currentUser.department = manager.DEPARTMENT || '';
          document.querySelector('h1').textContent = `Gestión de Pausas - ${currentUser.department}`;
          break;
        }
      }
      
      // Show loading state
      employeesList.innerHTML = `
        <tr>
          <td colspan="6" class="loading-cell">Cargando estadísticas de pausas...</td>
        </tr>`;
      activePausesList.innerHTML = '<p>Cargando pausas activas...</p>';
      
      if (employees.length === 0) {
        employeesList.innerHTML = `
          <tr>
            <td colspan="6" style="text-align: center;">No hay empleados asignados.</td>
          </tr>`;
        activePausesList.innerHTML = '<p>No hay empleados para mostrar.</p>';
        return;
      }
      
      try {
        // Single fetch for all employee stats
        const employeeIds = employees.map(e => e.id).join(',');
        const response = await fetch(`api/get_employee_stats.php?employee_ids=${employeeIds}&start_date=${startDate}&end_date=${endDate}`);
        const result = await response.json();
        
        if (!result.success) {
          throw new Error(result.message || 'Error al cargar estadísticas');
        }
        
        const stats = result.data || {};
        
        // Render both employees table and active pauses summary using the same data
        renderEmployeesTable(employees, stats);
        renderActivePausesSummary(employees, stats);
        
      } catch (error) {
        console.error('Error loading employee stats:', error);
        employeesList.innerHTML = `
          <tr>
            <td colspan="6" class="error-cell">
              Error al cargar las estadísticas: ${error.message}
            </td>
          </tr>`;
        activePausesList.innerHTML = '<p class="error-text">Error al cargar las pausas activas.</p>';
      }
      
      feather.replace();
    }
    
    function renderEmployeesTable(employees, stats) {
      const employeesList = document.getElementById('employees-list');
      employeesList.innerHTML = '';
      
      employees.forEach(employee => {
        const employeeStats = stats[employee.id] || {
          active_pauses: 0,
          total_pauses: 0,
          total_pause_time: '00:00:00'
        };
        
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${employee.name || 'N/A'}</td>
          <td>${employee.id || 'N/A'}</td>
          <td class="center ${employeeStats.active_pauses > 0 ? 'active-pause' : 'inactive-pause'}">
            ${employeeStats.active_pauses}
          </td>
          <td class="center">
            ${employeeStats.total_pauses}
          </td>
          <td class="center monospace">
            ${employeeStats.total_pause_time || '00:00:00'}
          </td>
          <td class="right">
            <button class="btn icon-button" onclick="viewPauses('${employee.id}', '${(employee.name || '').replace(/'/g, "\\'")}')"><i data-feather="eye" class="icon-small"></i> Ver</button>
          </td>
        `;
        employeesList.appendChild(row);
      });
    }
    
    function renderActivePausesSummary(employees, stats) {
      const activePausesList = document.getElementById('active-pauses-list');
      const employeesWithPauses = [];
      
      for (const employeeId in stats) {
        const employeeStats = stats[employeeId];
        if (employeeStats.active_pauses > 0) {
          const employee = employees.find(e => e.id === employeeId);
          if (employee) {
            employeesWithPauses.push({
              ...employee,
              ...employeeStats
            });
          }
        }
      }
      
      if (employeesWithPauses.length === 0) {
        activePausesList.innerHTML = '<p>No hay pausas activas en este momento.</p>';
        return;
      }
      
      let tableHTML = `
        <div class="overflow-auto">
          <table class="active-pauses-table">
            <thead>
              <tr>
                <th>Empleado</th>
                <th>Departamento</th>
                <th>Hora de Inicio</th>
                <th>Tiempo Transcurrido</th>
                <th>Razón</th>
              </tr>
            </thead>
            <tbody>
      `;
      
      employeesWithPauses.forEach(emp => {
        if (emp.pauses && emp.pauses.length > 0) {
          emp.pauses.forEach((pause, index) => {
            const isFirst = index === 0;
            const displayTime = pause.display_time || (pause.start_time.includes('T') 
              ? pause.start_time.split('T')[1].split('.')[0] 
              : pause.start_time);
              
            
            tableHTML += `
              <tr class="clickable-row" onclick="viewPauses('${emp.id}', '${emp.name.replace(/'/g, "\\'")}')">                
                <td>${isFirst ? emp.name : ''}</td>
                <td>${isFirst ? (emp.department || 'N/A') : ''}</td>
                <td>${displayTime}</td>
                <td class="elapsed-time" data-start="${pause.start_time}">00:00:00</td>
                <td>${reasons[pause.reason]|| 'Sin razón'}</td>
              </tr>
            `;
          });
        } else {
          tableHTML += `
            <tr class="clickable-row" onclick="viewPauses('${emp.id}', '${emp.name.replace(/'/g, "\\'")}')">              
              <td>${emp.name}</td>
              <td>${emp.department || 'N/A'}</td>
              <td colspan="3">Sin pausas activas</td>
            </tr>
          `;
        }
      });
      
      tableHTML += `
            </tbody>
          </table>
        </div>
      `;
      
      activePausesList.innerHTML = tableHTML;
    }
    
    function viewPauses(employeeId, employeeName) {
      currentEmployeeId = employeeId;
      document.getElementById('employee-name').textContent = employeeName;
      document.getElementById('pauses-modal').style.display = 'flex';
      
      document.getElementById('pauses-summary').innerHTML = 'Cargando...';
      document.getElementById('active-pauses').innerHTML = '';
      document.getElementById('pauses-history').innerHTML = '';
      
      loadPauses();
    }
    
    function closeModal() {
      document.getElementById('pauses-modal').style.display = 'none';
    }
    
    function formatDuration(startTime) {
      let start = new Date(startTime);
      if (isNaN(start.getTime())) {
        const timeParts = startTime.match(/(\d{2}):(\d{2}):(\d{2})/);
        if (timeParts) {
          const now = new Date();
          start = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate(),
            parseInt(timeParts[1]),
            parseInt(timeParts[2]),
            parseInt(timeParts[3])
          );
          if (start > now) {
            start.setDate(start.getDate() - 1);
          }
        }
      }
      
      const now = new Date();
      const diffMs = now - start;
      const totalSeconds = Math.floor(diffMs / 1000);
      const hours = Math.floor(totalSeconds / 3600);
      const minutes = Math.floor((totalSeconds % 3600) / 60);
      const seconds = totalSeconds % 60;
      
      return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    function updateElapsedTimes() {
      document.querySelectorAll('.elapsed-time').forEach(element => {
        const startTime = element.getAttribute('data-start');
        element.textContent = formatDuration(startTime);
      });
    }
    
    setInterval(updateElapsedTimes, 1000);
    updateElapsedTimes();
    
    async function loadPauses() {
      const startDate = document.getElementById('start-date').value;
      const endDate = document.getElementById('end-date').value;
      const activePausesList = document.getElementById('active-pauses');
      const pausesHistoryList = document.getElementById('pauses-history');
      
      activePausesList.innerHTML = 'Cargando...';
      pausesHistoryList.innerHTML = 'Cargando...';
      
      try {
        const response = await fetch(`api/get_pauses.php?employee_id=${currentEmployeeId}&start_date=${startDate}&end_date=${endDate}`);
        const result = await response.json();
        let allPauses = Array.isArray(result) ? result : (result.data || []);
        
        if (!Array.isArray(allPauses)) {
          console.error('Invalid pauses data:', result);
          allPauses = [];
        }
        
        const activePauses = allPauses.filter(pause => pause.end_time === null);
        const totalPauseSeconds = allPauses.reduce((total, pause) => {
          if (pause.end_time) {
            const start = new Date(pause.start_time);
            const end = new Date(pause.end_time);
            return total + Math.floor((end - start) / 1000);
          }
          return total;
        }, 0);
        
        const hours = Math.floor(totalPauseSeconds / 3600);
        const minutes = Math.floor((totalPauseSeconds % 3600) / 60);
        const seconds = totalPauseSeconds % 60;
        const formattedPauseTime = `${hours}h ${minutes}m ${seconds}s`;
        
        document.getElementById('pauses-summary').innerHTML = `
          <div class="pause-summary-container">
            <h3 class="pause-summary-title">Resumen de Pausas</h3>
            <table class="pause-summary-table">
              <tr>
                <td class="pause-summary-label">Total de Pausas:</td>
                <td class="pause-summary-value">${allPauses.length}</td>
              </tr>
              <tr>
                <td class="pause-summary-label">Tiempo Total en Pausa:</td>
                <td class="pause-summary-value">${formattedPauseTime}</td>
              </tr>
              <tr>
                <td class="pause-summary-label">Pausas Activas:</td>
                <td class="pause-summary-value">${activePauses.length}</td>
              </tr>
            </table>
          </div>
        `;
        
        if (activePauses.length === 0) {
          activePausesList.innerHTML = 'No hay pausas activas';
        } else {
          activePausesList.innerHTML = `
            <table class="pause-table">
              <thead>
                <tr>
                  <th>Razón</th>
                  <th>Inicio</th>
                  <th>Tiempo</th>
                  <th class="right">Estado</th>
                </tr>
              </thead>
              <tbody> 
                ${activePauses.map(pause => {
                  const startTime = new Date(pause.start_time);
                  const formattedTime = startTime.toLocaleTimeString('es-HN', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true 
                  });
                  return `
                    <tr>
                      <td>${reasons[pause.reason]|| 'Sin razón'}</td>
                      <td>${formattedTime}</td>
                      <td>
                        <span class="elapsed-time" data-start="${pause.start_time}">${formatDuration(pause.start_time)}</span>
                      </td>
                      <td class="center pause-active">
                        En progreso
                      </td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          `;
        }
        
        const historicalPauses = allPauses.filter(pause => pause.end_time !== null)
          .sort((a, b) => new Date(b.start_time) - new Date(a.start_time));
        
        if (historicalPauses.length === 0) {
          pausesHistoryList.innerHTML = 'No hay historial de pausas';
        } else {
          pausesHistoryList.innerHTML = `
            <table class="pause-table">
              <thead>
                <tr>
                  <th>Razón</th>
                  <th>Inicio</th>
                  <th>Fin</th>
                  <th>Duración</th>
                  <th class="right">Estado</th>
                </tr>
              </thead>
              <tbody>
                ${historicalPauses.map(pause => {
                  const startTime = new Date(pause.start_time);
                  const endTime = new Date(pause.end_time);
                  const durationMs = endTime - startTime;
                  // Use Math.round() for more accurate second calculation
                  const totalSeconds = Math.round(durationMs / 1000);
                  const hours = Math.floor(totalSeconds / 3600);
                  const minutes = Math.floor((totalSeconds % 3600) / 60);
                  const seconds = totalSeconds % 60;
                  const reasonText = reasons[pause.reason] || pause.reason;
                  return `
                    <tr>
                      <td>${reasonText || 'Sin razón'}</td>
                      <td>
                        ${startTime.toLocaleTimeString('es-HN', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
                      </td>
                      <td>
                        ${endTime.toLocaleTimeString('es-HN', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
                      </td>
                     
                      <td>${hours}h ${minutes}m ${seconds}s</td>
                      <td class="right pause-completed">Completada</td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          `;
        }
        
      } catch (error) {
        console.error('Error loading pauses:', error);
        activePausesList.innerHTML = 'Error al cargar las pausas';
        pausesHistoryList.innerHTML = 'Error al cargar el historial';
      } finally {
        feather.replace();
        updateElapsedTimes();
      }
    }
    
    window.onclick = function(event) {
      const modal = document.getElementById('pauses-modal');
      if (event.target === modal) {
        closeModal();
      }
    };
  </script>
  <script>
    feather.replace();
  </script>
</body>
</html>