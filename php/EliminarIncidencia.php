<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se recibió el ID de la incidencia
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $incidencia_id = intval($_GET['id']);

    // Consulta para eliminar la incidencia
    $query = "DELETE FROM incidencias WHERE id = $incidencia_id";

    if ($conn->query($query) === TRUE) {
        header("Location: ../ListadoIncidencias.php"); // Redirigir al listado después de eliminar
        exit();
    } else {
        echo "Error al eliminar la incidencia: " . $conn->error;
    }
} else {
    echo "ID de la incidencia no especificado.";
}
?>
