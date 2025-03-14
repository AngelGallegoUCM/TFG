<?php
// Conexión a la base de datos
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $justificacion = $conn->real_escape_string($_POST['justificacion']);

    // Actualizar la incidencia como justificada
    $query = "UPDATE incidencias SET justificada = 1, descripcion = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $justificacion, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Incidencia justificada correctamente.'); window.location.href='../ListadoIncidencias.php';</script>";
    } else {
        echo "<script>alert('Error al justificar la incidencia.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Acceso no permitido.'); window.history.back();</script>";
}
?>
