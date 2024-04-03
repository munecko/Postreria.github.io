<?php
session_start();

// Vaciar el carrito
unset($_SESSION["carrito"]);

// Redirigir de vuelta a la página del carrito
header("Location: carrito.php");
exit;
?>
