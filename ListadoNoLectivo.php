<?php
// Iniciar sesión y verificar autenticación
require_once("php/verificar_sesion.php");
verificarSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Días No Lectivos</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para mostrar mensajes temporales
        window.onload = function() {
            const msgSuccess = document.getElementById('msg-success');
            if (msgSuccess) {
                setTimeout(function() {
                    msgSuccess.style.opacity = '0';
                    setTimeout(function() {
                        msgSuccess.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        };
    </script>
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Días No Lectivos</h1>
        <p>Calendario > Listado de Días No Lectivos</p>
        
        <?php if (isset($_GET['success'])): ?>
        <div id="msg-success" class="success-message">
            <?php 
            $mensaje = "Operación realizada con éxito.";
            if ($_GET['success'] == '1') $mensaje = "Día no lectivo añadido correctamente.";
            if ($_GET['success'] == '2') $mensaje = "Día no lectivo eliminado correctamente.";
            echo htmlspecialchars($mensaje);
            ?>
        </div>
        <?php endif; ?>

        <!-- Barra de búsqueda -->
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" placeholder="Buscar por descripción" 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Buscar</button>
            <button type="button" onclick="window.location.href='ListadoNoLectivo.php'">Eliminar Filtro</button>
            
            <?php if (in_array($_SESSION['rol'], ['admin', 'editor'])): ?>
            <a href="AgregarDiaNoLectivo.php" class="add-btn">Añadir Día No Lectivo</a>
            <?php endif; ?>
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

                // Preparar la consulta con o sin filtro de búsqueda
                $query = "SELECT * FROM nolectivo";
                $params = [];
                $types = "";
                
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = "%" . $_GET['search'] . "%";
                    $query .= " WHERE descripcion LIKE ?";
                    $params[] = $search;
                    $types = "s";
                }
                
                $query .= " ORDER BY fecha DESC";
                
                $stmt = $conn->prepare($query);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        // Formatear la fecha en formato más legible (DD/MM/YYYY)
                        $fecha_formateada = date('d/m/Y', strtotime($row['fecha']));
                        echo "<td>" . htmlspecialchars($fecha_formateada) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td>";
                        
                        // Solo admin y editor pueden eliminar
                        if (in_array($_SESSION['rol'], ['admin', 'editor'])) {
                            echo "<a href='php/EliminarDiaNoLectivo.php?id=" . htmlspecialchars($row['id']) . "' ";
                            echo "class='delete-btn' ";
                            echo "onclick='return confirm(\"¿Estás seguro de que deseas eliminar este día no lectivo?\")'>Eliminar</a>";
                        } else {
                            echo "<span class='action-disabled'>Eliminar</span>";
                        }
                        
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