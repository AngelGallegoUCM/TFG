<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Incidencias</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function mostrarPopup(id) {
            const popup = document.getElementById('popup');
            const incidenciaId = document.getElementById('incidencia_id');
            incidenciaId.value = id;
            popup.style.display = 'block';
        }

        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
    <style>
  
  #popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    width: 400px; /* Ajusta el ancho según sea necesario */
}
    </style>
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Listado de Incidencias</h1>
        <p>Informe > Incidencias</p>

        <!-- Filtro de búsqueda -->
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <label for="inicio">Fecha de inicio:</label>
            <input type="date" id="inicio" name="inicio" value="<?php echo isset($_GET['inicio']) ? htmlspecialchars($_GET['inicio']) : ''; ?>">

            <label for="fin">Fecha de fin:</label>
            <input type="date" id="fin" name="fin" value="<?php echo isset($_GET['fin']) ? htmlspecialchars($_GET['fin']) : ''; ?>">

            <label for="justificada">Estado:</label>
            <select id="justificada" name="justificada">
                <option value="">Todas</option>
                <option value="1" <?php if(isset($_GET['justificada']) && $_GET['justificada'] == "1") echo 'selected'; ?>>Justificadas</option>
                <option value="0" <?php if(isset($_GET['justificada']) && $_GET['justificada'] == "0") echo 'selected'; ?>>No Justificadas</option>
            </select>

            <button type="submit">Filtrar</button>
            <button type="button" onclick="window.location.href='ListaIncidencias.php'">Restablecer Filtro</button>
        </form>

      <!-- Tabla de Incidencias -->
        <table border="1">
            <thead>
                <tr>
                    <th>Justificado</th>
                    <th>Día de Incidencia</th>
                    <th>Asignatura</th>
                    <th>Profesor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("php/conexion.php");

                $conditions = [];

                if (!empty($_GET['inicio']) && !empty($_GET['fin'])) {
                    $inicio = $conn->real_escape_string($_GET['inicio']);
                    $fin = $conn->real_escape_string($_GET['fin']);
                    $conditions[] = "i.fecha_incidencia BETWEEN '$inicio' AND '$fin'";
                }

                if (isset($_GET['justificada']) && ($_GET['justificada'] === "0" || $_GET['justificada'] === "1")) {
                    $justificada = intval($_GET['justificada']);
                    $conditions[] = "i.justificada = $justificada";
                }

                $where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

                $query = "
                    SELECT i.id, DATE_FORMAT(i.fecha_incidencia, '%d/%m/%y') AS fecha_incidencia, a.nombre_asignatura, 
                        CONCAT(p.nombre, ' ', p.apellidos) AS profesor, p.CorreoPropio AS correo_profesor, 
                        i.justificada, i.descripcion
                    FROM incidencias i
                    JOIN asistencias s ON i.asistencia_id = s.id
                    JOIN asignaturas a ON s.asignatura_id = a.id
                    JOIN profesores p ON a.profesor_id = p.id
                    $where
                    ORDER BY i.fecha_incidencia DESC";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . ($row['justificada'] ? '✔️' : '❌') . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_incidencia']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_asignatura']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['profesor']) . "</td>";
                        echo "<td>";
                        if (!$row['justificada']) {
                            echo "<button class='justify-btn' onclick='mostrarPopup(" . htmlspecialchars($row['id']) . ")'>Justificar</button> | ";
                            echo "<a href='php/EnviarCorreo.php?id=" . htmlspecialchars($row['id']) . "&correo_profesor=" . urlencode($row['correo_profesor']) . "' class='email-btn'>Enviar Correo</a> | ";
                        } else {
                            echo "<button class='email-btn' onclick='mostrarDescripcion(" . htmlspecialchars($row['id']) . ", \"" . htmlspecialchars($row['descripcion']) . "\")'>Ver Descripción</button> | ";
                        }
                        echo "<a href='php/EliminarIncidencia.php?id=" . htmlspecialchars($row['id']) . "' class='delete-btn' onclick='return confirm(\"¿Seguro que quieres eliminar esta incidencia?\")'>Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No se encontraron incidencias.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <!-- Popup de Descripción -->
        <div id="descripcion-popup" class="popup-overlay" style="display: none;">
            <div style="background: white; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); border-radius: 10px; z-index: 1000;">
                <h3>Descripción de la Incidencia</h3>
                <p id="descripcion-text"></p>
                <button type="button" onclick="cerrarDescripcionPopup()">Cerrar</button>
            </div>
        </div>

        <script>
            function mostrarDescripcion(id, descripcion) {
                const popup = document.getElementById('descripcion-popup');
                const descripcionText = document.getElementById('descripcion-text');
                descripcionText.innerText = descripcion;
                popup.style.display = 'block';
            }

            function cerrarDescripcionPopup() {
                document.getElementById('descripcion-popup').style.display = 'none';
            }
        </script>


        <!-- Popup de Justificación -->
        <div id="popup" class="popup-overlay" style="display: none;">
            <div class="popup-content">
                <form action="php/JustificarIncidencia.php" method="POST">
                    <h3>Justificar Incidencia</h3>
                    <input type="hidden" id="incidencia_id" name="id">
                    <label for="justificacion">Motivo de la justificación:</label>
                    <textarea id="justificacion" name="justificacion" rows="4" required></textarea>
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="cerrarPopup()">Cancelar</button>
                </form>
            </div>
</div>

    </div>
</body>
</html>
