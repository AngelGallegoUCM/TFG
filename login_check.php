<?php
// Este script verifica la funcionalidad de autenticación
// Solo para propósitos de diagnóstico
// ¡BORRA ESTE ARCHIVO DESPUÉS DE USARLO!

// Incluir la conexión a la base de datos
include("php/conexion.php");

echo "<h1>Diagnóstico de autenticación</h1>";

// 1. Verificar conexión a la base de datos
echo "<h2>1. Verificando conexión a la base de datos...</h2>";
if ($conn && !$conn->connect_error) {
    echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa.</p>";
} else {
    echo "<p style='color: red;'>✗ Error de conexión a la base de datos: " . $conn->connect_error . "</p>";
    exit();
}

// 2. Verificar la existencia de la tabla usuarios
echo "<h2>2. Verificando la tabla 'usuarios'...</h2>";
$result = $conn->query("SHOW TABLES LIKE 'usuarios'");
if ($result && $result->num_rows > 0) {
    echo "<p style='color: green;'>✓ La tabla 'usuarios' existe.</p>";
} else {
    echo "<p style='color: red;'>✗ La tabla 'usuarios' no existe. Necesitas crearla.</p>";
    echo "<pre>
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'editor', 'lector') NOT NULL DEFAULT 'lector',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    </pre>";
}

// 3. Verificar usuarios en la tabla
echo "<h2>3. Verificando usuarios en la tabla...</h2>";
$result = $conn->query("SELECT id, username, password, rol FROM usuarios");
if ($result) {
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Se encontraron " . $result->num_rows . " usuario(s) en la tabla.</p>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Hash de contraseña (primeros 20 caracteres)</th><th>Rol</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars(substr($row['password'], 0, 20)) . "...</td>";
            echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p style='color: red;'>✗ No se encontraron usuarios en la tabla.</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Error al consultar usuarios: " . $conn->error . "</p>";
}

// 4. Probar la función password_verify
echo "<h2>4. Prueba de la función password_verify...</h2>";
if (function_exists('password_verify')) {
    echo "<p style='color: green;'>✓ La función password_verify está disponible.</p>";
    
    // Crear un hash de ejemplo
    $test_password = "Admin123!";
    $test_hash = password_hash($test_password, PASSWORD_DEFAULT);
    
    echo "<p>Hash de prueba para '$test_password': $test_hash</p>";
    
    // Verificar el hash
    if (password_verify($test_password, $test_hash)) {
        echo "<p style='color: green;'>✓ La función password_verify funciona correctamente.</p>";
    } else {
        echo "<p style='color: red;'>✗ La función password_verify no funciona correctamente.</p>";
    }
    
    // Verificar una contraseña contra un hash existente
    echo "<h3>Verificando 'Admin123!' contra usuarios existentes:</h3>";
    
    $test_password = "Admin123!";
    $result = $conn->query("SELECT id, username, password FROM usuarios");
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $verify_result = password_verify($test_password, $row['password']);
            echo "<p>Usuario: " . htmlspecialchars($row['username']) . " - Verificación: ";
            if ($verify_result) {
                echo "<span style='color: green;'>CORRECTO</span> (La contraseña 'Admin123!' es válida para este usuario)";
            } else {
                echo "<span style='color: red;'>FALLIDO</span> (La contraseña 'Admin123!' NO es válida para este usuario)";
            }
            echo "</p>";
        }
    }
} else {
    echo "<p style='color: red;'>✗ La función password_verify no está disponible. Esto indica un problema con la versión de PHP (necesita PHP 5.5 o superior).</p>";
}

// 5. Verificar la existencia y configuración del archivo verificar_sesion.php
echo "<h2>5. Verificando archivo 'verificar_sesion.php'...</h2>";
if (file_exists("php/verificar_sesion.php")) {
    echo "<p style='color: green;'>✓ El archivo 'php/verificar_sesion.php' existe.</p>";
} else {
    echo "<p style='color: red;'>✗ El archivo 'php/verificar_sesion.php' no existe. Este archivo es necesario para la autenticación.</p>";
}

// 6. Verificar la existencia y contenido del archivo login.php
echo "<h2>6. Verificando archivo 'login.php'...</h2>";
if (file_exists("login.php")) {
    echo "<p style='color: green;'>✓ El archivo 'login.php' existe.</p>";
    
    // Intentar leer el contenido para verificar partes críticas
    $login_content = file_get_contents("login.php");
    if ($login_content !== false) {
        if (strpos($login_content, "password_verify") !== false) {
            echo "<p style='color: green;'>✓ El archivo contiene la función password_verify.</p>";
        } else {
            echo "<p style='color: red;'>✗ El archivo NO contiene la función password_verify.</p>";
        }
        
        if (strpos($login_content, "prepare") !== false) {
            echo "<p style='color: green;'>✓ El archivo utiliza consultas preparadas.</p>";
        } else {
            echo "<p style='color: red;'>✗ El archivo NO utiliza consultas preparadas, lo que podría ser un riesgo de seguridad.</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ No se pudo leer el contenido del archivo 'login.php'.</p>";
    }
} else {
    echo "<p style='color: red;'>✗ El archivo 'login.php' no existe. Este archivo es necesario para la autenticación.</p>";
}
?>

<div style="margin-top: 30px; padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">
    <h2 style="color: #721c24;">¡ADVERTENCIA DE SEGURIDAD!</h2>
    <p>Este script muestra información sensible sobre la configuración de autenticación.</p>
    <p><strong>Elimina este archivo inmediatamente después de usarlo.</strong></p>
</div>