<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Asignatura</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function agregarHorario() {
            const contenedor = document.getElementById('horarios-container');
            const nuevoHorario = document.createElement('div');
            nuevoHorario.className = 'horario-item';
            nuevoHorario.innerHTML = `
                <label for="dia_semana[]">Día de la Semana:</label>
                <select name="dia_semana[]" required>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                </select>
                <label for="hora_inicio[]">Hora Inicio:</label>
                <input type="time" name="hora_inicio[]" required>
                <label for="hora_fin[]">Hora Fin:</label>
                <input type="time" name="hora_fin[]" required>
                <button type="button" onclick="eliminarHorario(this)">Eliminar</button>
            `;
            contenedor.appendChild(nuevoHorario);
        }

        function eliminarHorario(boton) {
            boton.parentElement.remove();
        }
    </script>
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <main class="content">
        <h1>Añadir Asignatura</h1>

        <?php
        // Conexión a la base de datos
        include("php/conexion.php");

        // Consulta para obtener los profesores existentes
        $query_profesores = "SELECT id, CONCAT(nombre, ' ', apellidos) AS nombre_completo FROM profesores";
        $result_profesores = $conn->query($query_profesores);

        // Consulta para obtener las aulas existentes
        $query_aulas = "SELECT id, numero_aula FROM aulas";
        $result_aulas = $conn->query($query_aulas);
        ?>

        <!-- Formulario para añadir asignatura -->
        <form action="php/InsertarAsignatura.php" method="POST" class="form-container">
            <div class="form-group">
                <label for="nombre_asignatura">Nombre Asignatura:</label>
                <input type="text" id="nombre_asignatura" name="nombre_asignatura" required>

                <label for="grupo">Grupo:</label>
                <input type="text" id="grupo" name="grupo" required>

                <!-- Selector de Profesor -->
                <label for="profesor_id">Profesor:</label>
                <select id="profesor_id" name="profesor_id" required>
                    <?php while ($profesor = $result_profesores->fetch_assoc()) { ?>
                        <option value="<?php echo $profesor['id']; ?>"><?php echo $profesor['nombre_completo']; ?></option>
                    <?php } ?>
                </select>

                <!-- Selector de Aula -->
                <label for="aula_id">Aula:</label>
                <select id="aula_id" name="aula_id" required>
                    <?php while ($aula = $result_aulas->fetch_assoc()) { ?>
                        <option value="<?php echo $aula['id']; ?>"><?php echo $aula['numero_aula']; ?></option>
                    <?php } ?>
                </select>

                <!-- Contenedor de horarios dinámicos -->
                <div id="horarios-container">
                    <h3>Horarios</h3>
                    <button type="button" onclick="agregarHorario()">Añadir Horario</button>
                </div>
            </div>

            <!-- Botones -->
            <button type="submit">Guardar Asignatura</button>
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>
