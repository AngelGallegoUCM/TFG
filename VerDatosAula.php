<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Aula</title>
    <link rel="stylesheet" href="stylesDatos.css">
</head>
<body>
    <?php
    // Conexión a la base de datos
    include("php/conexion.php");

    // Obtener el ID del aula desde la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $aula_id = intval($_GET['id']);

        // Consulta para obtener los datos del aula
        $query_aula = "SELECT * FROM aulas WHERE id = ?";
        $stmt = $conn->prepare($query_aula);
        $stmt->bind_param("i", $aula_id);
        $stmt->execute();
        $result_aula = $stmt->get_result();

        if ($result_aula->num_rows > 0) {
            $aula = $result_aula->fetch_assoc();
        } else {
            die("Aula no encontrada.");
        }

        // Consulta para obtener las asignaturas en el aula
        $query_asignaturas = "
            SELECT a.nombre_asignatura, 
                   a.grupo, 
                   CONCAT(p.nombre, ' ', p.apellidos) AS profesor_nombre, 
                   h.hora_inicio, 
                   h.hora_fin
            FROM asignaturas a
            JOIN profesores p ON a.profesor_id = p.id
            JOIN horarios h ON a.id = h.asignatura_id
            WHERE a.aula_id = ?";

        $stmt = $conn->prepare($query_asignaturas);
        $stmt->bind_param("i", $aula_id);
        $stmt->execute();
        $result_asignaturas = $stmt->get_result();
    } else {
        die("ID del aula no especificado o inválido.");
    }
    ?>

    <div class="container">
        <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->
        <main class="content">
            <h1>Aulas</h1>
            <h2>Datos del Aula <?php echo htmlspecialchars($aula['numero_aula']); ?></h2>

            <!-- Mostrar los datos del aula -->
            <div class="form-container">
                <!-- Datos básicos del aula -->
                <div class="form-group">
                    <label for="numero-aula">Número de Aula</label>
                    <input type="text" id="numero-aula" value="<?php echo htmlspecialchars($aula['numero_aula']); ?>" readonly>

                    <label for="capacidad">Capacidad</label>
                    <input type="text" id="capacidad" value="<?php echo htmlspecialchars($aula['capacidad']); ?>" readonly>
                </div>

                <!-- Tabla para Asignaturas -->
                <table class="data-table" id="tabla-asignaturas">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Grupo</th>
                            <th>Profesor</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_asignaturas->num_rows > 0) {
                            while ($asignatura = $result_asignaturas->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($asignatura['nombre_asignatura']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['grupo']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['profesor_nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['hora_inicio']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['hora_fin']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No hay asignaturas registradas para este aula.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Botón Horario -->
                <button type="button" class="horario-btn" onclick="mostrarHorario()">Horario</button>

                <!-- Botón Volver -->
                <button class="volver" onclick="history.back()">Volver</button>

            </div>

        </main>
    </div>

    <script>
        function mostrarHorario() {
            const tabla = document.getElementById('tabla-asignaturas');
            tabla.style.display = tabla.style.display === 'none' ? 'table' : 'none';
        }
    </script>

</body>
</html>
