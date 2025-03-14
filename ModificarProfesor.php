<?php
// Conexión a la base de datos
include("php/conexion.php");

// Obtener el ID del profesor desde la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $profesor_id = intval($_GET['id']);

    // Consulta para obtener los datos del profesor
    $query = "SELECT id, nombre, apellidos, correoPropio, departamento_id FROM profesores WHERE id = $profesor_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $profesor = $result->fetch_assoc();
    } else {
        die("Profesor no encontrado.");
    }
} else {
    die("ID del profesor no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Profesor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("php/sidebar.php"); ?> <!-- Incluir el sidebar -->

    <main class="content">
        <h1>Modificar Profesor</h1>

        <!-- Formulario para modificar profesor -->
        <form action="php/ActualizarProfesor.php" method="POST" class="form-container">
            <!-- Campo oculto para enviar el ID del profesor -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($profesor['id']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($profesor['apellidos']); ?>" required>

                <label for="correoPropio">Correo Propio:</label>
                <input type="email" id="correoPropio" name="correoPropio" value="<?php echo htmlspecialchars($profesor['correoPropio']); ?>" required>

                <!-- Selector de Departamento -->
                <?php
                $query_departamentos = "SELECT id, nombre_departamento FROM departamento";
                $result_departamentos = $conn->query($query_departamentos);
                ?>
                <label for="departamento_id">Departamento:</label>
                <select id="departamento_id" name="departamento_id" required>
                    <?php while ($departamento = $result_departamentos->fetch_assoc()) { ?>
                        <option value="<?php echo $departamento['id']; ?>" <?php if ($profesor['departamento_id'] == $departamento['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($departamento['nombre_departamento']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Botones -->
            <button type="submit">Guardar Cambios</button>
            <button type="button" onclick="history.back()">Volver</button>
        </form>

    </main>
</body>
</html>