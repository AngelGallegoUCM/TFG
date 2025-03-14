<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $numero_aula = intval($_POST['numero_aula']);
    $capacidad = intval($_POST['capacidad']);

    // Actualizar los datos del aula en la base de datos
    $query = "
        UPDATE aulas 
        SET numero_aula = $numero_aula, 
            capacidad = $capacidad 
        WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        header("Location: ../ListadoAulas.php"); // Redirigir al listado tras éxito
        exit();
    } else {
        echo "Error al actualizar el aula: " . $conn->error;
    }
}
?>
