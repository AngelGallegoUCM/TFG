<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $correoPropio = $conn->real_escape_string($_POST['correoPropio']);
    $departamento_id = intval($_POST['departamento_id']);

    // Insertar el nuevo profesor en la base de datos
    $query = "INSERT INTO profesores (nombre, apellidos, correoPropio, departamento_id) 
              VALUES ('$nombre', '$apellidos', '$correoPropio', $departamento_id)";

    if ($conn->query($query) === TRUE) {
        echo "Profesor añadido correctamente.";
        header("Location: ../ListadoProfesores.php"); // Redirigir al listado de profesores
        exit();
    } else {
        echo "Error al añadir el profesor: " . $conn->error;
    }
}
?>