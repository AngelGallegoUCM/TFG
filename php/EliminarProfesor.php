<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se recibió el ID del profesor
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $profesor_id = intval($_GET['id']);

    // Consulta para eliminar el profesor
    $query = "DELETE FROM profesores WHERE id = $profesor_id";

    if ($conn->query($query) === TRUE) {
        echo "Profesor eliminado correctamente.";
        header("Location: ../ListadoProfesores.php"); // Redirigir al listado después de eliminar
        exit();
    } else {
        echo "Error al eliminar el profesor: " . $conn->error;
    }
} else {
    echo "ID del profesor no especificado.";
}
?>
