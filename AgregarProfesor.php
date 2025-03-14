<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Profesor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <main class="content">
        <h1>Añadir Profesor</h1>

        <?php
        // Conexión a la base de datos
        include("php/conexion.php");

        // Consulta para obtener los departamentos
        $query_departamentos = "SELECT id, nombre_departamento FROM departamento";
        $result_departamentos = $conn->query($query_departamentos);
        ?>

        <!-- Formulario para añadir profesor -->
        <form action="php/InsertarProfesor.php" method="POST" class="form-container">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>

                <label for="correoPropio">Correo Propio:</label>
                <input type="email" id="correoPropio" name="correoPropio" required>

                <!-- Selector de Departamento -->
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento_id" required>
                    <?php
                    if ($result_departamentos && $result_departamentos->num_rows > 0) {
                        while ($departamento = $result_departamentos->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($departamento['id']) . "'>" . htmlspecialchars($departamento['nombre_departamento']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay departamentos disponibles</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Botón para enviar -->
            <button type="submit">Guardar Profesor</button>

            <!-- Botón Volver -->
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>
