const API_URL = 'api';
const reasons = {
  lunch: 'Almuerzo',
  bathroom_outside: 'Baño afuera',
  bathroom_office: 'Baño oficina',
  break: 'Break 15 minutos'
};

let currentPause = null;

// Función para obtener el ID del empleado desde el almacenamiento local
function getCurrentEmployeeId() {
  // Intentar obtener los datos del usuario del almacenamiento local
  const userData = localStorage.getItem('currentUser');
  if (userData) {
    try {
      const user = JSON.parse(userData);
      console.log('Datos del usuario encontrados:', user);
      // Asegurarse de que el usuario tenga un ID
      if (user && user.id) {
        return user.id;
      } else {
        console.error('El objeto de usuario no tiene un ID válido:', user);
      }
    } catch (e) {
      console.error('Error al analizar los datos del usuario:', e);
    }
  } else {
    console.log('No se encontraron datos de usuario en localStorage');  }
  
  // Si llegamos aquí, algo salió mal
  console.error('No se pudo obtener el ID del empleado');
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
  startTime.setHours(startTime.getHours() - 6); // Ajustar a hora de Honduras (CST, UTC-6)
  
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
      // Guardar la pausa actual con el ID devuelto por el servidor
      currentPause = {
        id: data.pause_id,
        reason: reason,
        start_time: startTime.toISOString(),
        status: 'in_progress'
      };
      
      document.getElementById('start-pause').disabled = true;
      document.getElementById('stop-pause').disabled = false;
      document.getElementById('reason').disabled = true;
      
      // Actualizar la lista de pausas
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
  endTime.setHours(endTime.getHours() - 6); // Ajustar a hora de Honduras (CST, UTC-6)

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
      // Resetear controles
      currentPause = null;
      document.getElementById('start-pause').disabled = false;
      document.getElementById('stop-pause').disabled = true;
      document.getElementById('reason').value = '';
      document.getElementById('reason').disabled = false;
      
      // Actualizar la lista de pausas
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
    // Añadir transición de desvanecimiento
    loaderOverlay.style.opacity = '0';
    // Esperar a que termine la transición antes de ocultar
    setTimeout(() => {
      loaderOverlay.style.display = 'none';
    }, 300); // Debe coincidir con la duración de la transición CSS
  }
}

// Función para formatear la fecha a YYYY-MM-DD
function formatDate(date) {
  const d = new Date(date);
  return d.toISOString().split('T')[0];
}

// Función para calcular la diferencia en minutos entre dos fechas
function getMinutesDiff(start, end) {
  return Math.round((new Date(end) - new Date(start)) / 60000);
}

// Función para formatear minutos a HH:MM
function formatMinutesToHHMM(minutes) {
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
}

