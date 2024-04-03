<?php
include 'conexion.php'; // Asegúrate de incluir la conexión a la base de datos


// Verificar si se envió el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = limpiarDatos($_POST["usuario"]);
    $contrasena = limpiarDatos($_POST["contrasena"]);

    // Verificar si el usuario ya existe
    if (usuarioExiste($usuario)) {
        echo "El nombre de usuario ya está registrado. Por favor, elige otro.";
    } else {
        // Obtener el rol seleccionado en el formulario (por defecto 'cliente' si no se selecciona nada)
        $rol = isset($_POST["rol"]) ? limpiarDatos($_POST["rol"]) : 'cliente';

        // Verificar la sesión del usuario y su rol antes de asignar 'admin'
        if ($rol !== 'cliente' && isset($_SESSION["usuario"]) && $_SESSION["usuario"] == "admin") {
            $rol = 'admin';
        }

        // Registrar al nuevo usuario
        registrarUsuario($usuario, $contrasena, $rol);
        echo "¡Registro exitoso! Ahora puedes iniciar sesión.";
    }
}

// Función para verificar si un usuario ya existe
function usuarioExiste($nombre_usuario) {
    global $conn;

    try {
        $consulta = "SELECT COUNT(*) as cantidad FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['cantidad'] > 0;
    } catch (PDOException $e) {
        die("Error al verificar la existencia del usuario: " . $e->getMessage());
    }
}

// Función para registrar un nuevo usuario con rol específico
function registrarUsuario($nombre_usuario, $contrasena, $rol) {
    global $conn;

    try {
        // Lógica para almacenar la contraseña de forma segura (usando hash)
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        // Inserción del nuevo usuario en la base de datos con rol específico
        $consulta = "INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES (:nombre_usuario, :contrasena, :rol)";
        $stmt = $conn->prepare($consulta);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
        $stmt->execute();

        echo "Registro exitoso"; // Puedes agregar un mensaje de éxito
    } catch (PDOException $e) {
        die("Error al registrar el nuevo usuario: " . $e->getMessage());
    }
}

// Función para limpiar los datos de entrada
function limpiarDatos($dato) {
    return htmlspecialchars(trim($dato));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>
    <form action="registro.php" method="post">
        Usuario: <input type="text" name="usuario" required><br>
        Contraseña: <input type="password" name="contrasena" required><br>
        
        <!-- Campo para seleccionar el rol -->
        Rol: 
        <select name="rol" required>
            <option value="cliente">Cliente</option>
            <option value="admin">Administrador</option>
        </select><br>

        <input type="submit" value="Registrarse">
    </form>
    <p>¿Ya tienes una cuenta? <a href="acceso.php">Inicia sesión</a></p>
</body>
</html>
