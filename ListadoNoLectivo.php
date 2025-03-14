<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Días No Lectivos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Días No Lectivos</h1>
        <p>Calendario > Listado de Días No Lectivos</p>

        <!-- Barra de búsqueda -->
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" placeholder="Buscar por descripción" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='ListadoNoLectivo.php'">Eliminar Filtro</button>
            <a href="AgregarDiaNoLectivo.php" class="add-btn">Añadir Día No Lectivo</a>
        </form>

        <!-- Tabla dinámica -->
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("php/conexion.php");

                $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                $query = "SELECT * FROM nolectivo";
                if (!empty($search)) {
                    $query .= " WHERE descripcion LIKE '%$search%'";
                }
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td>";
                        echo "<a href='php/EliminarDiaNoLectivo.php?id=" . htmlspecialchars($row['id']) . "' class='delete-btn' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este día no lectivo?\")'>Eliminar</a>";
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
