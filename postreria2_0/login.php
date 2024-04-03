<?php
include 'conexion.php';

// Verificar las credenciales de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Validar las credenciales consultando la base de datos
    if (validarCredenciales($nombre_usuario, $contrasena)) {
        // Iniciar sesión
        session_start();
        $_SESSION["usuario"] = $nombre_usuario;
        $_SESSION["usuario_id"] = obtenerIDUsuario($nombre_usuario); // Asignar el ID de usuario a la sesión

        // Obtener el rol del usuario desde la base de datos
        $rol_usuario = obtenerRolUsuario($nombre_usuario);

        // Redirigir al usuario dependiendo del rol
        if ($rol_usuario == "admin") {
            header("Location: menu.php");
        } else {
            header("Location: menu.php");
        }
        exit();
    } else {
        echo "Credenciales incorrectas";
    }
}

// Función para validar las credenciales consultando la base de datos
function validarCredenciales($nombre_usuario, $contrasena) {
    global $conn;

    try {
        $consulta = "SELECT contrasena FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si la contraseña proporcionada coincide con la almacenada en la base de datos
        return password_verify($contrasena, $resultado['contrasena']);
    } catch (PDOException $e) {
        die("Error al validar las credenciales: " . $e->getMessage());
    }
}

// Función para obtener el rol del usuario desde la base de datos
function obtenerRolUsuario($usuario) {
    global $conn;

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

// Función para obtener el ID de usuario desde la base de datos
function obtenerIDUsuario($nombre_usuario) {
    global $conn;

    try {
        $consulta = "SELECT id FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['id'];
    } catch (PDOException $e) {
        die("Error al obtener el ID de usuario: " . $e->getMessage());
    }
}
?>
