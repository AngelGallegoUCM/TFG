<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Asistencia de Profesores</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="container">
      <!-- Barra lateral -->
      <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

      <!-- Contenido principal -->
      <main class="main-content">
        <header class="header">
          <h1>Bienvenido a Gestión de Asistencia de Profesores</h1>
          <p>Selecciona uno de los módulos de GAP</p>
        </header>

        <!-- Módulos -->
        <section class="modules">
            <div class="module">
              <img src="img/list.svg" alt="Listar Profesores" href="ListadoProfesores.html"/>
              <p>Listar Profesores</p>
            </div>
            <div class="module">
              <img src="img/view.svg" alt="Listar Aulas" />
              <p>Listar Aulas</p>
            </div>
            <div class="module">
              <img src="img/edit.svg" alt="Listar Asignatura" />
              <p>Listar Asignatura</p>
            </div>
          </section>
      </main>
    </div>
  </body>
</html>
