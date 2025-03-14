<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellidos = $conn->real_escape_string($_POST['apellidos']);
    $correoPropio = $conn->real_escape_string($_POST['correoPropio']);
    $departamento_id = intval($_POST['departamento_id']);

    // Actualizar los datos del profesor en la base de datos
    $query = "
        UPDATE profesores 
        SET nombre = '$nombre', 
            apellidos = '$apellidos', 
            correoPropio = '$correoPropio', 
            departamento_id = $departamento_id 
        WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        header("Location: ../ListadoProfesores.php"); // Redirigir al listado tras éxito
        exit();
    } else {
        echo "Error al actualizar el profesor: " . $conn->error;
    }
}
?>
