<?php
include 'conexion.php';
// Inicia la sesión
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
    <link rel="stylesheet" href="estilos/carrusel.css">
    <link rel="stylesheet" href="estilos/index.css">

    

    <title>Postreria</title>
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
      <li><a class="nav-link" href="nosotros.php">Nosotros</a></li>
      <li><a class="nav-link" href="catalogo.php">Catalogo</a></li>
      <li><a class="nav-link" href="carrito.php">Carrito</a></li>
      <li><a class="nav-link" href="pedido_usuario.php">Pedidos</a></li>
      <!-- Mostrar botón " Pedido admin" solo si el usuario es administrador -->
      <?php if ($es_admin) { ?>
                <li><a class="nav-link" href="pedido_admin.php">Pedidos Admin</a></li>
            <?php } ?>
     

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
  </header>

   <!--  Encabezado  -->
  <div class="video-container">
  <video src="video1.mp4" autoplay loop muted></video>
  <div class="text-overlay">
    <h1>P A S T E L E R I A</h1>
    <p>Nunca es tarde para un Pastel</p>
  </div>
</div>
<br>
<br>
<br>

  <!--  Div Productos  -->
<div class="fondo">
  <br>
  <br>
<center><h1>Productos Recomendados</h1></center>
<div class="container">
  <div class="card">
    <img src="imagenes/pastel5.jpg" alt="Producto 1">
    <div class="info">
      <h2>Flan Napolitano</h2>
      <p>Consiste en una cremosa mezcla de huevos, leche, azúcar y vainilla</p>
    </div>
  </div>
  
  <div class="card">
    <img src="imagenes/pastel6.jpg" alt="Producto 2">
    <div class="info">
      <h2>Pay de Manzana</h2>
      <p>Postre clásico hecho con una base de masa de pastel, relleno de rodajas de manzana sazonadas con canela y azúcar, y cubierto con una capa de migajas crujientes.</p>
    </div>
  </div>

  <div class="card">
    <img src="imagenes/pastel3.jpg" alt="Producto 2">
    <div class="info">
      <h2>Gelatina de limon</h2>
      <p>Postre refrescante y delicioso hecho a base de gelatina sabor limón.</p>
    </div>
  </div>

  <div class="card">
    <img src="imagenes/pastel4.jpg" alt="Producto 2">
    <div class="info">
      <h2>Capuchino</h2>
      <p>Bebida helada y espumosa que combina café </p>
    </div>
  </div>
</div>
</div>
<br>
<br>
  <!--  Tarjetas para Nosotros  -->
  <center><h1>Historia</h1></center>
<div class="card-container">
  <div class="card">
    <div class="contenido">
      <h2>Como comenzo..</h2>
      <p>La Postreria se fundo en el 2001, empezo como un pasatiempo algo que dar para compartir con mis amigos y familiares asi poco a poco se convirtio en un negocio.
Que ha sido el sabor preferido de la gente por mas de 20 años mediante sus recetas originales y el sabor de pan casero.
Con más de 15 variedades de sus once estilos de preparar pasteles, se han catalogado como los favoritos para quienes lo buscan lo mejor de toda ocasión especial.</p>
    </div>
  </div>
  
  <div class="card">
    <div class="imagen">
      <img src="imagenes/pastel1.jpg" alt="Imagen">
    </div>
  </div>
</div>


<br>
<br>
  <!-- Pie de Pagina  -->
  <footer>
  <div class="container">
    <div class="footer-column">
      <h3>POSTRERIA</h3>
      <ul>
        <li><a href="index.php">Inicio</a></li>
        <li><a href="catalogo.php">Catalogo</a></li>
        <!-- Mostrar enlace "Cerrar Sesión" solo si la sesión está iniciada -->
        <?php if(isset($_SESSION["usuario"])) { ?>
            <li><a href="cerrar_sesion.php">Cerrar Sesión</a></li>
        <?php } else { ?>
            <li><a href="acceso.php">Acceder</a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="footer-column">
      <h3>Contacto</h3>
      <p>Dirección: Calle Principal #123</p>
      <p>Teléfono: (331) 456-7890</
