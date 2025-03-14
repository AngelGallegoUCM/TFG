<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Asignatura</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function agregarHorario(dia = '', inicio = '', fin = '') {
            const contenedor = document.getElementById('horarios-container');
            const nuevoHorario = document.createElement('div');
            nuevoHorario.className = 'horario-item';
            nuevoHorario.innerHTML = `
                <label for="dia_semana[]">Día de la Semana:</label>
                <select name="dia_semana[]" required>
                    <option value="Lunes" ${dia === 'Lunes' ? 'selected' : ''}>Lunes</option>
                    <option value="Martes" ${dia === 'Martes' ? 'selected' : ''}>Martes</option>
                    <option value="Miércoles" ${dia === 'Miércoles' ? 'selected' : ''}>Miércoles</option>
                    <option value="Jueves" ${dia === 'Jueves' ? 'selected' : ''}>Jueves</option>
                    <option value="Viernes" ${dia === 'Viernes' ? 'selected' : ''}>Viernes</option>
                </select>
                <label for="hora_inicio[]">Hora Inicio:</label>
                <input type="time" name="hora_inicio[]" value="${inicio}" required>
                <label for="hora_fin[]">Hora Fin:</label>
                <input type="time" name="hora_fin[]" value="${fin}" required>
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

    <?php
    // Conexión a la base de datos
    include("php/conexion.php");

    // Obtener el ID de la asignatura desde la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $asignatura_id = intval($_GET['id']);

        // Consulta para obtener los datos de la asignatura
        $query = "
            SELECT a.*, h.dia_semana, h.hora_inicio, h.hora_fin
            FROM asignaturas a
            LEFT JOIN horarios h ON a.id = h.asignatura_id
            WHERE a.id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $asignatura_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $asignatura = null;
        $horarios = [];

        while ($fila = $result->fetch_assoc()) {
            if (!$asignatura) {
                $asignatura = $fila; // Guarda los datos de la asignatura una sola vez
            }
            if ($fila['dia_semana']) {
                $horarios[] = [
                    'dia_semana' => $fila['dia_semana'],
                    'hora_inicio' => $fila['hora_inicio'],
                    'hora_fin' => $fila['hora_fin']
                ];
            }
        }

        if (!$asignatura) {
            die("Asignatura no encontrada.");
        }

        if (empty($horarios)) {
            $horarios[] = ['dia_semana' => '', 'hora_inicio' => '', 'hora_fin' => ''];
        }
    } else {
        die("ID de la asignatura no especificado o inválido.");
    }

    // Consultas para obtener profesores y aulas existentes
    $query_profesores = "SELECT id, CONCAT(nombre, ' ', apellidos) AS nombre_completo FROM profesores";
    $result_profesores = $conn->query($query_profesores);

    $query_aulas = "SELECT id, numero_aula FROM aulas";
    $result_aulas = $conn->query($query_aulas);
    ?>

    <main class="content">
        <h1>Modificar Asignatura</h1>

        <!-- Formulario para modificar asignatura -->
        <form action="php/ActualizarAsignatura.php" method="POST" class="form-container">
            <!-- Campo oculto para enviar el ID de la asignatura -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($asignatura_id); ?>">

            <div class="form-group">
                <label for="nombre_asignatura">Nombre Asignatura:</label>
                <input type="text" id="nombre_asignatura" name="nombre_asignatura" value="<?php echo htmlspecialchars($asignatura['nombre_asignatura'] ?? ''); ?>" required>

                <label for="grupo">Grupo:</label>
                <input type="text" id="grupo" name="grupo" value="<?php echo htmlspecialchars($asignatura['grupo'] ?? ''); ?>" required>

                <!-- Selector de Profesor -->
                <label for="profesor_id">Profesor:</label>
                <select id="profesor_id" name="profesor_id" required>
                    <?php while ($profesor = $result_profesores->fetch_assoc()) { ?>
                        <option value="<?php echo $profesor['id']; ?>" <?php if ($asignatura['profesor_id'] == $profesor['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($profesor['nombre_completo']); ?>
                        </option>
                    <?php } ?>
                </select>

                <!-- Selector de Aula -->
                <label for="aula_id">Aula:</label>
                <select id="aula_id" name="aula_id" required>
                    <?php while ($aula = $result_aulas->fetch_assoc()) { ?>
                        <option value="<?php echo $aula['id']; ?>" <?php if ($asignatura['aula_id'] == $aula['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($aula['numero_aula']); ?>
                        </option>
                    <?php } ?>
                </select>

                <!-- Contenedor de horarios dinámicos -->
                <div id="horarios-container">
                    <h3>Horarios</h3>
                    <button type="button" onclick="agregarHorario()">Añadir Horario</button>
                    <?php foreach ($horarios as $horario) {
                        echo "<script>agregarHorario('" . $horario['dia_semana'] . "', '" . $horario['hora_inicio'] . "', '" . $horario['hora_fin'] . "');</script>";
                    } ?>
                </div>
            </div>

            <!-- Botones -->
            <button type="submit">Guardar Cambios</button>
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>