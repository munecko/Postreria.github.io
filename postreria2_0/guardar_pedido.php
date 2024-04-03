<?php
 session_start();
include 'conexion.php';


?>
 <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CARRITO</title>
  <link rel="stylesheet" href="estilos/nav_bar.css">
</head>
<body>
    
<header id="header">
  <!--  Brand Logo  -->
  <a class="nav-brand" href="" target="_blank">
    <img id="header-img" src="imagenes/logo_lapostrería.png" alt="Pixel Skincare">
  </a>
  
  <!--  Menus  -->
  <nav id="nav-bar" class="navbar">
    <ul class="menu">
      <li><a class="nav-link" href="index.php">Inicio</a></li>
      <li><a class="nav-link" href="nosotros.php">Nosotros</a></li>
      <li><a class="nav-link" href="catalogo.php">Catalogo</a></li>
      <li><a class="nav-link" href="carrito.php">Carrito</a></li>
      <!-- Mostrar enlace "Cerrar Sesión" solo si la sesión está iniciada -->
      <?php if(isset($_SESSION["usuario"])) { ?>
          <li><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
      <?php } else { ?>
          <li><a class="nav-link" href="acceso.php">Acceder</a></li>
      <?php } ?>
    </ul>
    <ul class="social-menu">
      <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
    </ul>
  </nav>
 
  </header><br><br><br><br><br><br><br><br><br>
  
  <?php

// Verifica si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si el modo de pago está establecido y es válido
    if (isset($_POST["modo_pago"]) && ($_POST["modo_pago"] == "efectivo" || $_POST["modo_pago"] == "tarjeta_credito" || $_POST["modo_pago"] == "transferencia_bancaria")) {
        // Obtiene el modo de pago seleccionado
        $modo_pago = $_POST["modo_pago"];

        // Verifica si el usuario está autenticado y se tiene acceso a su ID
        if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario_id"])) {
            $usuario_id = $_SESSION["usuario_id"]; // Cambio: Utiliza $_SESSION["usuario_id"] en lugar de $_SESSION["usuario"]["id"]

            try {
                // Inicia una transacción
                $conn->beginTransaction();

                // Consulta SQL para insertar el pedido en la tabla de pedidos
                $sql_pedido = "INSERT INTO pedidos (usuario_id, modo_pago) VALUES (:usuario_id, :modo_pago)";
                $stmt_pedido = $conn->prepare($sql_pedido);
                $stmt_pedido->bindParam(':usuario_id', $usuario_id);
                $stmt_pedido->bindParam(':modo_pago', $modo_pago);
                $stmt_pedido->execute();
                $pedido_id = $conn->lastInsertId();

                // Consulta SQL para seleccionar los productos del carrito del usuario
                $sql_carrito = "SELECT * FROM carrito WHERE usuario_id = :usuario_id";
                $stmt_carrito = $conn->prepare($sql_carrito);
                $stmt_carrito->bindParam(':usuario_id', $usuario_id);
                $stmt_carrito->execute();

                $total_pagar = 0;

                // Itera sobre cada producto del carrito
                while ($producto = $stmt_carrito->fetch(PDO::FETCH_ASSOC)) {
                    // Calcula el subtotal del producto
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total_pagar += $subtotal;

                    // Consulta SQL para insertar el producto en la tabla de detalles de pedidos
                    $sql_detalle_pedido = "INSERT INTO detalle_pedidos (pedido_id, producto_id, nombre, descripcion, precio, imagen, cantidad, subtotal) VALUES (:pedido_id, :producto_id, :nombre, :descripcion, :precio, :imagen, :cantidad, :subtotal)";
                    $stmt_detalle_pedido = $conn->prepare($sql_detalle_pedido);
                    $stmt_detalle_pedido->bindParam(':pedido_id', $pedido_id);
                    $stmt_detalle_pedido->bindParam(':producto_id', $producto['producto_id']);
                    $stmt_detalle_pedido->bindParam(':nombre', $producto['nombre']);
                    $stmt_detalle_pedido->bindParam(':descripcion', $producto['descripcion']);
                    $stmt_detalle_pedido->bindParam(':precio', $producto['precio']);
                    $stmt_detalle_pedido->bindParam(':imagen', $producto['imagen']);
                    $stmt_detalle_pedido->bindParam(':cantidad', $producto['cantidad']);
                    $stmt_detalle_pedido->bindParam(':subtotal', $subtotal);
                    $stmt_detalle_pedido->execute();
                }

                // Vacía el carrito después de completar el pedido
                $sql_vaciar_carrito = "DELETE FROM carrito WHERE usuario_id = :usuario_id";
                $stmt_vaciar_carrito = $conn->prepare($sql_vaciar_carrito);
                $stmt_vaciar_carrito->bindParam(':usuario_id', $usuario_id);
                $stmt_vaciar_carrito->execute();

                // Confirma la transacción
                $conn->commit();

                // Muestra el mensaje de éxito y los detalles del pedido
                echo "Pedido guardado correctamente. Total a pagar: $total_pagar <br>";

                // Consulta SQL para obtener los detalles del pedido recién creado
                $sql_detalles_pedido = "SELECT * FROM detalle_pedidos WHERE pedido_id = :pedido_id";
                $stmt_detalles_pedido = $conn->prepare($sql_detalles_pedido);
                $stmt_detalles_pedido->bindParam(':pedido_id', $pedido_id);
                $stmt_detalles_pedido->execute();

                // Imprimir los detalles del pedido
                echo "<h2>Detalles del pedido:</h2>";
                echo "<ul>";
                while ($detalle_pedido = $stmt_detalles_pedido->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>Producto: " . $detalle_pedido['nombre'] . " - Cantidad: " . $detalle_pedido['cantidad'] . " - Subtotal: $" . $detalle_pedido['subtotal'] . "</li>";
                    // Mostrar la imagen del producto
                    echo '<img src="' . $detalle_pedido['imagen'] . '" alt="' . $detalle_pedido['nombre'] . '" width="100" height="100">';
                }
                echo "</ul>";
            } catch (PDOException $e) {
                // Manejo de errores: Si ocurre algún error, deshace la transacción
                $conn->rollBack();
                echo "Error al procesar el pedido: " . $e->getMessage();
            }
        } else {
            // Usuario no autenticado o falta el ID del usuario en la sesión
            echo "Error: Usuario no autenticado o ID de usuario no disponible.";
        }
    } else {
        // Modo de pago no válido
        echo "Modo de pago no válido.";
    }
} else {
    // Si no se ha enviado una solicitud POST, redirige a la página de inicio o muestra un mensaje de error
    echo "Acceso no autorizado.";
}
?>
