<?php
session_start();
require_once 'conexion.php'; // Asegúrate de incluir la conexión correctamente

// Verificar si se ha enviado el formulario de agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_al_carrito"])) {
    // Recibir los detalles del producto desde el formulario
    $producto_id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $imagen = $_POST["imagen"];
    $cantidad = $_POST["cantidad"];

    // Paso 1: Verificar la sesión del usuario
    if (!isset($_SESSION['usuario'])) {
        echo "La sesión de usuario no está iniciada.";
        exit;
    }

    // Paso 2: Obtener el ID de usuario de la sesión
    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

    // Paso 3: Verificar si el ID de usuario está disponible
    if ($usuario_id === null) {
        echo "El ID de usuario no está disponible.";
        exit;
    }

    // Paso 4: Insertar los detalles del producto en la tabla 'carrito'
    try {
        $stmt = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id, nombre, descripcion, precio, imagen, cantidad) VALUES (:usuario_id, :producto_id, :nombre, :descripcion, :precio, :imagen, :cantidad)");
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir a la página del carrito o a donde sea apropiado
        header("Location: carrito.php");
        exit;
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al agregar el producto al carrito: " . $e->getMessage();
    }
}

// Depurar la sesión para verificar si el ID de usuario se está asignando correctamente
echo "Contenido de la sesión:<br>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
?>
