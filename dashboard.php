<?php require_once 'settings.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pausa App</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <script>
    const router = "<?php echo BASE_URL; ?>";
    console.log(router);
  </script>
  
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
            <a href="#" onclick="logout()"><i data-feather="log-out"></i> <span>Salir</span></a>
        </div>

        <button class="menu-toggle" id="menuToggle">
            <i data-feather="menu"></i>
        </button>
    </nav>
<div class="admin-content">
            <div class="card pauses">
               
                  <div class="form-container form-switch">
            <div class="form-header">
                <h2><i data-feather="pause"></i> Administrador de Pausas</h2>
            </div>
            <div class="form-body">
                <p id="message-time-pause" style="font-size: 0.875rem; margin-bottom: 1rem;">
                    Puedes crear tus pausas aquí, recuerda organizarte bien para que no te quedes sin pausa (:
                </p>
            </div>
            <div class="form-footer" id="footer-switch">
               <div class="form-group">
                    <select>
                        <option>Opción 1</option>
                        <option>Opción 2</option>
                        <option>Opción 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
                   
                </div>
                <div class="profile time-pauses">
                <h3>Stadistics</h3>
                <div class="info-row">
                    <div class="box">
                      <h4>12</h4>
                      <p>Pauses</p>
                    </div>
                    <div class="box">
                      <h4>12</h4>
                      <p>Pauses</p>
                    </div>
                    <div class="box">
                      <h4>12</h4>
                      <p>Pauses</p>
                    </div>
                </div>
            </div>
             <div class="profile">
                <h3>Pausas 17 de octubre</h3>
                <div class="info-row">
                    <span class="info-label">Razón:</span>
                    <span class="info-value">Break 15 minutos</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">17 de octubre - 18 de octubre</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hora:</span>
                    <span class="info-value">12:00</span>
                </div>
              </div>
              <div class="profile">
                <h3>Pausas 16 de octubre</h3>
                <div class="info-row">
                    <span class="info-label">Razón:</span>
                    <span class="info-value">Break 15 minutos</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value">17 de octubre - 18 de octubre</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hora:</span>
                    <span class="info-value">12:00</span>
                </div>
              </div>

            </div>
            <div class="card">
                <h3>Actividad <span id="user-name"></span> <span id="user-role"></span><span id="user-department"></span></h3>
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
        
        <div class="switch-container">
          <span class="switch-label">Control de Pausa:</span>
          <label class="switch">
            <input type="checkbox" id="pause-switch" onchange="togglePause()">
            <span class="slider"></span>
          </label>
          <span id="switch-status" class="switch-status inactive">Inactiva</span>
        </div>
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
    <!-- <div class="total-pause-time-two">
      <span id="total-pause-time-two">Total de tiempo de pausas: </span>
    </div> -->

    <div id="pause-list"></div>
  </div>

  <script>
    feather.replace();

    const API_URL = router + 'api';
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

    async function togglePause() {
      const pauseSwitch = document.getElementById('pause-switch');
      const isChecked = pauseSwitch.checked;

      if (isChecked) {
        // Iniciar pausa
        await startPause();
      } else {
        // Detener pausa
        await stopPause();
      }
    }

    async function startPause() {
      const reason = document.getElementById('reason').value;
      if (!reason) {
        alert('Por favor, selecciona una razón');
        // Revertir el switch si no hay razón seleccionada
        document.getElementById('pause-switch').checked = false;
        return;
      }

      const employeeId = getCurrentEmployeeId();
      if (!employeeId) {
        alert('Error: No se pudo identificar al empleado. Por favor, inicia sesión nuevamente.');
        document.getElementById('pause-switch').checked = false;
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

          updateSwitchState(true);
          await fetchPauses();
        } else {
          throw new Error(data.message || 'Error al iniciar la pausa');
        }
      } catch (error) {
        console.error('Error al iniciar la pausa:', error);
        alert('Error al iniciar la pausa: ' + error.message);
        // Revertir el switch en caso de error
        document.getElementById('pause-switch').checked = false;
      }
    }

    async function stopPause() {
      if (!currentPause) {
        console.error('No hay pausa activa para detener');
        document.getElementById('pause-switch').checked = true;
        return;
      }

      const employeeId = getCurrentEmployeeId();
      if (!employeeId) {
        alert('Error: No se pudo identificar al empleado. Por favor, inicia sesión nuevamente.');
        document.getElementById('pause-switch').checked = true;
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
          updateSwitchState(false);
          await fetchPauses();
        } else {
          throw new Error(data.message || 'Error al detener la pausa');
        }
      } catch (error) {
        console.error('Error al detener la pausa:', error);
        alert('Error al detener la pausa: ' + error.message);
        // Revertir el switch en caso de error
        document.getElementById('pause-switch').checked = true;
      }
    }

    function updateSwitchState(isActive) {
      const pauseSwitch = document.getElementById('pause-switch');
      const switchStatus = document.getElementById('switch-status');
      const reasonSelect = document.getElementById('reason');

      pauseSwitch.checked = isActive;
      
      if (isActive) {
        switchStatus.textContent = 'Activa';
        switchStatus.className = 'switch-status active';
        reasonSelect.disabled = true;
      } else {
        switchStatus.textContent = 'Inactiva';
        switchStatus.className = 'switch-status inactive';
        reasonSelect.disabled = false;
        reasonSelect.value = '';
      }
    }

    function updatePauseControls(hasActivePause) {
      updateSwitchState(hasActivePause);
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
        const totalPauseElementTwo = document.getElementById('message-time-pause');
        const controlsContent = document.getElementById('controls-content');

        console.log(importantPauses);

        if (totalSeconds < 15 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two clr-success';
          totalPauseElementTwo.textContent = `Hoy has estado en pausa: ${totalPauseTime} - Excelente`;
          controlsContent.style.display = 'flex';
        } else if (totalSeconds >= 15 * 60 && totalSeconds < 40 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two clr-warning';
          totalPauseElementTwo.textContent = `Tu tiempo de pausa ha llegado a: ${totalPauseTime} - Cuida tu tiempo de pausa`;
          controlsContent.style.display = 'flex';
        } else if (totalSeconds >= 30 * 60) {
          totalPauseElementTwo.className = 'total-pause-time-two clr-danger';
          totalPauseElementTwo.textContent = `Excediste el tiempo de pausa: ${totalPauseTime} - Por favor, detén la pausa`;
          controlsContent.style.display = 'none';
          document.getElementById('footer-switch').style.display = 'none';
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
      window.location.href = router + 'auth.php';
    }
  </script>
</body>
</html>