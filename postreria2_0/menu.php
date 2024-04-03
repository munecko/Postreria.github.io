<?php
session_start();
include 'nav_bar.php';

// Verificar si hay una sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: acceso.php");
    exit();
}

// Obtener el rol del usuario
$rol_usuario = obtenerRolUsuario($_SESSION["usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
</head>
<body>
    
</body>
</html>

<?php
// Función para obtener el rol del usuario desde la base de datos
function obtenerRolUsuario($usuario) {
    include 'conexion.php';

    try {
        $consulta = "SELECT rol FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':nombre_usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['rol'];
    } catch (PDOException $e) {
        die("Error al obtener el rol del usuario: " . $e->getMessage());
    }
}
?>
