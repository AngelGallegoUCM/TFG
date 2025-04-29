<?php
// Iniciar sesión y verificar autenticación
require_once("php/verificar_sesion.php");
verificarSesion();

// Verificar si el usuario tiene permisos (admin o editor)
verificarRol(['admin', 'editor']);

// Conexión a la base de datos
include("php/conexion.php");

// Obtener el ID del profesor desde la URL y validarlo
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $profesor_id = intval($_GET['id']);

    try {
        // Consulta preparada para obtener los datos del profesor
        $stmt = $conn->prepare("SELECT id, nombre, apellidos, correoPropio, departamento_id FROM profesores WHERE id = ?");
        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $profesor = $result->fetch_assoc();
        } else {
            die("Profesor no encontrado.");
        }
    } catch (Exception $e) {
        die("Error al obtener datos del profesor: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("ID del profesor no especificado o inválido.");
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
                <input type="text" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($profesor['nombre']); ?>"
                       pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" 
                       title="Solo se permiten letras y espacios"
                       maxlength="100" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" 
                       value="<?php echo htmlspecialchars($profesor['apellidos']); ?>"
                       pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+" 
                       title="Solo se permiten letras y espacios"
                       maxlength="150" required>

                <label for="correoPropio">Correo Propio:</label>
                <input type="email" id="correoPropio" name="correoPropio" 
                       value="<?php echo htmlspecialchars($profesor['correoPropio']); ?>"
                       maxlength="255" required>

                <!-- Selector de Departamento -->
                <?php
                $query_departamentos = "SELECT id, nombre_departamento FROM departamento ORDER BY nombre_departamento";
                $stmt_departamentos = $conn->prepare($query_departamentos);
                $stmt_departamentos->execute();
                $result_departamentos = $stmt_departamentos->get_result();
                ?>
                <label for="departamento_id">Departamento:</label>
                <select id="departamento_id" name="departamento_id" required>
                    <?php while ($departamento = $result_departamentos->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($departamento['id']); ?>" 
                                <?php if ($profesor['departamento_id'] == $departamento['id']) echo 'selected'; ?>>
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