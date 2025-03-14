<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se recibió el ID de la asignatura
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $asignatura_id = intval($_GET['id']);

    // Eliminar los horarios relacionados con la asignatura
    $query_horarios = "DELETE FROM horarios WHERE asignatura_id = $asignatura_id";
    $conn->query($query_horarios);

    // Eliminar la asignatura
    $query_asignatura = "DELETE FROM asignaturas WHERE id = $asignatura_id";

    if ($conn->query($query_asignatura) === TRUE) {
        echo "Asignatura eliminada correctamente.";
        header("Location: ../ListadoAsignaturas.php"); // Redirigir al listado después de eliminar
        exit();
    } else {
        echo "Error al eliminar la asignatura: " . $conn->error;
    }
} else {
    echo "ID de la asignatura no especificado.";
}
?>
