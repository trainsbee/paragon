// assets/script.js
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

// === MODAL ===
window.cerrarModal = () => {
  const modal = document.getElementById('modal');
  modal.style.display = 'none';
  modal.innerHTML = '';
};

// === FUNCIONES COMPARTIDAS ===
window.mostrarModal = (html) => {
  const modal = document.getElementById('modal');
  modal.innerHTML = html;
  modal.style.display = 'block';
};