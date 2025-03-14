<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Asignatura</title>
    <link rel="stylesheet" href="stylesDatos.css">
</head>
<body>
    <?php
    // Conexión a la base de datos
    include("php/conexion.php");

    // Validar y obtener el ID de la asignatura
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $asignatura_id = intval($_GET['id']);

        // Consulta para obtener los datos de la asignatura, profesor, aula y horarios
        $query_asignatura = "
            SELECT a.nombre_asignatura, a.grupo, au.numero_aula, 
                   CONCAT(p.nombre, ' ', p.apellidos) AS profesor_nombre, 
                   h.dia_semana, h.hora_inicio, h.hora_fin
            FROM asignaturas a
            LEFT JOIN profesores p ON a.profesor_id = p.id
            LEFT JOIN aulas au ON a.aula_id = au.id
            LEFT JOIN horarios h ON a.id = h.asignatura_id
            WHERE a.id = ?";

        $stmt = $conn->prepare($query_asignatura);
        $stmt->bind_param("i", $asignatura_id);
        $stmt->execute();
        $result_asignatura = $stmt->get_result();

        if ($result_asignatura->num_rows > 0) {
            $asignatura = $result_asignatura->fetch_assoc();
        } else {
            die("Asignatura no encontrada.");
        }
    } else {
        die("ID de la asignatura no especificado o inválido.");
    }
    ?>

    <div class="container">
        <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->
        <main class="content">
            <h1>Asignaturas</h1>
            <h2>Datos de Asignatura</h2>

            <div class="form-container">
                <form>
                    <div class="form-group">
                        <label for="nombre_asignatura">Nombre de la Asignatura</label>
                        <input type="text" id="nombre_asignatura" value="<?php echo htmlspecialchars($asignatura['nombre_asignatura']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="grupo">Grupo</label>
                        <input type="text" id="grupo" value="<?php echo htmlspecialchars($asignatura['grupo']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="numero_aula">Número de Aula</label>
                        <input type="text" id="numero_aula" value="<?php echo htmlspecialchars($asignatura['numero_aula']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="profesor">Profesor</label>
                        <input type="text" id="profesor" value="<?php echo htmlspecialchars($asignatura['profesor_nombre']); ?>" readonly>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Día de la Semana</th>
                                <th>Hora de Inicio</th>
                                <th>Hora de Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            do {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($asignatura['dia_semana']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['hora_inicio']) . "</td>";
                                echo "<td>" . htmlspecialchars($asignatura['hora_fin']) . "</td>";
                                echo "</tr>";
                            } while ($asignatura = $result_asignatura->fetch_assoc());
                            ?>
                        </tbody>
                    </table>

                    <!-- Botón Volver -->
                    <button class="volver" onclick="history.back()">Volver</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
