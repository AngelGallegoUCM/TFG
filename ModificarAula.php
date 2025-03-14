<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Aula</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <?php
    // Conexión a la base de datos
    include("php/conexion.php");

    // Obtener el ID del aula desde la URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $aula_id = intval($_GET['id']);

        // Consulta para obtener los datos del aula
        $query = "SELECT * FROM aulas WHERE id = $aula_id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $aula = $result->fetch_assoc();
        } else {
            die("Aula no encontrada.");
        }
    } else {
        die("ID del aula no especificado.");
    }
    ?>

    <main class="content">
        <h1>Modificar Aula</h1>

        <!-- Formulario para modificar aula -->
        <form action="php/ActualizarAula.php" method="POST" class="form-container">
            <!-- Campo oculto para enviar el ID del aula -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($aula['id']); ?>">

            <div class="form-group">
                <label for="numero_aula">Número de Aula:</label>
                <input type="number" id="numero_aula" name="numero_aula" value="<?php echo htmlspecialchars($aula['numero_aula']); ?>" required>

                <label for="capacidad">Capacidad:</label>
                <input type="number" id="capacidad" name="capacidad" value="<?php echo htmlspecialchars($aula['capacidad']); ?>" required>
            </div>

            <!-- Botones -->
            <button type="submit">Guardar Cambios</button>
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>
