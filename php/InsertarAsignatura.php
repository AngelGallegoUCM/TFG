<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_asignatura = $conn->real_escape_string($_POST['nombre_asignatura']);
    $grupo = $conn->real_escape_string($_POST['grupo']);
    $profesor_id = intval($_POST['profesor_id']);
    $aula_id = intval($_POST['aula_id']);

    // Insertar la nueva asignatura en la base de datos
    $query = "INSERT INTO asignaturas (nombre_asignatura, grupo, profesor_id, aula_id) 
              VALUES ('$nombre_asignatura', '$grupo', $profesor_id, $aula_id)";

    if ($conn->query($query) === TRUE) {
        $asignatura_id = $conn->insert_id; // Obtener el ID de la asignatura recién creada

        // Insertar los horarios relacionados
        if (!empty($_POST['dia_semana']) && !empty($_POST['hora_inicio']) && !empty($_POST['hora_fin'])) {
            for ($i = 0; $i < count($_POST['dia_semana']); $i++) {
                $dia_semana = $conn->real_escape_string($_POST['dia_semana'][$i]);
                $hora_inicio = $conn->real_escape_string($_POST['hora_inicio'][$i]);
                $hora_fin = $conn->real_escape_string($_POST['hora_fin'][$i]);

                $query_horario = "INSERT INTO horarios (asignatura_id, dia_semana, hora_inicio, hora_fin) 
                                  VALUES ($asignatura_id, '$dia_semana', '$hora_inicio', '$hora_fin')";
                $conn->query($query_horario);
            }
        }

        header("Location: ../ListadoAsignaturas.php"); // Redirigir al listado tras éxito
        exit();
    } else {
        echo "Error al añadir la asignatura: " . $conn->error;
    }
}
?>
