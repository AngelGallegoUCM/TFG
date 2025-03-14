<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Aula</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <main class="content">
        <h1>Añadir Aula</h1>

        <!-- Formulario para añadir aula -->
        <form action="php/InsertarAula.php" method="POST" class="form-container">
            <div class="form-group">
                <label for="numero_aula">Número de Aula:</label>
                <input type="number" id="numero_aula" name="numero_aula" required>

                <label for="capacidad">Capacidad:</label>
                <input type="number" id="capacidad" name="capacidad" required>
            </div>

            <!-- Botón para enviar -->
            <button type="submit">Guardar Aula</button>

            <!-- Botón Volver -->
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>
