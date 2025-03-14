<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Asistencias</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Estadísticas de Asistencias</h1>
        <p>Informe > Estadísticas de Asistencias</p>

        <!-- Formulario de selección de fecha -->
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <label for="inicio">Fecha de inicio:</label>
            <input type="date" id="inicio" name="inicio" value="<?php echo isset($_GET['inicio']) ? htmlspecialchars($_GET['inicio']) : ''; ?>" required>

            <label for="fin">Fecha de fin:</label>
            <input type="date" id="fin" name="fin" value="<?php echo isset($_GET['fin']) ? htmlspecialchars($_GET['fin']) : ''; ?>" required>

            <button type="submit">Generar Estadísticas</button>
        </form>

        <?php
        if (isset($_GET['inicio']) && isset($_GET['fin'])) {
            include("php/conexion.php");

            $inicio = $conn->real_escape_string($_GET['inicio']);
            $fin = $conn->real_escape_string($_GET['fin']);

            // Obtener el número de asistencias
            $query_asistencias = "SELECT COUNT(*) AS total_asistencias FROM asistencias WHERE fecha BETWEEN '$inicio' AND '$fin'";
            $result_asistencias = $conn->query($query_asistencias);
            $total_asistencias = $result_asistencias->fetch_assoc()['total_asistencias'];

            // Obtener el número de incidencias justificadas
            $query_justificadas = "SELECT COUNT(*) AS total_justificadas FROM incidencias WHERE justificada = 1 AND fecha_incidencia BETWEEN '$inicio' AND '$fin'";
            $result_justificadas = $conn->query($query_justificadas);
            $total_justificadas = $result_justificadas->fetch_assoc()['total_justificadas'];

            // Obtener el número de incidencias sin justificar
            $query_no_justificadas = "SELECT COUNT(*) AS total_no_justificadas FROM incidencias WHERE justificada = 0 AND fecha_incidencia BETWEEN '$inicio' AND '$fin'";
            $result_no_justificadas = $conn->query($query_no_justificadas);
            $total_no_justificadas = $result_no_justificadas->fetch_assoc()['total_no_justificadas'];

            // Obtener el total de incidencias
            $total_incidencias = $total_justificadas + $total_no_justificadas;

            // Obtener el número de días no lectivos
            $query_nolectivos = "SELECT COUNT(*) AS total_nolectivos FROM nolectivo WHERE fecha BETWEEN '$inicio' AND '$fin'";
            $result_nolectivos = $conn->query($query_nolectivos);
            $total_nolectivos = $result_nolectivos->fetch_assoc()['total_nolectivos'];

            echo "<h2>Resultados del $inicio al $fin:</h2>";
            echo "<table border='1'>";
            echo "<thead><tr><th>Total de Asistencias</th><th>Días No Lectivos</th><th>Incidencias Justificadas</th><th>Incidencias Sin Justificar</th><th>Total de Incidencias</th></tr></thead>";
            echo "<tbody><tr>";
            echo "<td>$total_asistencias</td>";
            echo "<td>$total_nolectivos</td>";
            echo "<td>$total_justificadas</td>";
            echo "<td>$total_no_justificadas</td>";
            echo "<td>$total_incidencias</td>";
            echo "</tr></tbody>";
            echo "</table>";

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
