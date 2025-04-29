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
    <title>Listado de Asignaturas</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Función para confirmar antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta asignatura?")) {
                window.location.href = "php/EliminarAsignatura.php?id=" + id;
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
        <h1>Asignaturas</h1>
        <p>Listado de todas las asignaturas registradas.</p>
        
        <?php if (isset($_GET['success'])): ?>
        <div id="msg-success" class="success-message">
            <?php 
            $mensaje = "Operación realizada con éxito.";
            if ($_GET['success'] == '1') $mensaje = "Asignatura añadida correctamente.";
            if ($_GET['success'] == '2') $mensaje = "Asignatura actualizada correctamente.";
            if ($_GET['success'] == '3') $mensaje = "Asignatura eliminada correctamente.";
            echo htmlspecialchars($mensaje);
            ?>
        </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda con protección XSS -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <input type="text" name="nombre_asignatura" placeholder="Buscar por nombre de asignatura" 
                   value="<?php echo isset($_GET['nombre_asignatura']) ? htmlspecialchars($_GET['nombre_asignatura']) : ''; ?>">
            <input type="text" name="profesor" placeholder="Buscar por profesor" 
                   value="<?php echo isset($_GET['profesor']) ? htmlspecialchars($_GET['profesor']) : ''; ?>">
            <input type="text" name="aula" placeholder="Buscar por aula" 
                   value="<?php echo isset($_GET['aula']) ? htmlspecialchars($_GET['aula']) : ''; ?>">
            <button type="submit">Buscar</button>
            <!-- Botón para eliminar el filtro -->
            <button type="button" onclick="window.location.href='ListadoAsignaturas.php'">Eliminar Filtros</button>
            
            <?php if (in_array($_SESSION['rol'], ['admin', 'editor'])): ?>
            <a href="AgregarAsignatura.php" class="add-btn">Añadir Asignatura</a>
            <?php endif; ?>
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
                $nombre_asignatura = isset($_GET['nombre_asignatura']) ? $_GET['nombre_asignatura'] : '';
                $profesor = isset($_GET['profesor']) ? $_GET['profesor'] : '';
                $aula = isset($_GET['aula']) ? $_GET['aula'] : '';

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

                // Preparar los parámetros y condiciones para la consulta
                $conditions = [];
                $params = [];
                $types = "";
                
                if (!empty($nombre_asignatura)) {
                    $conditions[] = "a.nombre_asignatura LIKE ?";
                    $params[] = "%" . $nombre_asignatura . "%";
                    $types .= "s";
                }
                
                if (!empty($profesor)) {
                    $conditions[] = "(p.nombre LIKE ? OR p.apellidos LIKE ?)";
                    $params[] = "%" . $profesor . "%";
                    $params[] = "%" . $profesor . "%";
                    $types .= "ss";
                }
                
                if (!empty($aula)) {
                    $conditions[] = "CAST(au.numero_aula AS CHAR) LIKE ?";
                    $params[] = "%" . $aula . "%";
                    $types .= "s";
                }

                // Agregar condiciones a la consulta si existen
                if (count($conditions) > 0) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }
                
                // Ordenar los resultados
                $query .= " ORDER BY a.nombre_asignatura, a.grupo";

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
                        echo "<td>" . htmlspecialchars($row['nombre_asignatura']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['numero_aula']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['grupo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['profesor_nombre']) . "</td>";
                        echo "<td>";
                        
                        // Todos pueden ver
                        echo "<a href='VerDatosAsignatura.php?id=" . htmlspecialchars($row['id']) . "' class='view-btn'>Ver</a>";
                        
                        // Solo admin y editor pueden modificar
                        if (in_array($_SESSION['rol'], ['admin', 'editor'])) {
                            echo " | <a href='ModificarAsignatura.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn'>Modificar</a>";
                        }
                        
                        // Solo admin puede eliminar
                        if ($_SESSION['rol'] === 'admin') {
                            echo " | <button onclick='confirmarEliminacion(" . htmlspecialchars($row['id']) . ")' class='delete-btn'>Eliminar</button>";
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay asignaturas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>