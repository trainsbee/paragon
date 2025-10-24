<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesos de Data Entry - Paragon Financial Corp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-card: #161616;
            --bg-card-hover: #1a1a1a;
            --border-subtle: #222222;
            --border-emphasis: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted: #737373;
            --accent-gold: #d4af37;
            --accent-gold-hover: #c19b2e;
            --accent-emerald: #047857;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            padding: 5rem 2rem 4rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--accent-gold);
            margin-bottom: 1.5rem;
        }

        .header h1 {
            font-size: 3.5rem;
            font-weight: 300;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .header-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .header-location {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        /* Grid */
        .processes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.5rem;
        }

        /* Process Card */
        .process-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 0.75rem;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .process-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-emerald));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .process-card:hover {
            background-color: var(--bg-card-hover);
            border-color: var(--border-emphasis);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .process-card:hover::before {
            transform: scaleX(1);
        }

        .process-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .process-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
            line-height: 1.4;
        }

        .process-card-icon {
            width: 24px;
            height: 24px;
            opacity: 0.4;
            transition: opacity 0.3s ease;
        }

        .process-card:hover .process-card-icon {
            opacity: 1;
        }

        .process-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 300;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Side Modal */
        .modal-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            max-width: 500px;
            height: 100%;
            background-color: var(--bg-secondary);
            border-left: 1px solid var(--border-emphasis);
            box-shadow: var(--shadow-xl);
            transform: translateX(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1001;
            overflow-y: auto;
        }

        .modal-drawer.active {
            transform: translateX(0);
        }

        .modal-header {
            padding: 2.5rem 2rem 2rem;
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            background-color: var(--bg-secondary);
            z-index: 10;
        }

        .modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 40px;
            height: 40px;
            border: none;
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: var(--text-secondary);
        }

        .modal-close:hover {
            background-color: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .modal-title {
            font-size: 1.75rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-section {
            margin-bottom: 2rem;
        }

        .modal-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .info-item {
            background-color: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 0.5rem;
            padding: 1.25rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            border-color: var(--border-emphasis);
        }

        .info-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-primary);
            font-weight: 400;
        }

        /* Footer */
        .footer {
            background-color: var(--bg-secondary);
            border-top: 1px solid var(--border-subtle);
            padding: 3rem 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h4 {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .footer-section p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 300;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }

            .processes-grid {
                grid-template-columns: 1fr;
            }

            .modal-drawer {
                max-width: 100%;
            }

            .container {
                padding: 2rem 1rem;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-emphasis);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-badge">Paragon Financial Corp</div>
            <h1>DATA ENTRY</h1>
            <p class="header-subtitle">Gestión de Procesos</p>
            <p class="header-location">Honduras</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="processes-grid">
            <!-- Process Cards -->
            <div class="process-card" onclick="openModal('qc-compliance', 'Quality Control Compliance', 'Realizar el control de calidad a las llamadas de verificación que se reciben a diario, auditar y evaluar la calidad de la llamada.', 'Juan Pérez', 'jperez', 'jperez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Quality Control Compliance</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Realizar el control de calidad a las llamadas de verificación que se reciben a diario, auditar y evaluar la calidad de la llamada.</p>
            </div>

            <div class="process-card" onclick="openModal('qc-sales', 'Quality Control Sales', 'Auditar llamadas de ventas asegurando que se sigan todos los procedimientos por cada agente que realiza la venta.', 'María López', 'mlopez', 'mlopez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Quality Control Sales</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Auditar llamadas de ventas asegurando que se sigan todos los procedimientos por cada agente que realiza la venta.</p>
            </div>

            <div class="process-card" onclick="openModal('manager-sales', 'Manager Sales', 'Proceso crítico, núcleo del reporte de ventas. Verificar en grupos de español e inglés cambios de status de firmas, verificar datos específicos correctos, con alta concentración pendiente de cada venta que llega.', 'Carlos Ramírez', 'cramirez', 'cramirez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Manager Sales</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Proceso crítico, núcleo del reporte de ventas. Verificar en grupos de español e inglés cambios de status de firmas, verificar datos específicos correctos.</p>
            </div>

            <div class="process-card" onclick="openModal('reportes-cpa', 'Reportes CPA', 'Crear los reportes de ventas CPA, verificar últimos deals si se cerraron.', 'Ana Gómez', 'agomez', 'agomez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Reportes CPA</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Crear los reportes de ventas CPA, verificar últimos deals si se cerraron.</p>
            </div>

            <div class="process-card" onclick="openModal('left-behind', 'Left Behind', 'Filtrar oportunidades que llegan de las campañas, eliminar, actualizar ventas y deals que no aplican a los programas.', 'Luis Fernández', 'lfernandez', 'lfernandez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Left Behind</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Filtrar oportunidades que llegan de las campañas, eliminar, actualizar ventas y deals que no aplican a los programas.</p>
            </div>

            <div class="process-card" onclick="openModal('registro-data', 'Registro de Data', 'Proceso de ingreso de toda la data para realizar otros procesos como QC. Registrar información para ser usada en otros procesos, como ingreso de datos de clientes, búsqueda de audios de cada llamada ya sea en inglés o español.', 'Sofia Martínez', 'smartinez', 'smartinez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Registro de Data</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Proceso de ingreso de toda la data para realizar otros procesos como QC. Registrar información para ser usada en otros procesos.</p>
            </div>

            <div class="process-card" onclick="openModal('enrollment', 'Enrollment', 'Verificar que todos los clientes estén con los status correctos diario, mañana y tarde.', 'Pedro Sánchez', 'psanchez', 'psanchez@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Enrollment</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Verificar que todos los clientes estén con los status correctos diario, mañana y tarde.</p>
            </div>

            <div class="process-card" onclick="openModal('enrollment-review', 'Enrollment Review', 'Verificación de todos los leads del día anterior, reportar y corregir errores.', 'Laura Díaz', 'ldiaz', 'ldiaz@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Enrollment Review</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Verificación de todos los leads del día anterior, reportar y corregir errores.</p>
            </div>

            <div class="process-card" onclick="openModal('processing', 'Processing', 'Alta concentración, estar pendiente de los grupos español e inglés, verificar los clientes que llegan para asegurar que toda la información esté correcta, cumplan las políticas, documentación correcta, validar y detectar anomalías como cuentas que no aplican, información incorrecta, documentos y retornar al agente para su corrección.', 'Roberto Vargas', 'rvargas', 'rvargas@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>Processing</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Alta concentración, estar pendiente de los grupos español e inglés, verificar los clientes que llegan para asegurar que toda la información esté correcta.</p>
            </div>

            <div class="process-card" onclick="openModal('qc-processing', 'QC Processing', 'Verificar que cada proceso de processing haya sido correctamente ejecutado, validación y cumplimiento que lleva el proceso de processing.', 'Elena Ruiz', 'eruiz', 'eruiz@paragonfinancial.com')">
                <div class="process-card-header">
                    <h3>QC Processing</h3>
                    <svg class="process-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <p>Verificar que cada proceso de processing haya sido correctamente ejecutado, validación y cumplimiento que lleva el proceso de processing.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Supervisor del Departamento</h4>
                <p>David Maldonado</p>
            </div>
            <div class="footer-section">
                <h4>Gerente de la Empresa</h4>
                <p>Gustavo Zelaya</p>
            </div>
            <div class="footer-section">
                <h4>Recursos Humanos</h4>
                <p>Jorge Arturo</p>
            </div>
        </div>
    </footer>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

    <!-- Side Modal Drawer -->
    <div class="modal-drawer" id="modalDrawer">
        <div class="modal-header">
            <button class="modal-close" onclick="closeModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="modal-title" id="modalTitle"></h2>
            <p class="modal-subtitle">Detalles del Proceso</p>
        </div>
        <div class="modal-body">
            <div class="modal-section">
                <h3 class="modal-section-title">Descripción</h3>
                <div class="info-item">
                    <p class="info-value" id="modalDescription"></p>
                </div>
            </div>
            <div class="modal-section">
                <h3 class="modal-section-title">Información del Responsable</h3>
                <div class="info-item">
                    <p class="info-label">Responsable</p>
                    <p class="info-value" id="modalResponsible"></p>
                </div>
                <div class="info-item">
                    <p class="info-label">Usuario</p>
                    <p class="info-value" id="modalUser"></p>
                </div>
                <div class="info-item">
                    <p class="info-label">Correo Electrónico</p>
                    <p class="info-value" id="modalEmail"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, title, description, responsible, user, email) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalResponsible').textContent = responsible;
            document.getElementById('modalUser').textContent = user;
            document.getElementById('modalEmail').textContent = email;
            
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalDrawer').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('modalDrawer').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>

</body>
</html>