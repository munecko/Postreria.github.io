<?php
session_start();
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php"); // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Consulta SQL para obtener los pedidos del usuario
$sql_pedidos_usuario = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id";
$stmt_pedidos_usuario = $conn->prepare($sql_pedidos_usuario);
$stmt_pedidos_usuario->bindParam(':usuario_id', $usuario_id);
$stmt_pedidos_usuario->execute();
$pedidos_usuario = $stmt_pedidos_usuario->fetchAll(PDO::FETCH_ASSOC);


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
 
  </header><br><br><br><br><br><br><br><br><br>
  <h1>Mis Pedidos</h1>
    <table>
        <tr>
            <th>ID del Pedido</th>
            <th>Modo de Pago</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($pedidos_usuario as $pedido): ?>
            <tr>
                <td><?php echo $pedido['id']; ?></td>
                <td><?php echo $pedido['modo_pago']; ?></td>
                <td><?php echo $pedido['fecha_pedido']; ?></td>
                <td><a href="eliminar_pedido.php?id=<?php echo $pedido['id']; ?>">Eliminar Pedido</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>