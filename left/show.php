  <?php include 'nav.php'; ?>
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

<script type="module">
  import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

  const SUPABASE_URL = 'https://dvhzhszkulgsvfuuaatz.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR2aHpoc3prdWxnc3ZmdXVhYXR6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTYxNDY4NTgsImV4cCI6MjA3MTcyMjg1OH0.wy2a8vYUrDn9HPsqGC6Pcpo0Zt0KAnVBqBuTvlFJkvk';
  const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);


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

</script>