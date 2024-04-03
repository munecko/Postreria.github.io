<?php
session_start();
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Verifica si la sesión está iniciada
if (!isset($_SESSION["usuario"])) {
    // Si la sesión no está iniciada, redirige a la página de acceso
    header("Location: acceso.php");
    exit;
}

// Verifica si se ha enviado una solicitud POST y si se ha recibido un ID de producto válido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["producto_id"])) {
    // Obtén el ID del producto a eliminar
    $producto_id = $_POST["producto_id"];
    
    // Verifica si $_SESSION["carrito"] está inicializada y si el producto a eliminar está presente en ella
    if(isset($_SESSION["carrito"]) && isset($_SESSION["carrito"][$producto_id])) {
        // Elimina el producto del carrito
        unset($_SESSION["carrito"][$producto_id]);
        
        // Mensaje de depuración
        echo "Producto con ID $producto_id eliminado del carrito correctamente.";

        // Redirige de vuelta a la página del carrito
        header("Location: carrito.php");
        exit;
    } else {
        // Si el producto no está presente en el carrito, redirige a la página del carrito
        header("Location: carrito.php");
        exit;
    }
} else {
    // Si no se recibe un ID de producto válido, redirige a la página del carrito
    header("Location: carrito.php");
    exit;
}
?>
