<?php require_once 'settings.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="assets/css/app.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <header class="header">
    <div class="bar-top">
      <div class="container">
        <div class="bar-menu">
          <div class="links">
            <a href="dashboard.php">
              <span class="dgm-icon" icon-data="home"><i data-feather="home"></i></span>
              Dashboard
            </a>
            <a href="monitoring.php">
              <span class="dgm-icon" icon-data="monitor"><i data-feather="monitor"></i></span>
              Monitoring
            </a>
            <a href="attendance.php">
              <span class="dgm-icon" icon-data="calendar"><i data-feather="calendar"></i></span>
              Attendance
            </a>
            <a href="employee_statement.php">
              <span class="dgm-icon" icon-data="file-text"><i data-feather="file-text"></i></span>
              Payroll statement
            </a>
          </div>
          <div class="attendance-switch">
            <label for="attendance-switch" class="switch-attendance">
              <input type="checkbox" id="attendance-switch">
              <span class="slider-attendance"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar container">
      <div class="left">
        <a href="index.php">
          <img src="<?php echo BASE_URL; ?>assets/images/brand/brand_logo.webp" alt="Logo">
        </a>
      </div>
      <div class="center">
        <form action="" class="form-group">
          <span class="dgm-icon" icon-data="search"><i data-feather="search"></i></span>
          <input type="text" placeholder="Buscar">
        </form>
      </div>
      <div class="right">
        <div class="profile">
          <div class="profile-img">
            <img src="https://vaion.neositio.com/uploads/profiles/profile_MGR001_1758653823.png" alt="Profile">
          </div>
          <div class="profile-info">
            <span class="name">dmaldonado</span>
            <span class="role">Manager</span>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="container">
      <div class="columns">
        <div class="column">
          <div class="box-column">
                <div class="form-container pauses">
            <div class="form-header">
              <span class="dgm-icon" icon-data="pause"><i data-feather="pause"></i></span>
              <h2>Administrador de Pausas</h2>
            </div>
            <div class="form-body">
              <p>Hola!, Puedes crear tus pausas aquí, recuerda organizarte bien para que no te quedes sin pausas (:</p>
            </div>
            <div class="form-footer">
              <div class="form-group">
                <span class="dgm-icon" icon-data="airplay"><i data-feather="disc"></i></span>
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
              </div>
              <div class="form-group pauses-switch border border-success">
                <label for="pauses-switch" class="switch-pauses">
                  <input type="checkbox" id="pauses-switch" onchange="togglePause()">
                  <span class="slider-pauses"></span>
                </label>
              </div>
            </div>
          </div>
          </div>
          
          <div class="box-column mt-1">
            <div class="form-container">
                <div class="form-header">
                    <h3>Stadistics</h3>
                    <div class="info-row">
                        <div class="box">
                            <h4 id="total-pauses">0</h4>
                            <p>Pausas activas</p>
                        </div>
                        <div class="box">
                            <h4 id="total-pause-time">0</h4>
                            <p>Tiempo consumido</p>
                        </div>
                        <div class="box">
                            <h4 id="total-remaining-time">0</h4>
                            <p>Tiempo restante</p>
                        </div>
                    </div> <!-- /.info-row -->
                </div> <!-- /.form-header -->

                <form class="filter-date range-container" id="filter-form">
                    <div class="form-body">
                        <div class="form-group">
                            <label>FROM</label>
                            <input type="date" value="<?php 
                                $today = new DateTime('now', new DateTimeZone(TIMEZONE));
                                echo $today->format('Y-m-d');
                            ?>" id="start-date">
                        </div>
                        <div class="form-group">
                            <label>TO</label>
                            <input type="date" value="<?php 
                                $today = new DateTime('now', new DateTimeZone(TIMEZONE));
                                echo $today->format('Y-m-d');
                            ?>" id="end-date">
                        </div>
                    </div> <!-- /.form-body -->

                    <div class="form-footer">
                        <button type="submit" class="filter-button">
                            <i data-feather="filter" style="width: 1rem; height: 1rem;"></i>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div> <!-- /.form-container -->
          </div>

          <div class="box-column mt-1">
            <div class="pauses-list">
            <!-- Card pause -->
            <div class="date-pause first">
              <h3>2025-10-30</h3>
            </div>
            <div class="card-pause">
              <h3>Break 15 minutos</h3>
              <div class="card-body">
                <div class="info-row">
                  <span class="info-label">Inicio:</span>
                  <span class="info-value">17:55:45 - En curso</span>
                </div>
              </div>
            </div>

            <!-- Card pause -->
            <div class="date-pause">
              <h3>2025-10-30</h3>
            </div>
            <div class="card-pause">
              <h3>Almuerzo</h3>
              <div class="card-body">
                <div class="info-row">
                  <span class="info-label">Inicio:</span>
                  <span class="info-value">17:39:01 - 17:39:02</span>
                </div>
                <div class="info-row">
                  <span class="info-label">Duración:</span>
                  <span class="info-value">1s</span>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>

        <div class="column">
          <div class="form-container">
            <p>Login</p>
            
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer></footer>

  <script>
    feather.replace();
  </script>
</body>
</html>