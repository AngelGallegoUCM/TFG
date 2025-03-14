<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se recibió el ID del día no lectivo
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $nolectivo_id = intval($_GET['id']);

    // Eliminar el día no lectivo
    $query = "DELETE FROM nolectivo WHERE id = $nolectivo_id";

    if ($conn->query($query) === TRUE) {
        echo "Día no lectivo eliminado correctamente.";
        header("Location: ../ListadoNoLectivo.php"); // Redirigir al listado después de eliminar
        exit();
    } else {
        echo "Error al eliminar el día no lectivo: " . $conn->error;
    }
} else {
    echo "ID del día no lectivo no especificado.";
}
?>
