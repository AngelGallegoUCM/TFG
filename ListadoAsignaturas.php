<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Asignaturas</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para confirmar antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta asignatura?")) {
                window.location.href = "php/EliminarAsignatura.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Asignaturas</h1>
        <p>Listado de todas las asignaturas registradas.</p>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="nombre_asignatura" placeholder="Buscar por nombre de asignatura" value="<?php echo isset($_GET['nombre_asignatura']) ? htmlspecialchars($_GET['nombre_asignatura']) : ''; ?>">
            <input type="text" name="profesor" placeholder="Buscar por profesor" value="<?php echo isset($_GET['profesor']) ? htmlspecialchars($_GET['profesor']) : ''; ?>">
            <input type="text" name="aula" placeholder="Buscar por aula" value="<?php echo isset($_GET['aula']) ? htmlspecialchars($_GET['aula']) : ''; ?>">
            <button type="submit">Buscar</button>
            <!-- Botón para eliminar el filtro -->
            <button type="button" onclick="window.location.href='ListadoAsignaturas.php'">Eliminar Filtros</button>
            <a href="AgregarAsignatura.php" class="add-btn">Añadir Asignatura</a>
        </form>

        <!-- Tabla dinámica -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre Asignatura</th>
                    <th>Aula</th>
                    <th>Grupo</th>
                    <th>Profesor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                include("php/conexion.php");

                // Obtener los filtros de búsqueda
                $nombre_asignatura = isset($_GET['nombre_asignatura']) ? $conn->real_escape_string($_GET['nombre_asignatura']) : '';
                $profesor = isset($_GET['profesor']) ? $conn->real_escape_string($_GET['profesor']) : '';
                $aula = isset($_GET['aula']) ? $conn->real_escape_string($_GET['aula']) : '';

                // Consulta base
                $query = "
                        SELECT a.id,
                        a.nombre_asignatura, 
                        a.grupo, 
                        CONCAT(p.nombre, ' ', p.apellidos) AS profesor_nombre, 
                        au.numero_aula
                        FROM asignaturas a
                        JOIN profesores p ON a.profesor_id = p.id
                        JOIN aulas au ON a.aula_id = au.id";

                // Agregar filtros dinámicamente
                $conditions = [];
                if (!empty($nombre_asignatura)) {
                    $conditions[] = "a.nombre_asignatura LIKE '%$nombre_asignatura%'";
                }
                if (!empty($profesor)) {
                    $conditions[] = "(p.nombre LIKE '%$profesor%' OR p.apellidos LIKE '%$profesor%')";
                }
                if (!empty($aula)) {
                    $conditions[] = "au.numero_aula LIKE '%$aula%'";
                }

                // Agregar condiciones a la consulta si existen
                if (count($conditions) > 0) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }

                // Ejecutar la consulta
                $result = $conn->query($query);

                // Generar filas dinámicamente
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre_asignatura']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['numero_aula']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['grupo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['profesor_nombre']) . "</td>";
                        echo "<td>";
                        echo "<a href='VerDatosAsignatura.php?id=" . htmlspecialchars($row['id']) . "' class='view-btn'>Ver</a>";
                        echo " | ";
                        echo "<a href='ModificarAsignatura.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Modificar</a>";
                        echo " | ";
                        echo "<button onclick='confirmarEliminacion(" . htmlspecialchars($row['id']) . ")' class='delete-btn'>Eliminar</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay asignaturas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>
