<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Aulas</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para mostrar confirmación antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta aula?")) {
                window.location.href = "php/EliminarAula.php?id=" + id;
            }
        }
    </script>
</head>
<body>
   
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->
    <div class="main-content">
        <h1>Aulas</h1>
        <p>Aulas disponibles en la universidad.</p>

        <!-- Barra de búsqueda -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Buscar por número de aula" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Buscar</button>
            <!-- Botón para eliminar el filtro -->
            <button type="button" onclick="window.location.href='ListadoAulas.php'">Eliminar Filtro</button>
            <a href="AgregarAula.php" class="add-btn">Añadir Aula</a>
        </form>

        <!-- Tabla dinámica -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Número de Aula</th>
                    <th>Capacidad</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión a la base de datos
                include("php/conexion.php");

                // Obtener el término de búsqueda si existe
                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                // Consulta para obtener las aulas (con filtro si hay búsqueda)
                $query = "SELECT * FROM aulas";
                if (!empty($search)) {
                    $query .= " WHERE numero_aula LIKE '%$search%'";
                }
                $result = $conn->query($query);

                // Generar filas dinámicamente
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['numero_aula']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['capacidad']) . "</td>";
                        echo "<td>";
                        echo "<a href='VerDatosAula.php?id=" . htmlspecialchars($row['id']) . "' class='view-btn'>Ver datos</a>";
                        echo " | ";
                        echo "<a href='ModificarAula.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Modificar</a>";
                        echo " | ";
                        echo "<button onclick='confirmarEliminacion(" . htmlspecialchars($row['id']) . ")' class='delete-btn'>Eliminar</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay aulas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
    
</body>
</html>
