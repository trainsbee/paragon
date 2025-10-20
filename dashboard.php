<?php require_once 'settings.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pausa App</title>
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
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
  </style>
</head>
<body>
  <nav>
    <div class="nav-left">
      <h1>Pausa App</h1>
    </div>
    <div class="user-info">
      <span id="user-name"></span>
      <span id="user-role"></span>
      <span id="user-department"></span>
      <button class="logout-btn" onclick="logout()">
        <i data-feather="log-out" style="width: 1rem; height: 1rem;"></i>
        Cerrar Sesión
      </button>
    </div>
  </nav>

  <div class="container">
    <div id="pause-controls" class="pause-controls">
      <div class="loader-overlay" style="display: flex;">
        <div class="loader-container">
          <div class="loader"></div>
          <span class="loader-text">Cargando pausas...</span>
        </div>
      </div>
      <div class="controls-content" id="controls-content">
        <select id="reason" required>
          <option value="" disabled selected>Selecciona una razón</option>
          <option value="break">Break 15 minutos</option>
          <option value="lunch">Almuerzo</option>
          <option value="bathroom_outside">Baño afuera</option>
          <option value="bathroom_office">Baño oficina</option>
          <option value="meeting_manager">Reunión con gerente</option>
          <option value="meeting_rrhh">Reunión con RRHH</option>
          <option value="meeting_country_manager">Reunión con gerente de país</option>
        </select>
        <button id="start-pause" onclick="startPause()">
          <i data-feather="play" style="width: 1rem; height: 1rem;"></i>
          Iniciar Pausa
        </button>
        <button id="stop-pause" onclick="stopPause()" disabled>
          <i data-feather="square" style="width: 1rem; height: 1rem;"></i>
          Detener Pausa
        </button>
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
      <button type="submit" class="filter-button">
        <i data-feather="filter" style="width: 1rem; height: 1rem;"></i>
        Filtrar
      </button>
    </form>
    <div class="total-pause-time">
      <span id="total-pause-time">Total de pausas: </span>
    </div>
    <div class="total-pause-time-two">
      <span id="total-pause-time-two">Total de tiempo de pausas: </span>
    </div>

    <div id="pause-list"></div>
  </div>

  <script>
    feather.replace();

    const API_URL = 'api';
    const reasons = {
      break: 'Break 15 minutos',
      lunch: 'Almuerzo',
      bathroom_outside: 'Baño afuera',
      bathroom_office: 'Baño oficina',
      meeting_manager: 'Reunión con gerente',
      meeting_rrhh: 'Reunión con RRHH',
      meeting_country_manager: 'Reunión con gerente de país',
    };

    let currentPause = null;
    let currentUser = null;

    function getCurrentEmployeeId() {
      if (!currentUser) {
        console.error('No se encontraron datos de usuario');
        return null;
      }
      if (currentUser.id) {
        return currentUser.id;
      }
      console.error('El objeto de usuario no tiene un ID válido:', currentUser);
      return null;
    }

    async function startPause() {
      const reason = document.getElementById('reason').value;
      if (!reason) {
        alert('Por favor, selecciona una razón');
        return;
      }

      const employeeId = getCurrentEmployeeId();
      if (!employeeId) {
        alert('Error: No se pudo identificar al empleado. Por favor, inicia sesión nuevamente.');
        window.location.href = 'login.html';
        return;
      }

      const startTime = new Date();
      startTime.setHours(startTime.getHours() - 6);

      try {
        const response = await fetch(`${API_URL}/save_pause.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            employee_id: employeeId,
            reason: reason,
            start_time: startTime.toISOString()
          })
        });

        const data = await response.json();

        if (data.success) {
          currentPause = {
            id: data.pause_id,
            reason: reason,
            start_time: startTime.toISOString(),
            status: 'in_progress'
          };

          document.getElementById('start-pause').disabled = true;
          document.getElementById('stop-pause').disabled = false;
          document.getElementById('reason').disabled = true;

          await fetchPauses();
        } else {
          throw new Error(data.message || 'Error al iniciar la pausa');
        }
      } catch (error) {
        console.error('Error al iniciar la pausa:', error);
        alert('Error al iniciar la pausa: ' + error.message);
      }
    }

    async function stopPause() {
      if (!currentPause) {
        console.error('No hay pausa activa para detener');
        return;
      }

      const employeeId = getCurrentEmployeeId();
      if (!employeeId) {
        alert('Error: No se pudo identificar al empleado. Por favor, inicia sesión nuevamente.');
        window.location.href = 'login.html';
        return;
      }

      const endTime = new Date();
      endTime.setHours(endTime.getHours() - 6);

      try {
        const requestData = {
          employee_id: employeeId,
          end_time: endTime.toISOString()
        };

        console.log('Sending stop request with data:', requestData);

        const response = await fetch(`${API_URL}/save_pause.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(requestData)
        });

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        if (!responseText) {
          throw new Error('Respuesta vacía del servidor');
        }

        const data = JSON.parse(responseText);

        if (data.success) {
          currentPause = null;
          document.getElementById('start-pause').disabled = false;
          document.getElementById('stop-pause').disabled = true;
          document.getElementById('reason').value = '';
          document.getElementById('reason').disabled = false;

          await fetchPauses();
        } else {
          throw new Error(data.message || 'Error al detener la pausa');
        }
      } catch (error) {
        console.error('Error al detener la pausa:', error);
        alert('Error al detener la pausa: ' + error.message);
      }
    }

    function updatePauseControls(hasActivePause) {
      const startBtn = document.getElementById('start-pause');
      const stopBtn = document.getElementById('stop-pause');
      const reasonSelect = document.getElementById('reason');

      if (hasActivePause) {
        startBtn.disabled = true;
        stopBtn.disabled = false;
        reasonSelect.disabled = true;
        stopBtn.classList.add('active');
      } else {
        startBtn.disabled = false;
        stopBtn.disabled = true;
        reasonSelect.disabled = false;
        stopBtn.classList.remove('active');
      }
    }

    function showLoading(show) {
      const loaderOverlay = document.querySelector('.loader-overlay');
      if (show) {
        loaderOverlay.style.display = 'flex';
        loaderOverlay.style.opacity = '1';
      } else {
        loaderOverlay.style.opacity = '0';
        setTimeout(() => {
          loaderOverlay.style.display = 'none';
        }, 300);
      }
    }

    function formatDate(date) {
      const d = new Date(date);
      return d.toISOString().split('T')[0];
    }

    function getSecondsDiff(start, end) {
      const startDate = new Date(start);
      const endDate = new Date(end);
      return (endDate - startDate) / 1000;
    }

    async function fetchPauses() {
      if (!currentUser) {
        console.error('Usuario no definido, no se pueden cargar las pausas');
        showLoading(false);
        const pauseList = document.getElementById('pause-list');
        pauseList.innerHTML = '<div class="error">Error: Sesión no iniciada. Por favor, inicia sesión nuevamente.</div>';
        window.location.href = 'auth.php';
        return;
      }

      try {
        showLoading(true);

        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;

        const response = await fetch(`${API_URL}/get_pauses.php?employee_id=${currentUser.id}&start_date=${startDate}&end_date=${endDate}`);

        if (!response.ok) {
          throw new Error('Error en la red al cargar las pausas');
        }

        const data = await response.json();

        const importantPauses = data.data.filter(pause =>
          pause.reason === 'bathroom_outside' || pause.reason === 'break' || pause.reason === 'bathroom_office'
        );

        const hondurasDate = new Date().toLocaleDateString("es-HN", {
          timeZone: "America/Tegucigalpa",
          year: "numeric",
          month: "2-digit",
          day: "2-digit"
        });
        const parts = hondurasDate.split("/");
        const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

        const todayPauses = importantPauses.filter(pause =>
          pause.end_time != null &&
          pause.start_time.startsWith(formattedDate)
        );

        const totalSeconds = todayPauses.reduce(
          (total, pause) => total + Math.round(getSecondsDiff(pause.start_time, pause.end_time)),
          0
        );

        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = Math.floor(totalSeconds % 60);

        const totalPauseTime = `${hours}h ${minutes}m ${seconds}s`;
        const totalPauseElementTwo = document.getElementById('total-pause-time-two');
        const controlsContent = document.getElementById('controls-content');

        if (totalSeconds < 15 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two green';
          totalPauseElementTwo.textContent = `Hoy has estado en pausa: ${totalPauseTime} - Excelente`;
          controlsContent.style.display = 'flex';
        } else if (totalSeconds >= 15 * 60 && totalSeconds < 30 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two orange';
          totalPauseElementTwo.textContent = `Tu tiempo de pausa ha llegado a: ${totalPauseTime} - Cuida tu tiempo de pausa`;
          controlsContent.style.display = 'flex';
        } else if (totalSeconds >= 30 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two red';
          totalPauseElementTwo.textContent = `Excediste el tiempo de pausa: ${totalPauseTime} - Por favor, detén la pausa`;
          controlsContent.style.display = 'none';
        }

        if (!data.success) {
          throw new Error(data.message || 'Error al cargar las pausas');
        }

        const pauses = data.data || [];
        const pauseList = document.getElementById('pause-list');
        pauseList.innerHTML = '';

        const activePause = pauses.find(pause => !pause.end_time);
        if (activePause) {
          currentPause = {
            id: activePause.pause_id,
            reason: activePause.reason,
            start_time: activePause.start_time,
            status: 'in_progress'
          };
          updatePauseControls(true);
        } else {
          currentPause = null;
          updatePauseControls(false);
        }

        showLoading(false);

        pauses.sort((a, b) => new Date(b.start_time) - new Date(a.start_time));

        let totalPauseSeconds = 0;

        const pausesByDate = {};
        pauses.forEach(pause => {
          const date = new Date(pause.start_time).toLocaleDateString('es-HN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'America/Tegucigalpa'
          });

          if (!pausesByDate[date]) {
            pausesByDate[date] = [];
          }
          pausesByDate[date].push(pause);
        });

        for (const [date, dailyPauses] of Object.entries(pausesByDate)) {
          const dateHeader = document.createElement('div');
          dateHeader.className = 'date-header';
          dateHeader.textContent = date;
          pauseList.appendChild(dateHeader);

          dailyPauses.forEach(pause => {
            const card = document.createElement('div');
            card.className = `card ${!pause.end_time ? 'in-progress' : ''}`;

            let endText = '';
            let durationText = '';

            if (pause.end_time) {
              endText = new Date(pause.end_time).toLocaleTimeString('es-HN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'America/Tegucigalpa'
              });

              const duration = Math.round((new Date(pause.end_time) - new Date(pause.start_time)) / 1000);
              const hours = Math.floor(duration / 3600);
              const minutes = Math.floor((duration % 3600) / 60);
              const seconds = duration % 60;

              durationText = [
                hours > 0 ? `${hours}h` : '',
                minutes > 0 ? `${minutes}m` : '',
                `${seconds}s`
              ].filter(Boolean).join(' ');

              if (pause.end_time) {
                const start = new Date(pause.start_time);
                const end = new Date(pause.end_time);
                const duration = Math.round((end - start) / 1000);
                totalPauseSeconds += duration;
              }
            }

            const reasonText = reasons[pause.reason] || pause.reason;
            const startTime = new Date(pause.start_time).toLocaleTimeString('es-HN', {
              hour: '2-digit',
              minute: '2-digit',
              second: '2-digit',
              hour12: false,
              timeZone: 'America/Tegucigalpa'
            });

            card.innerHTML = `
              <div class="pause-info">
                <div><span class="label">Razón:</span> ${reasonText}</div>
                <div><span class="label">Inicio:</span> ${startTime} - ${pause.end_time ? endText : 'En curso'}</div>
                ${pause.end_time ? `<div><span class="label">Duración:</span> ${durationText}</div>` : ''}
              </div>
            `;

            pauseList.appendChild(card);
          });
        }

        const totalPauseElement = document.getElementById('total-pause-time');
        if (totalPauseSeconds > 0) {
          const hours = Math.floor(totalPauseSeconds / 3600);
          const minutes = Math.floor((totalPauseSeconds % 3600) / 60);
          const seconds = totalPauseSeconds % 60;

          let timeString = [];
          if (hours > 0) timeString.push(`${hours}h`);
          if (minutes > 0 || hours > 0) timeString.push(`${minutes}m`);
          timeString.push(`${seconds}s`);

          totalPauseElement.textContent = `Tiempo total en pausas: ${timeString.join(' ')}`;
        } else {
          totalPauseElement.textContent = 'No hay pausas registradas en el rango de fechas';
        }
      } catch (error) {
        console.error('Error fetching pauses:', error);
        const pauseList = document.getElementById('pause-list');
        pauseList.innerHTML = '<div class="error">Error al cargar las pausas. Intente de nuevo más tarde.</div>';
        showLoading(false);
      }
    }

    function getLocalDateString(date) {
      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      const localDateStr = `${year}-${month}-${day}`;

      const today = new Date();
      if (d.toDateString() === today.toDateString()) {
        const localOffset = today.getTimezoneOffset();
        const hondurasOffset = 360;
        const totalOffset = (hondurasOffset - localOffset) * 60000;
        const adjustedDate = new Date(today.getTime() + totalOffset);
        return adjustedDate.toISOString().split('T')[0];
      }

      return localDateStr;
    }

    let isFiltering = false;

    function handleFilter(event) {
      if (isFiltering) {
        event.preventDefault();
        return;
      }

      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }

      isFiltering = true;

      const filterButton = document.querySelector('.filter-button');
      if (filterButton) {
        filterButton.disabled = true;
        filterButton.innerHTML = '<i data-feather="filter" style="width: 1rem; height: 1rem;"></i> Filtrando...';
        feather.replace();
      }

      const startDate = document.getElementById('start-date').value;
      const endDate = document.getElementById('end-date').value;

      if (new Date(startDate) > new Date(endDate)) {
        alert('La fecha de inicio no puede ser mayor que la fecha de fin');
        document.getElementById('end-date').value = startDate;
        if (filterButton) {
          filterButton.disabled = false;
          filterButton.innerHTML = '<i data-feather="filter" style="width: 1rem; height: 1rem;"></i> Filtrar';
          feather.replace();
        }
        isFiltering = false;
        return;
      }

      setTimeout(() => {
        fetchPauses().finally(() => {
          if (filterButton) {
            filterButton.disabled = false;
            filterButton.innerHTML = '<i data-feather="filter" style="width: 1rem; height: 1rem;"></i> Filtrar';
            feather.replace();
          }
          isFiltering = false;
        });
      }, 100);
    }

    let pageInitialized = false;

    document.addEventListener('DOMContentLoaded', () => {
      if (pageInitialized) return;

      currentUser = JSON.parse(localStorage.getItem('currentUser'));

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

      const today = getLocalDateString(new Date());
      const startDateInput = document.getElementById('start-date');
      const endDateInput = document.getElementById('end-date');

      if (!startDateInput.value) {
        startDateInput.value = today;
      }
      if (!endDateInput.value) {
        endDateInput.value = today;
      }

      const filterForm = document.querySelector('.range-container');
      if (filterForm) {
        filterForm.removeEventListener('submit', handleFilter);
        filterForm.addEventListener('submit', handleFilter);

        if (!pageInitialized) {
          fetchPauses();
          pageInitialized = true;
        }
      }

      const dateInputs = document.querySelectorAll('input[type="date"]');
      dateInputs.forEach(input => {
        input.removeEventListener('keydown', handleDateInputKeydown);
        input.addEventListener('keydown', handleDateInputKeydown);
      });
    });

    function handleDateInputKeydown(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        handleFilter(e);
      }
    }

    function logout() {
      localStorage.removeItem('currentUser');
      window.location.href = 'auth.php';
    }
  </script>
</body>
</html>