<?php
// EnviarCorreo.php

if (isset($_GET['id']) && isset($_GET['correo_profesor'])) {
    $id = $_GET['id'];
    $correoProfesor = urldecode($_GET['correo_profesor']);

    // Conexión a la base de datos
    include("conexion.php");

    // Consulta para obtener detalles de la incidencia
    $query = "
        SELECT i.fecha_incidencia, a.nombre_asignatura
        FROM incidencias i
        JOIN asistencias s ON i.asistencia_id = s.id
        JOIN asignaturas a ON s.asignatura_id = a.id
        WHERE i.id = $id
    ";

    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    // Configuración básica para enviar un correo electrónico
    $asunto = "Incidencia no justificada";
    $mensaje = "Se ha producido una incidencia no justificada el día " . date('d/m/Y', strtotime($row['fecha_incidencia'])) . " en la asignatura " . $row['nombre_asignatura'] . " con el ID $id.";
    $cabeceras = "From: tu_correo@example.com\r\n";
    $cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envía el correo electrónico
    mail($correoProfesor, $asunto, $mensaje, $cabeceras);

    echo "Correo electrónico enviado correctamente.";
} else {
    echo "No se han proporcionado los parámetros necesarios.";
}

$conn->close();
?>
