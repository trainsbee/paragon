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
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
  }
  
  .container {
    max-width: 1000px;
    margin: 0 auto;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px 12px;
    text-align: left;
  }
  
  th {
    background-color: #f2f2f2;
  }
  
  .btn {
    padding: 4px 8px;
    text-decoration: none;
    border: 1px solid #ccc;
    border-radius: 3px;
    cursor: pointer;
    font-size: 13px;
  }
  
  .modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
  }
  
  .modal-content {
    background: white;
    margin: 50px auto;
    padding: 20px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
  }
  
  .close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
  }
  
  /* Estilos extraídos de elementos inline */
  .date-filter {
    margin: 20px 0;
  }
  
  .date-filter label {
    margin-left: 10px;
  }
  
  .date-filter button {
    margin-left: 10px;
  }
  
  .active-pauses-container {
    margin: 20px 0;
  }
  
  .employees-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  
  .employees-table th {
    text-align: left;
    padding: 8px;
    border-bottom: 1px solid #ddd;
  }
  
  .employees-table th.center {
    text-align: center;
  }
  
  .employees-table th.right {
    text-align: right;
  }
  
  .employees-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
  }
  
  .employees-table td.center {
    text-align: center;
  }
  
  .employees-table td.right {
    text-align: right;
  }
  
  .active-pause {
    color: #dc3545;
    font-weight: 500;
  }
  
  .inactive-pause {
    color: #28a745;
    font-weight: 500;
  }
  
  .monospace {
    font-family: monospace;
  }
  
  .icon-button {
    padding: 4px 8px;
    font-size: 13px;
  }
  
  .icon-small {
    width: 14px;
    height: 14px;
  }
  
  .modal-header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .modal-title {
    margin: 0;
  }
  
  .modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
  }
  
  .pauses-summary {
    margin-bottom: 20px;
  }
  
  .history-container {
    margin-top: 20px;
  }
  
  .overflow-auto {
    overflow-x: auto;
  }
  
  .active-pauses-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  
  .active-pauses-table th {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    background-color: #f2f2f2;
  }
  
  .active-pauses-table td {
    padding: 10px;
    border-bottom: 1px solid #eee;
  }
  
  .clickable-row {
    cursor: pointer;
  }
  
  .clickable-row:hover {
    background-color: #f9f9f9;
  }
  
  .loading-cell {
    text-align: center;
    padding: 15px;
  }
  
  .error-cell {
    text-align: center;
    color: #dc3545;
  }
  
  .error-text {
    color: red;
  }
  
  .pause-summary-container {
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    padding: 15px;
    background-color: #f9f9f9;
  }
  
  .pause-summary-title {
    margin-top: 0;
    color: #333;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 10px;
    margin-bottom: 15px;
  }
  
  .pause-summary-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .pause-summary-table td {
    padding: 8px;
    border-bottom: 1px solid #e0e0e0;
  }
  
  .pause-summary-label {
    font-weight: 500;
    width: 60%;
  }
  
  .pause-summary-value {
    text-align: right;
    font-weight: 500;
  }
  
  .pause-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  
  .pause-table th {
    text-align: left;
    padding: 8px;
    border-bottom: 1px solid #ddd;
  }
  
  .pause-table th.right {
    text-align: right;
  }
  
  .pause-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
  }
  
  .pause-table td.right {
    text-align: right;
  }
  
  .pause-table td.center {
    text-align: center;
  }
  
  .pause-active {
    color: #28a745;
    font-weight: 500;
  }
  
  .pause-completed {
    color: #6c757d;
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