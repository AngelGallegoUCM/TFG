<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Profesores</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para mostrar confirmación antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este profesor?")) {
                window.location.href = "php/EliminarProfesor.php?id=" + id;
            }
        }
    </script>

</head>
<body>
     <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Profesores</h1>
        <p>Profesor > Listado de todos los Profesores</p>

        <!-- Barra de búsqueda -->
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" placeholder="Buscar por nombre o apellidos" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Buscar</button>
            <!-- Botón para eliminar el filtro -->
            <button type="button" onclick="window.location.href='ListadoProfesores.php'">Eliminar Filtro</button>
            <a href="AgregarProfesor.php" class="add-btn">Añadir Profesor</a>
        </form>

        <!-- Tabla dinámica -->
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                include("php/conexion.php");

                // Obtener el término de búsqueda si existe
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                // Consulta para obtener los profesores (con filtro si hay búsqueda)
                $query = "SELECT * FROM profesores";
                if (!empty($search)) {
                    $query .= " WHERE nombre LIKE '%$search%' OR apellidos LIKE '%$search%'";
                }
                $result = $conn->query($query);

                // Verificar si hay resultados
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                        echo "<td>";
                        echo "<a href='VerDatosProfesor.php?id=" . htmlspecialchars($row['id']) . "' class='view-btn'>Ver</a>";
                        echo " | ";
                        echo "<a href='ModificarProfesor.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Modificar</a>";
                        echo " | ";
                        echo "<button onclick='confirmarEliminacion(" . htmlspecialchars($row['id']) . ")' class='delete-btn'>Eliminar</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron resultados.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

</body>
</html>
