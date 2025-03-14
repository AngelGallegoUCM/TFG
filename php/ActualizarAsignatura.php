<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre_asignatura = $conn->real_escape_string($_POST['nombre_asignatura']);
    $grupo = $conn->real_escape_string($_POST['grupo']);
    $profesor_id = intval($_POST['profesor_id']);
    $aula_id = intval($_POST['aula_id']);

    // Actualizar los datos de la asignatura
    $query = "
        UPDATE asignaturas 
        SET nombre_asignatura = '$nombre_asignatura', 
            grupo = '$grupo', 
            profesor_id = $profesor_id, 
            aula_id = $aula_id 
        WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        // Eliminar los horarios antiguos de esta asignatura
        $conn->query("DELETE FROM horarios WHERE asignatura_id = $id");

        // Insertar los nuevos horarios
        if (!empty($_POST['dia_semana']) && is_array($_POST['dia_semana'])) {
            $stmt = $conn->prepare("INSERT INTO horarios (asignatura_id, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
            foreach ($_POST['dia_semana'] as $index => $dia) {
                $hora_inicio = $_POST['hora_inicio'][$index];
                $hora_fin = $_POST['hora_fin'][$index];
                $stmt->bind_param("isss", $id, $dia, $hora_inicio, $hora_fin);
                $stmt->execute();
            }
            $stmt->close();
        }

        header("Location: ../ListadoAsignaturas.php");
        exit();
    } else {
        echo "Error al actualizar la asignatura: " . $conn->error;
    }
}
?>
