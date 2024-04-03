<?php
include 'conexion.php';

// Verifica si el usuario está autenticado y es administrador
$es_admin = false; // Supongamos que el usuario no es administrador por defecto
if (isset($_SESSION["usuario"])) {
    // Consulta la base de datos para obtener el rol del usuario
    $stmt = $conn->prepare("SELECT rol FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->bindParam(':nombre_usuario', $_SESSION["usuario"], PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si el rol del usuario es 'admin'
    if ($resultado && $resultado['rol'] == 'admin') {
        $es_admin = true;
    }
  }

?>

 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/nav_bar.css">
</head>
<body>
<header id="header">
  <!--  Brand Logo  -->
  <a class="nav-brand" href="" target="_blank">
    <img id="header-img" src="imagenes/logo_lapostrería.png" alt="Pixel Skincare">
  </a>
  
  <!--  Toggle Menu for Mobile  -->
  
  <input type="checkbox" id="toggle-menu" role="button">
  <label for="toggle-menu" class="toggle-menu">Menu</label>
  
  <!--  Menus  -->
  <nav id="nav-bar" class="navbar">
    <ul class="menu">
      <li><a class="nav-link" href="index.php">Inicio</a></li>
      <li><a class="nav-link" href="#collections">Collections</a></li>
      <li><a class="nav-link" href="nosotros.php">Nosotros</a></li>
      <li><a class="nav-link" href="catalogo.php">Catalogo</a></li>
      <li><a class="nav-link" href="carrito.php">Carrito</a></li>
      <li><a class="nav-link" href="pedido_usuario.php">Pedidos</a></li>
      <!-- Mostrar botón " Pedido admin" solo si el usuario es administrador -->
      <?php if ($es_admin) { ?>
                <li><a class="nav-link" href="pedido_admin.php">Pedidos Admin</a></li>
            <?php } ?>
      
    
      <!-- Mostrar enlace "Cerrar Sesión" y mensaje de bienvenida solo si la sesión está iniciada -->
      <?php if(isset($_SESSION["usuario"])) { ?>
          <li><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
      <?php } ?>
    </ul>
    <ul class="social-menu">
      <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
      <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
    </ul>
  </nav>
</header>
</body>
</html>