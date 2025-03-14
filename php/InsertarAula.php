<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_aula = intval($_POST['numero_aula']);
    $capacidad = intval($_POST['capacidad']);

    // Insertar la nueva aula en la base de datos
    $query = "INSERT INTO aulas (numero_aula, capacidad) VALUES ($numero_aula, $capacidad)";

    if ($conn->query($query) === TRUE) {
        echo "Aula añadida correctamente.";
        header("Location: ../ListadoAulas.php"); // Redirigir al listado de aulas tras éxito
        exit();
    } else {
        echo "Error al añadir el aula: " . $conn->error;
    }
}
?>
