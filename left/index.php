<?php include 'nav.php'; ?>
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
    <table border="1" style="border-collapse:collapse;">
      <tr>
        <th>Tag</th>
        <th>Total Deals</th>
        <th>Opportunities</th>
        <th>%</th>
      </tr>
      <tr>
        <td>OFFICE</td>
        <td id="total-deals-office"></td>
        <td id="total-opportunities-office"></td>
        <td id="office-avg"></td>
      </tr>
      <tr>
        <td>ENGLISH</td>
        <td id="total-deals-english"></td>
        <td id="total-opportunities-english"></td>
        <td id="english-avg"></td>
      </tr>
      <tr>
        <td>SPANISH</td>
        <td id="total-deals-spanish"></td>
        <td id="total-opportunities-spanish"></td>
        <td id="spanish-avg"></td>
      </tr>
      <tr style="background:#f0f0f0; font-weight:bold;">
        <td>Total General</td>
        <td id="total-deals-all"></td>
        <td id="total-opportunities-all"></td>
        <td id="avg-all"></td>
      </tr>
    </table>
  </section>
</div>

<script type="module">
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

const SUPABASE_URL = 'https://dvhzhszkulgsvfuuaatz.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImR2aHpoc3prdWxnc3ZmdXVhYXR6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTYxNDY4NTgsImV4cCI6MjA3MTcyMjg1OH0.wy2a8vYUrDn9HPsqGC6Pcpo0Zt0KAnVBqBuTvlFJkvk';
const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// Totales de ventas ya conocidos (puedes reemplazarlos por tus datos reales)
let TotalSalesSpanish = 3;
let TotalSalesEnglish = 4;

// Mostrar en tabla inicial
document.getElementById('total-deals-spanish').textContent = TotalSalesSpanish;
document.getElementById('total-deals-english').textContent = TotalSalesEnglish;

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

  // === Calcular totales generales ===
  let totalSpanish = 0;
  let totalEnglish = 0;

  Object.values(resumen).forEach(r => {
    totalSpanish += r.SPANISH;
    totalEnglish += r.ENGLISH;
  });

  // === Calcular totales combinados ===
  const totalOpSpanish = totalSpanish + TotalSalesSpanish;
  const totalOpEnglish = totalEnglish + TotalSalesEnglish;
  const totalDealsAll = TotalSalesSpanish + TotalSalesEnglish;
  const totalOpAll = totalOpSpanish + totalOpEnglish;

  // Mostrar totales en la tabla principal
  document.getElementById('total-opportunities-spanish').textContent = totalOpSpanish;
  document.getElementById('total-opportunities-english').textContent = totalOpEnglish;
  document.getElementById('total-deals-all').textContent = totalDealsAll;
  document.getElementById('total-opportunities-all').textContent = totalOpAll;

  // Porcentajes
  const spanishPct = ((TotalSalesSpanish / totalOpSpanish) * 100).toFixed(2) + '%';
  const englishPct = ((TotalSalesEnglish / totalOpEnglish) * 100).toFixed(2) + '%';
  const avgAll = ((totalDealsAll / totalOpAll) * 100).toFixed(2) + '%';

  document.getElementById('spanish-avg').textContent = spanishPct;
  document.getElementById('english-avg').textContent = englishPct;
  document.getElementById('avg-all').textContent = avgAll;

  // === Generar tabla de resumen por agente ===
  let html = `<table border="1" style="border-collapse:collapse;">
    <thead>
      <tr>
        <th>Agente</th>
        <th>SPANISH</th>
        <th>ENGLISH</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>`;

  Object.keys(resumen).sort().forEach(agente => {
    const s = resumen[agente].SPANISH;
    const e = resumen[agente].ENGLISH;
    html += `<tr>
      <td><strong>${agente}</strong></td>
      <td>${s}</td>
      <td>${e}</td>
      <td><strong>${s + e}</strong></td>
    </tr>`;
  });

  // === Footer con totales ===
  html += `</tbody>
    <tfoot>
      <tr style="background:#f0f0f0; font-weight:bold;">
        <td>Total General</td>
        <td>${totalSpanish}</td>
        <td>${totalEnglish}</td>
        <td>${totalSpanish + totalEnglish}</td>
      </tr>
    </tfoot>
  </table>`;

  document.getElementById('resumenAgentes').innerHTML = html;
};
</script>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Filtrar registros DATE y PHONE</title>
  <style>
    table {
      border-collapse: collapse;
      width: 60%;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
  <h1>Filtrar registros DATE y PHONE</h1>

  <label>Desde:</label>
  <input type="date" id="start">
  <label>Hasta:</label>
  <input type="date" id="end">
  <button id="buscarBtn" onclick="buscar()">Buscar</button>

  <div id="tableContainer">Cargando datos...</div>

  <script>
  const start = '2025-10-30';
const end = '2025-10-31';
const url = `https://script.google.com/macros/s/AKfycbymqg68gT0G0uEXsig-dfgo5N1TUGcDAt9mGk7ayx2TPR2rLS4t5DstOboOH-DABdNz4A/exec?start=${start}&end=${end}`;

  function buscar() {
    fetch(url)
      .then(res => res.json())
      .then(data => {
        let html = '<table border="1"><tr><th>DATE</th><th>PHONE</th></tr>';
        data.forEach(item => {
          html += `<tr><td>${item.DATE}</td><td>${item.PHONE}</td></tr>`;
        });
        html += '</table>';
        document.getElementById('tableContainer').innerHTML = html;
      });
  }
</script>
</body>
</html>
