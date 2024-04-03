<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_de_datos = 'postreria';

try {
    // Intentar establecer la conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$base_de_datos", $usuario, $contrasena);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar si la conexión se estableció correctamente
    if (!$conn) {
        throw new Exception("No se pudo establecer la conexión a la base de datos.");
    }
} catch (PDOException $e) {
    // Manejar los errores de conexión
    die("Error de conexión: " . $e->getMessage());
} catch (Exception $ex) {
    // Manejar otros errores
    die("Error: " . $ex->getMessage());
}

// Verificar si la sesión se ha iniciado correctamente
if (!isset($_SESSION)) {
    session_start();
}

// Ahora puedes continuar con el resto de tu código...
?>
