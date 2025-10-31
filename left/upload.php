  <?php include 'nav.php'; ?>
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
  <!-- Modal -->
<div id="modal"></div>
<script type="module">
  import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

  const SUPABASE_URL = 'https://dvhzhszkulgsvfuuaatz.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR2aHpoc3prdWxnc3ZmdXVhYXR6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTYxNDY4NTgsImV4cCI6MjA3MTcyMjg1OH0.wy2a8vYUrDn9HPsqGC6Pcpo0Zt0KAnVBqBuTvlFJkvk';
  const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);



let tablaGenerada = [];
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
</script>