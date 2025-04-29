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
    <title>Listado de Aulas</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para mostrar confirmación antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta aula?")) {
                window.location.href = "php/EliminarAula.php?id=" + id;
            }
        }
        
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
        <h1>Aulas</h1>
        <p>Aulas disponibles en la universidad.</p>
        
        <?php if (isset($_GET['success'])): ?>
        <div id="msg-success" class="success-message">
            <?php 
            $mensaje = "Operación realizada con éxito.";
            if ($_GET['success'] == '1') $mensaje = "Aula añadida correctamente.";
            if ($_GET['success'] == '2') $mensaje = "Aula actualizada correctamente.";
            if ($_GET['success'] == '3') $mensaje = "Aula eliminada correctamente.";
            echo htmlspecialchars($mensaje);
            ?>
        </div>
        <?php endif; ?>

        <!-- Barra de búsqueda con protección XSS -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Buscar por número de aula" 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Buscar</button>
            <!-- Botón para eliminar el filtro -->
            <button type="button" onclick="window.location.href='ListadoAulas.php'">Eliminar Filtro</button>
            
            <?php if (in_array($_SESSION['rol'], ['admin', 'editor'])): ?>
            <a href="AgregarAula.php" class="add-btn">Añadir Aula</a>
            <?php endif; ?>
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

                // Obtener el término de búsqueda si existe y preparar la consulta
                $search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;

                // Consulta preparada para evitar inyección SQL
                $query = "SELECT * FROM aulas";
                $params = [];
                $types = "";
                
                if (!empty($search)) {
                    $query .= " WHERE CAST(numero_aula AS CHAR) LIKE ?";
                    $params = [$search];
                    $types = "s";
                }
                
                // Preparar y ejecutar la consulta
                $stmt = $conn->prepare($query);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                // Generar filas dinámicamente
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['numero_aula']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['capacidad']) . "</td>";
                        echo "<td>";
                        
                        // Todos pueden ver
                        echo "<a href='VerDatosAula.php?id=" . htmlspecialchars($row['id']) . "' class='view-btn'>Ver datos</a>";
                        
                        // Solo admin y editor pueden modificar
                        if (in_array($_SESSION['rol'], ['admin', 'editor'])) {
                            echo " | <a href='ModificarAula.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Modificar</a>";
                        }
                        
                        // Solo admin puede eliminar
                        if ($_SESSION['rol'] === 'admin') {
                            echo " | <button onclick='confirmarEliminacion(" . htmlspecialchars($row['id']) . ")' class='delete-btn'>Eliminar</button>";
                        }
                        
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