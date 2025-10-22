<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesos de Data Entry - Paragon Financial Corp</title>
    <style>
        :root {
            --bg-primary: #171717;
            --bg-secondary: #0f0f0f;
            --bg-tertiary: #1a1a1a;
            --border-color: #2a2a2a;
            --text-primary: #e5e5e5;
            --text-secondary: #a0a0a0;
            --text-muted: #6b6b6b;
            --accent-primary: #006239;
            --accent-hover: #014d2d;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif; /* Assuming a modern sans-serif font */
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        header.hero {
            background-color: var(--bg-secondary);
            padding: 4rem 2rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        header.hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        header.hero p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .processes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .process-card {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .process-card:hover {
            background-color: var(--bg-secondary);
        }

        .process-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--accent-primary);
        }

        .process-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-content h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--accent-primary);
        }

        .modal-content p {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: var(--text-primary);
        }

        footer {
            background-color: var(--bg-secondary);
            padding: 2rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        footer p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <header class="hero">
        <h1>Procesos de Data Entry</h1>
        <p>Paragon Financial Corp</p>
        <p>País: Honduras</p>
    </header>

    <div class="container">
        <section class="processes-grid">
            <!-- Process Cards -->
            <div class="process-card" onclick="openModal('modal-qc-compliance')">
                <h3>Quality Control Compliance</h3>
                <p>Realizar el control de calidad a las llamadas de verificación que se reciben a diario, auditar y evaluar la calidad de la llamada.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-qc-sales')">
                <h3>Quality Control Sales</h3>
                <p>Auditar llamadas de ventas asegurando que se sigan todos los procedimientos por cada agente que realiza la venta.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-manager-sales')">
                <h3>Manager Sales</h3>
                <p>Proceso crítico, núcleo del reporte de ventas. Verificar en grupos de español e inglés cambios de status de firmas, verificar datos específicos correctos, con alta concentración pendiente de cada venta que llega.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-reportes-cpa')">
                <h3>Reportes CPA</h3>
                <p>Crear los reportes de ventas CPA, verificar últimos deals si se cerraron.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-left-behind')">
                <h3>Left Behind</h3>
                <p>Filtrar oportunidades que llegan de las campañas, eliminar, actualizar ventas y deals que no aplican a los programas.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-registro-data')">
                <h3>Registro de Data</h3>
                <p>Proceso de ingreso de toda la data para realizar otros procesos como QC. Registrar información para ser usada en otros procesos, como ingreso de datos de clientes, búsqueda de audios de cada llamada ya sea en inglés o español.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-enrollment')">
                <h3>Enrollment</h3>
                <p>Verificar que todos los clientes estén con los status correctos diario, mañana y tarde.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-enrollment-review')">
                <h3>Enrollment Review</h3>
                <p>Verificación de todos los leads del día anterior, reportar y corregir errores.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-processing')">
                <h3>Processing</h3>
                <p>Alta concentración, estar pendiente de los grupos español e inglés, verificar los clientes que llegan para asegurar que toda la información esté correcta, cumplan las políticas, documentación correcta, validar y detectar anomalías como cuentas que no aplican, información incorrecta, documentos y retornar al agente para su corrección.</p>
            </div>

            <div class="process-card" onclick="openModal('modal-qc-processing')">
                <h3>QC Processing</h3>
                <p>Verificar que cada proceso de processing haya sido correctamente ejecutado, validación y cumplimiento que lleva el proceso de processing.</p>
            </div>
        </section>
    </div>

    <!-- Modals -->
    <div id="modal-qc-compliance" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-qc-compliance')">&times;</span>
            <h4>Quality Control Compliance</h4>
            <p>Responsable: Juan Pérez</p>
            <p>Usuario: jperez</p>
            <p>Correo: jperez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-qc-sales" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-qc-sales')">&times;</span>
            <h4>Quality Control Sales</h4>
            <p>Responsable: María López</p>
            <p>Usuario: mlopez</p>
            <p>Correo: mlopez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-manager-sales" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-manager-sales')">&times;</span>
            <h4>Manager Sales</h4>
            <p>Responsable: Carlos Ramírez</p>
            <p>Usuario: cramirez</p>
            <p>Correo: cramirez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-reportes-cpa" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-reportes-cpa')">&times;</span>
            <h4>Reportes CPA</h4>
            <p>Responsable: Ana Gómez</p>
            <p>Usuario: agomez</p>
            <p>Correo: agomez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-left-behind" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-left-behind')">&times;</span>
            <h4>Left Behind</h4>
            <p>Responsable: Luis Fernández</p>
            <p>Usuario: lfernandez</p>
            <p>Correo: lfernandez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-registro-data" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-registro-data')">&times;</span>
            <h4>Registro de Data</h4>
            <p>Responsable: Sofia Martínez</p>
            <p>Usuario: smartinez</p>
            <p>Correo: smartinez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-enrollment" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-enrollment')">&times;</span>
            <h4>Enrollment</h4>
            <p>Responsable: Pedro Sánchez</p>
            <p>Usuario: psanchez</p>
            <p>Correo: psanchez@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-enrollment-review" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-enrollment-review')">&times;</span>
            <h4>Enrollment Review</h4>
            <p>Responsable: Laura Díaz</p>
            <p>Usuario: ldiaz</p>
            <p>Correo: ldiaz@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-processing" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-processing')">&times;</span>
            <h4>Processing</h4>
            <p>Responsable: Roberto Vargas</p>
            <p>Usuario: rvargas</p>
            <p>Correo: rvargas@paragonfinancial.com</p>
        </div>
    </div>

    <div id="modal-qc-processing" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modal-qc-processing')">&times;</span>
            <h4>QC Processing</h4>
            <p>Responsable: Elena Ruiz</p>
            <p>Usuario: eruiz</p>
            <p>Correo: eruiz@paragonfinancial.com</p>
        </div>
    </div>

    <footer>
        <p>Supervisor del Departamento: David Maldonado</p>
        <p>Gerente de la Empresa: Gustavo Zelaya</p>
        <p>RRHH: Jorge Arturo</p>
    </footer>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>

</body>
</html>