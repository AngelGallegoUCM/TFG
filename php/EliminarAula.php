<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se recibió el ID del aula
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $aula_id = intval($_GET['id']);

    // Consulta para eliminar el aula
    $query = "DELETE FROM aulas WHERE id = $aula_id";

    if ($conn->query($query) === TRUE) {
        echo "Aula eliminada correctamente.";
        header("Location: ../ListadoAulas.php"); // Redirigir al listado después de eliminar
        exit();
    } else {
        echo "Error al eliminar el aula: " . $conn->error;
    }
} else {
    echo "ID del aula no especificado.";
}
?>
