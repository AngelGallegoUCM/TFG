<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Día No Lectivo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <div class="main-content">
        <h1>Añadir Día No Lectivo</h1>
        <p>Calendario > Añadir Día No Lectivo</p>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include("php/conexion.php");

            $fecha = $conn->real_escape_string($_POST['fecha']);
            $descripcion = $conn->real_escape_string($_POST['descripcion']);

            $query = "INSERT INTO nolectivo (fecha, descripcion) VALUES ('$fecha', '$descripcion')";

            if ($conn->query($query) === TRUE) {
                echo "<p class='success-msg'>Día no lectivo añadido con éxito.</p>";
            } else {
                echo "<p class='error-msg'>Error al añadir el día no lectivo: " . $conn->error . "</p>";
            }

            $conn->close();
        }
        ?>

        <!-- Formulario para añadir día no lectivo -->
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Descripción" required>
            </div>

            <!-- Botones -->
            <button type="submit">Guardar Día No Lectivo</button>
            <button type="button" onclick="window.location.href='ListadoNoLectivo.php'">Volver al Listado</button>
        </form>

    </div>
</body>
</html>
