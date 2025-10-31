<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión de Oportunidades</title>
</head>
<body>

<div>
  <!-- Navbar Tabs -->
  <div>
    <button data-tab="tab-resumen" class="tab-btn active">Resumen por Agente</button>
    <button data-tab="tab-subir" class="tab-btn">Subir Oportunidades</button>
    <button data-tab="tab-mostrar" class="tab-btn">Mostrar Oportunidades</button>
  </div>

  <!-- Tab: Resumen por Agente -->
  <div id="tab-resumen" class="tab-panel">
    <section>
      <h2>Resumen de Oportunidades por Agente</h2>
      <label>Fecha Inicio: <input type="date" id="fechaInicio"></label>
      <label>Fecha Fin: <input type="date" id="fechaFin"></label>
      <button onclick="cargarResumenAgentes()">Buscar</button>
      <div id="resumenAgentes"></div>
    </section>
    <section>
      <div class="sales-spanish">
        <input type="text" id="sales-spanish">
      </div>
      <div class="sales-english">
        <input type="text" id="sales-english">
      </div>
    </section>
    <section>
    
    <table>
      <tr>
        <th>Tag</th>
        <th>Total Deals</th>
        <th>Opportunities</th>
        <th>%</th>
      </tr>
      <tr>
        <td>OFFICE</td>
        <td>5</td>
        <td>16</td>
        <td>31.25%</td>
      </tr>
      <tr>
        <td>ENGLISH</td>
        <td><input type="text" id="sales-english"></td>
        <td id="total-opportunities-english">5</td>
        <td>40.00%</td>
      </tr>
      <tr>
        <td>SPANISH</td>
        <td><input type="text" id="sales-spanish"></td>
        <td id="total-opportunities-spanish">11</td>
        <td>27.27%</td>
      </tr>
    </table>
    </section>
  </div>

  <!-- Tab: Subir Oportunidades -->
  <div id="tab-subir" class="tab-panel">
    <section>
      <h2>Agregar Oportunidades</h2>
      <label>Fecha: <input type="date" id="Date"></label>
      <label>Hora: <input type="time" id="Time"></label>
      <br><br>
      <textarea id="inputData" rows="10" cols="100" placeholder="Pega aquí los datos (separados por tabulaciones)..." disabled></textarea>
      <br><br>
      <button onclick="generarTabla()">Generar Vista Previa</button>
      <button onclick="guardarEnSupabase()">Guardar en Supabase</button>
      <div id="resultado"></div>
    </section>
  </div>

  <!-- Tab: Mostrar Oportunidades -->
  <div id="tab-mostrar" class="tab-panel">
    <section>
      <h2>Oportunidades en Supabase</h2>
      <label>Fecha Inicio: <input type="date" id="mostrarFechaInicio"></label>
      <label>Fecha Fin: <input type="date" id="mostrarFechaFin"></label>
      <button onclick="cargarOportunidades()">Buscar</button>
      <div id="tablaOportunidades"></div>
    </section>
  </div>
</div>

<!-- Modal -->
<div id="modal"></div>

