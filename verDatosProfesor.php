<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Profesor</title>
    <link rel="stylesheet" href="stylesDatos.css">
</head>
<body>
    <?php
    // Conexión a la base de datos
    include("php/conexion.php");

    // Validar y obtener el ID del profesor
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $profesor_id = intval($_GET['id']);

        // Consulta para obtener los datos del profesor y su departamento
        $query_profesor = "
            SELECT p.nombre, p.apellidos, p.CorreoPropio, d.nombre_departamento, d.correo_departamento 
            FROM profesores p
            LEFT JOIN departamento d ON p.departamento_id = d.id
            WHERE p.id = ?";

        $stmt = $conn->prepare($query_profesor);
        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();
        $result_profesor = $stmt->get_result();

        if ($result_profesor->num_rows > 0) {
            $profesor = $result_profesor->fetch_assoc();
        } else {
            die("Profesor no encontrado.");
        }

        // Consulta para obtener las asignaturas y horarios del profesor
        $query_asignaturas = "
            SELECT a.nombre_asignatura, a.grupo, au.numero_aula, h.hora_inicio, h.hora_fin 
            FROM asignaturas a
            JOIN aulas au ON a.aula_id = au.id
            JOIN horarios h ON a.id = h.asignatura_id
            WHERE a.profesor_id = ?";

        $stmt = $conn->prepare($query_asignaturas);
        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();
        $result_asignaturas = $stmt->get_result();

    } else {
        die("ID del profesor no especificado o inválido.");
    }
    ?>

    <div class="container">
        <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->
        <main class="content">
            <h1>Profesores</h1>
            <h2>Datos de Profesor</h2>

            <div class="form-container">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" value="<?php echo htmlspecialchars($profesor['apellidos']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Propio</label>
                        <input type="text" id="correo" value="<?php echo htmlspecialchars($profesor['CorreoPropio']); ?>" readonly>
                    </div>

                    <?php if (!empty($profesor['nombre_departamento'])): ?>
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <input type="text" id="departamento" value="<?php echo htmlspecialchars($profesor['nombre_departamento']); ?>" readonly>

                            <label for="correo-departamento">Correo Departamento</label>
                            <input type="text" id="correo-departamento" value="<?php echo htmlspecialchars($profesor['correo_departamento']); ?>" readonly>
                        </div>
                    <?php endif; ?>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Asignaturas</th>
                                <th>Grupo</th>
                                <th>Número del Aula</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_asignaturas->num_rows > 0) {
                                while ($asignatura = $result_asignaturas->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($asignatura['nombre_asignatura']) . "</td>";
                                    echo "<td>" . htmlspecialchars($asignatura['grupo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($asignatura['numero_aula']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No hay asignaturas registradas para este profesor.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                      <!-- Botón Horario -->
                      <button type="button" class="horario-btn" onclick="mostrarHorario()">Horario</button>
                </form>

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