async function fetchPauses() {
  try {
    // Mostrar loader y ocultar controles
    showLoading(true);
    
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    
    const response = await fetch(`${API_URL}/get_pauses.php?employee_id=${currentUser.id}&start_date=${startDate}&end_date=${endDate}`);
    
    if (!response.ok) {
      throw new Error('Error en la red al cargar las pausas');
    }
    
    const data = await response.json();
    
    if (!data.success) {
      throw new Error(data.message || 'Error al cargar las pausas');
    }
    
    const pauses = data.data || [];
    const pauseList = document.getElementById('pause-list');
    pauseList.innerHTML = '';
    
    // Verificar si hay pausas activas (sin end_time)
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
    
    // Mostrar controles después de verificar el estado de las pausas
    showLoading(false);
    
    // Ordenar pausas por fecha de inicio (más recientes primero)
    pauses.sort((a, b) => new Date(b.start_time) - new Date(a.start_time));
    
    // Calcular el tiempo total de pausas en segundos
    let totalPauseSeconds = 0;
    
    // Agrupar pausas por fecha
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
    
    // Mostrar pausas agrupadas por fecha
    for (const [date, dailyPauses] of Object.entries(pausesByDate)) {
      const dateHeader = document.createElement('h3');
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
        
          // Calcular tiempo total solo para pausas completadas
          if (pause.end_time) {
            const start = new Date(pause.start_time);
            const end = new Date(pause.end_time);
            const duration = Math.round((end - start) / 1000); // en segundos
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
    
    // Actualizar el total de tiempo en pausas
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
    
    // Asegurarse de que los controles se muestren incluso si hay un error
    showLoading(false);
  }
}

// Función para formatear la fecha en formato YYYY-MM-DD en la zona horaria de Honduras (UTC-6)
function getLocalDateString(date) {
  // Crear una fecha en la zona horaria local
  const d = new Date(date);
  
  // Obtener los componentes de fecha en la zona horaria local
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  
  // Crear una cadena de fecha en formato YYYY-MM-DD
  const localDateStr = `${year}-${month}-${day}`;
  
  // Si la fecha es la de hoy, verificar si necesitamos ajustar por la zona horaria
  const today = new Date();
  if (d.toDateString() === today.toDateString()) {
    // Obtener la diferencia de horas entre la zona local y Honduras (UTC-6)
    const localOffset = today.getTimezoneOffset(); // en minutos
    const hondurasOffset = 360; // 6 horas * 60 minutos
    const totalOffset = (hondurasOffset - localOffset) * 60000; // en milisegundos
    
    // Ajustar la fecha
    const adjustedDate = new Date(today.getTime() + totalOffset);
    return adjustedDate.toISOString().split('T')[0];
  }
  
  return localDateStr;
}

// Variable para controlar si ya hay una petición en curso
let isFiltering = false;

// Función para manejar el filtrado
function handleFilter(event) {
  // Si ya hay una petición en curso, no hacer nada
  if (isFiltering) {
    event.preventDefault();
    return;
  }
  
  // Prevenir el comportamiento por defecto del formulario
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  
  // Marcar que hay una petición en curso
  isFiltering = true;
  
  // Deshabilitar el botón para evitar múltiples clics
  const filterButton = document.querySelector('.filter-button');
  if (filterButton) {
    filterButton.disabled = true;
    filterButton.textContent = 'Filtrando...';
  }
  
  const startDate = document.getElementById('start-date').value;
  const endDate = document.getElementById('end-date').value;
  
  // Validar que la fecha de inicio no sea mayor que la de fin
  if (new Date(startDate) > new Date(endDate)) {
    alert('La fecha de inicio no puede ser mayor que la fecha de fin');
    document.getElementById('end-date').value = startDate;
    if (filterButton) {
      filterButton.disabled = false;
      filterButton.textContent = 'Filtrar';
    }
    isFiltering = false;
    return;
  }
  
  // Usar setTimeout para asegurar que el botón se deshabilite antes de la petición
  setTimeout(() => {
    fetchPauses().finally(() => {
      // Re-habilitar el botón después de completar la petición
      if (filterButton) {
        filterButton.disabled = false;
        filterButton.textContent = 'Filtrar';
      }
      isFiltering = false;
    });
  }, 100);
}

// Variable para rastrear si ya se ha cargado la página
let pageInitialized = false;

document.addEventListener('DOMContentLoaded', () => {
  // Si ya se inicializó la página, no hacer nada
  if (pageInitialized) return;
  
  // Obtener fecha actual en la zona horaria de Honduras
  const today = getLocalDateString(new Date());
  
  // Establecer fechas por defecto
  const startDateInput = document.getElementById('start-date');
  const endDateInput = document.getElementById('end-date');
  
  // Solo establecer valores si los campos están vacíos
  if (!startDateInput.value) {
    startDateInput.value = today;
  }
  if (!endDateInput.value) {
    endDateInput.value = today;
  }
  
  // Agregar event listener solo al formulario de filtrado
  const filterForm = document.querySelector('.range-container');
  if (filterForm) {
    // Remover cualquier event listener previo para evitar duplicados
    filterForm.removeEventListener('submit', handleFilter);
    filterForm.addEventListener('submit', handleFilter);
    
    // Cargar pausas iniciales solo si es la primera vez
    if (!pageInitialized) {
      fetchPauses();
      pageInitialized = true;
    }
  }
  
  // Deshabilitar el envío del formulario al presionar Enter en los campos de fecha
  const dateInputs = document.querySelectorAll('input[type="date"]');
  dateInputs.forEach(input => {
    // Remover event listeners previos para evitar duplicados
    input.removeEventListener('keydown', handleDateInputKeydown);
    input.addEventListener('keydown', handleDateInputKeydown);
  });
});

// Manejador separado para el evento keydown de los inputs de fecha
function handleDateInputKeydown(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    handleFilter(e);
  }
}