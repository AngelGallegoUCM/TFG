<?php
// Este script se usa para restablecer la contraseña del usuario administrador
// ¡BORRA ESTE ARCHIVO DESPUÉS DE USARLO!

// Incluir la conexión a la base de datos
include("php/conexion.php");

// Definir la nueva contraseña
$nueva_contrasena = "Admin123!";
$usuario = "admin";

// Generar hash de la contraseña
$hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

// Actualizar la contraseña en la base de datos
$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hash, $usuario);

if ($stmt->execute()) {
    echo "<div style='background-color: #dff0d8; color: #3c763d; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #d6e9c6;'>";
    echo "<h2>¡Contraseña actualizada correctamente!</h2>";
    echo "<p>El usuario <strong>$usuario</strong> ahora tiene la contraseña: <strong>$nueva_contrasena</strong></p>";
    echo "<p>Por favor, elimina este archivo inmediatamente después de usarlo por razones de seguridad.</p>";
    echo "<p>Puedes <a href='login.php' style='color: #3c763d; text-decoration: underline;'>iniciar sesión aquí</a>.</p>";
    echo "</div>";
    
    // Mostrar el hash generado para depuración
    echo "<div style='background-color: #f5f5f5; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #ddd;'>";
    echo "<h3>Información técnica (solo para depuración):</h3>";
    echo "<p>Hash generado: <code style='word-break: break-all;'>$hash</code></p>";
    echo "</div>";
} else {
    echo "<div style='background-color: #f2dede; color: #a94442; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #ebccd1;'>";
    echo "<h2>Error al actualizar la contraseña</h2>";
    echo "<p>No se pudo actualizar la contraseña: " . $stmt->error . "</p>";
    echo "</div>";
}

// Verificar si el usuario existe
$check_stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
$check_stmt->bind_param("s", $usuario);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div style='background-color: #fcf8e3; color: #8a6d3b; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #faebcc;'>";
    echo "<h2>¡Advertencia!</h2>";
    echo "<p>El usuario <strong>$usuario</strong> no existe en la base de datos.</p>";
    echo "<p>¿Quieres crear este usuario ahora?</p>";
    echo "<a href='reset_password.php?create=true' style='display: inline-block; background-color: #8a6d3b; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;'>Crear usuario admin</a>";
    echo "</div>";
}

// Crear el usuario si no existe y se solicita
if (isset($_GET['create']) && $_GET['create'] === 'true') {
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, nombre, rol) VALUES (?, ?, 'Administrador', 'admin')");
    $stmt->bind_param("ss", $usuario, $hash);
    
    if ($stmt->execute()) {
        echo "<div style='background-color: #dff0d8; color: #3c763d; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #d6e9c6;'>";
        echo "<h2>¡Usuario creado correctamente!</h2>";
        echo "<p>Se ha creado el usuario <strong>$usuario</strong> con la contraseña: <strong>$nueva_contrasena</strong></p>";
        echo "<p>Por favor, elimina este archivo inmediatamente después de usarlo por razones de seguridad.</p>";
        echo "<p>Puedes <a href='login.php' style='color: #3c763d; text-decoration: underline;'>iniciar sesión aquí</a>.</p>";
        echo "</div>";
    } else {
        echo "<div style='background-color: #f2dede; color: #a94442; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #ebccd1;'>";
        echo "<h2>Error al crear el usuario</h2>";
        echo "<p>No se pudo crear el usuario: " . $stmt->error . "</p>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Herramienta de restablecimiento de contraseña</h1>
        
        <div class="warning">
            <strong>¡ADVERTENCIA DE SEGURIDAD!</strong>
            <p>Esta herramienta debe usarse solo para restablecer contraseñas de administrador en un entorno controlado.</p>
            <p>Elimina este archivo inmediatamente después de usarlo.</p>
        </div>
    </div>
</body>
</html>