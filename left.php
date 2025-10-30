<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión de Oportunidades</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
    body { background: #f4f6f9; padding: 20px; }
    .container { max-width: 1200px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); overflow: hidden; }

    /* Navbar Tabs */
    .navbar { display: flex; background: #2c3e50; }
    .navbar button {
      flex: 1; padding: 15px; background: none; border: none; color: white; font-size: 16px; cursor: pointer; transition: 0.3s;
    }
    .navbar button:hover { background: #34495e; }
    .navbar button.active { background: #1abc9c; font-weight: bold; }

    /* Tab Content */
    .tab-content { display: none; padding: 20px; }
    .tab-content.active { display: block; }

    /* Sections */
    section { margin-bottom: 30px; }
    h2, h3, h4 { color: #2c3e50; margin-bottom: 10px; }
    input, textarea, button, select { margin: 5px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    button { background: #1abc9c; color: white; cursor: pointer; }
    button:hover { background: #16a085; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #f2f2f2; }
    tr:nth-child(even) { background: #f9f9f9; }

    /* Modal */
    #modal {
      display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
      background: white; border: 2px solid #2c3e50; padding: 20px; z-index: 1000; max-width: 90%; max-height: 90%; overflow: auto;
      box-shadow: 0 0 20px rgba(0,0,0,0.3); border-radius: 8px;
    }
    #modal button { margin: 5px; }

    /* Responsive */
    @media (max-width: 768px) {
      .navbar { flex-direction: column; }
      .navbar button { padding: 12px; }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Navbar Tabs -->
  <div class="navbar">
    <button data-tab="tab-resumen" class="active">Resumen por Agente</button>
    <button data-tab="tab-subir">Subir Oportunidades</button>
    <button data-tab="tab-mostrar">Mostrar Oportunidades</button>
  </div>

  <!-- Tab: Resumen por Agente -->
  <div id="tab-resumen" class="tab-content active">
    <section>
      <h2>Resumen de Oportunidades por Agente</h2>
      <label>Fecha Inicio: <input type="date" id="fechaInicio"></label>
      <label>Fecha Fin: <input type="date" id="fechaFin"></label>
      <button onclick="cargarResumenAgentes()">Buscar</button>
      <div id="resumenAgentes"></div>
    </section>
  </div>

  <!-- Tab: Subir Oportunidades -->
  <div id="tab-subir" class="tab-content">
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
  <div id="tab-mostrar" class="tab-content">
    <section>
      <h2>Oportunidades en Supabase</h2>
      <button onclick="cargarOportunidades()">Actualizar Lista</button>
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

  // === NAVBAR TABS ===
  document.querySelectorAll('.navbar button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.navbar button').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(btn.dataset.tab).classList.add('active');
    });
  });

  // === FECHA Y HORA PARA SUBIR ===
  const dateInput = document.getElementById('Date');
  const timeInput = document.getElementById('Time');
  const textarea = document.getElementById('inputData');
  function verificarFechaHora() { textarea.disabled = !(dateInput.value && timeInput.value); }
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
    let html = `<table><tr><th>Date</th><th>Time</th>${datos[0].map(h => `<th>${h}</th>`).join('')}</tr>`;

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
      <table><tr><th>Cliente</th><th>Teléfono</th><th>Asesor</th><th>Program</th><th>Deuda</th><th>Status</th></tr>`;
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

  // === CARGAR TODAS LAS OPORTUNIDADES ===
  window.cargarOportunidades = async () => {
    const { data, error } = await supabase.from('opportunities').select('*').order('id', { ascending: false });
    if (error) return alert("Error al cargar.");

    let html = `<table><tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Teléfono</th><th>Asesor</th><th>Idioma</th><th>Program</th><th>Deuda</th><th>Status</th><th></th></tr>`;
    data.forEach(op => {
      html += `<tr>
        <td>${op.id}</td><td>${op.date}</td><td>${op.time}</td>
        <td>${op["Client Name"]||""}</td><td>${op["Phone"]||""}</td>
        <td>${op["Financial Advisor"]||""}</td><td>${op["Language"]||""}</td>
        <td>${op["Program"]||""}</td><td>${op["Total Debt"]||""}</td><td>${op["Status"]||""}</td>
        <td><button onclick="eliminarOportunidad(${op.id})">Eliminar</button></td>
      </tr>`;
    });
    html += `</table>`;
    document.getElementById('tablaOportunidades').innerHTML = html;
  };

  window.eliminarOportunidad = async (id) => {
    if (!confirm("¿Eliminar este registro?")) return;
    const { error } = await supabase.from('opportunities').delete().eq('id', id);
    if (error) alert("Error al eliminar.");
    else { alert("Eliminado."); cargarOportunidades(); }
  };

  // === RESUMEN POR AGENTE (LO QUE QUERÍAS AL INICIO) ===
 window.cargarResumenAgentes = async () => {
  const inicio = document.getElementById('fechaInicio').value;
  const fin = document.getElementById('fechaFin').value;
  if (!inicio || !fin) return alert("Selecciona ambas fechas.");

  const { data, error } = await supabase.from('opportunities')
    .select('"Financial Advisor", Language')  // CORREGIDO: con comillas y espacio
    .gte('date', inicio).lte('date', fin);

  if (error) return alert("Error al cargar resumen: " + error.message);

  const resumen = {};
  data.forEach(r => {
    const agente = r["Financial Advisor"] || "Sin Asesor";  // Así se accede con espacio
    const lang = r.Language === "SPANISH" ? "SPANISH" : r.Language === "ENGLISH" ? "ENGLISH" : "OTRO";
    if (!resumen[agente]) resumen[agente] = { SPANISH: 0, ENGLISH: 0 };
    if (lang === "SPANISH") resumen[agente].SPANISH++;
    else if (lang === "ENGLISH") resumen[agente].ENGLISH++;
  });

  let html = `<table>
    <tr><th>Agente</th><th>SPANISH</th><th>ENGLISH</th><th>Total</th></tr>`;
  Object.keys(resumen).sort().forEach(agente => {
    const s = resumen[agente].SPANISH;
    const e = resumen[agente].ENGLISH;
    html += `<tr>
      <td><strong>${agente}</strong></td>
      <td style="background:#e8f5e9">${s}</td>
      <td style="background:#e3f2fd">${e}</td>
      <td><strong>${s + e}</strong></td>
    </tr>`;
  });
  html += `</table>`;
  document.getElementById('resumenAgentes').innerHTML = html;
};

  // === AL CARGAR LA PÁGINA: SETEAR FECHAS Y CARGAR RESUMEN ===
  window.onload = () => {
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaInicio').value = hoy;
    document.getElementById('fechaFin').value = hoy;
    cargarResumenAgentes(); // ¡Muestra resumen al entrar!
  };

</script>
</body>
</html>