<script type="module">
  import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

  const SUPABASE_URL = 'https://dvhzhszkulgsvfuuaatz.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR2aHpoc3prdWxnc3ZmdXVhYXR6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTYxNDY4NTgsImV4cCI6MjA3MTcyMjg1OH0.wy2a8vYUrDn9HPsqGC6Pcpo0Zt0KAnVBqBuTvlFJkvk';
  const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

  let tablaGenerada = [];

  // === CONTROL DE TABS (SIN CSS) ===
  function mostrarTab(tabId) {
    // Ocultar todos los paneles
    document.querySelectorAll('.tab-panel').forEach(panel => {
      panel.style.display = 'none';
    });
    // Quitar clase active de todos los botones
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('active');
    });
    // Mostrar el panel seleccionado
    const panel = document.getElementById(tabId);
    if (panel) panel.style.display = 'block';
    // Activar el botón
    const btn = document.querySelector(`[data-tab="${tabId}"]`);
    if (btn) btn.classList.add('active');
  }

  // Asignar evento a cada botón
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      mostrarTab(btn.dataset.tab);
    });
  });

  // === FECHA Y HORA PARA SUBIR ===
  const dateInput = document.getElementById('Date');
  const timeInput = document.getElementById('Time');
  const textarea = document.getElementById('inputData');
  function verificarFechaHora() { 
    textarea.disabled = !(dateInput.value && timeInput.value); 
  }
  dateInput.addEventListener('change', verificarFechaHora);
  timeInput.addEventListener('change', verificarFechaHora);
  verificarFechaHora();

  // === GENERAR TABLA PREVIA ===
  window.generarTabla = async () => {
    const fecha = dateInput.value;
    const hora = timeInput.value;
    const input = textarea.value.trim();
    if (!fecha || !hora || !input) return alert("Completa fecha, hora y pega datos.");

    const lineas = input.split('\n');
    const datos = lineas.map(l => l.split(/\t+|\s{2,}/).map(c => c.trim()).filter(c => c));

    const { data: existentes } = await supabase.from('opportunities').select('Phone');
    const numerosExistentes = new Set(existentes.map(r => r.Phone));

    tablaGenerada = [];
    let html = `<table border="1"><tr><th>Date</th><th>Time</th>${datos[0].map(h => `<th>${h}</th>`).join('')}</tr>`;

    for (let i = 1; i < datos.length; i++) {
      const fila = { date: fecha, time: hora };
      datos[0].forEach((col, idx) => fila[col] = datos[i][idx] || '');
      tablaGenerada.push(fila);
      const existe = numerosExistentes.has(fila['Phone']);
      html += `<tr style="background:${existe?'#ffa07a':'white'}">
        <td>${fecha}</td><td>${hora}</td>${datos[i].map(c => `<td>${c}</td>`).join('')}
      </tr>`;
    }
    html += `</table>`;
    document.getElementById('resultado').innerHTML = html;
  };

  // === GUARDAR EN SUPABASE CON MODAL ===
  window.guardarEnSupabase = async () => {
    if (!tablaGenerada.length) return alert("Primero genera la tabla.");
    const { data: existentes } = await supabase.from('opportunities').select('Phone');
    const numerosExistentes = new Set(existentes.map(r => r.Phone));
    const nuevos = tablaGenerada.filter(f => !numerosExistentes.has(f['Phone']));
    if (!nuevos.length) return alert("Todos los registros ya existen.");

    let html = `<h3>Confirmar ${nuevos.length} registros nuevos:</h3>
      <table border="1"><tr><th>Cliente</th><th>Teléfono</th><th>Asesor</th><th>Program</th><th>Deuda</th><th>Status</th></tr>`;
    nuevos.forEach(r => {
      html += `<tr>
        <td>${r["Client Name"]||""}</td>
        <td>${r["Phone"]||""}</td>
        <td>${r["Financial Advisor"]||""}</td>
        <td>${r["Program"]||""}</td>
        <td>${r["Total Debt"]||""}</td>
        <td>${r["Status"]||""}</td>
      </tr>`;
    });
    html += `</table><br>
      <button onclick="confirmarGuardar()">Confirmar</button>
      <button onclick="cerrarModal()">Cancelar</button>`;

    const modal = document.getElementById('modal');
    modal.innerHTML = html;
    modal.style.display = 'block';
    modal.style.position = 'fixed';
    modal.style.top = '50%';
    modal.style.left = '50%';
    modal.style.transform = 'translate(-50%, -50%)';
    modal.style.background = 'white';
    modal.style.padding = '20px';
    modal.style.border = '2px solid #333';
    modal.style.zIndex = '1000';
    modal.style.maxWidth = '90%';
    modal.style.maxHeight = '90%';
    modal.style.overflow = 'auto';
  };

  window.confirmarGuardar = async () => {
    const { data: existentes } = await supabase.from('opportunities').select('Phone');
    const numerosExistentes = new Set(existentes.map(r => r.Phone));
    const nuevos = tablaGenerada.filter(f => !numerosExistentes.has(f['Phone']));
    if (!nuevos.length) { cerrarModal(); return; }

    const { error } = await supabase.from('opportunities').insert(nuevos);
    if (error) { console.error(error); alert("Error al guardar."); }
    else { alert("Guardado exitoso."); cargarOportunidades(); }
    cerrarModal();
  };

  window.cerrarModal = () => {
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
    modal.innerHTML = '';
  };

  // === CARGAR OPORTUNIDADES ===
  window.cargarOportunidades = async () => {
    const inicio = document.getElementById('mostrarFechaInicio').value;
    const fin = document.getElementById('mostrarFechaFin').value;

    let query = supabase.from('opportunities').select('*').order('id', { ascending: false });
    if (inicio) query = query.gte('date', inicio);
    if (fin) query = query.lte('date', fin);

    const { data, error } = await query;
    if (error) return alert("Error al cargar: " + error.message);

    let html = `<table border="1"><tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Teléfono</th><th>Asesor</th><th>Idioma</th><th>Program</th><th>Deuda</th><th>Status</th><th></th></tr>`;
    if (data.length === 0) {
      html += `<tr><td colspan="11" style="text-align:center; color:#999; padding:20px;">No hay oportunidades.</td></tr>`;
    } else {
      data.forEach(op => {
        html += `<tr>
          <td>${op.id}</td><td>${op.date}</td><td>${op.time}</td>
          <td>${op["Client Name"]||""}</td><td>${op["Phone"]||""}</td>
          <td>${op["Financial Advisor"]||""}</td><td>${op["Language"]||""}</td>
          <td>${op["Program"]||""}</td><td>${op["Total Debt"]||""}</td>
          <td>${op["Status"]||""}</td>
          <td><button onclick="eliminarOportunidad(${op.id})">Eliminar</button></td>
        </tr>`;
      });
    }
    html += `</table>`;
    document.getElementById('tablaOportunidades').innerHTML = html;
  };

  window.eliminarOportunidad = async (id) => {
    if (!confirm("¿Eliminar este registro?")) return;
    const { error } = await supabase.from('opportunities').delete().eq('id', id);
    if (error) alert("Error al eliminar.");
    else { alert("Eliminado."); cargarOportunidades(); }
  };

  // === RESUMEN POR AGENTE ===
  window.cargarResumenAgentes = async () => {
    const inicio = document.getElementById('fechaInicio').value;
    const fin = document.getElementById('fechaFin').value;
    if (!inicio || !fin) return alert("Selecciona ambas fechas.");

    const { data, error } = await supabase.from('opportunities')
      .select('"Financial Advisor", Language')
      .gte('date', inicio).lte('date', fin);

    if (error) return alert("Error al cargar resumen: " + error.message);

    const resumen = {};
    data.forEach(r => {
      const agente = r["Financial Advisor"] || "Sin Asesor";
      const lang = r.Language === "SPANISH" ? "SPANISH" : r.Language === "ENGLISH" ? "ENGLISH" : "OTRO";
      if (!resumen[agente]) resumen[agente] = { SPANISH: 0, ENGLISH: 0 };
      if (lang === "SPANISH") resumen[agente].SPANISH++;
      else if (lang === "ENGLISH") resumen[agente].ENGLISH++;
    });

    let html = `<table border="1">
      <tr><th>Agente</th><th>SPANISH</th><th>ENGLISH</th><th>Total</th></tr>`;
    Object.keys(resumen).sort().forEach(agente => {
      const s = resumen[agente].SPANISH;
      const e = resumen[agente].ENGLISH;
      html += `<tr><td><strong>${agente}</strong></td><td>${s}</td><td>${e}</td><td><strong>${s + e}</strong></td></tr>`;
    });
    html += `</table>`;
    document.getElementById('resumenAgentes').innerHTML = html;
  };

  // === AL CARGAR LA PÁGINA ===
  window.onload = () => {
    const hoy = new Date().toISOString().split('T')[0];

    // Establecer fechas
    document.getElementById('fechaInicio').value = hoy;
    document.getElementById('fechaFin').value = hoy;
    document.getElementById('mostrarFechaInicio').value = hoy;
    document.getElementById('mostrarFechaFin').value = hoy;

    // === SOLO MOSTRAR EL PRIMER TAB ===
    document.querySelectorAll('.tab-panel').forEach(panel => {
      panel.style.display = 'none';
    });
    const primerTab = document.getElementById('tab-resumen');
    if (primerTab) primerTab.style.display = 'block';

    // Cargar datos del primer tab
    cargarResumenAgentes();
  };
</script>
</body>
</html>