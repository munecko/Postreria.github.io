<?php
// Inicia la sesión
session_start();
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos


// Verifica si el usuario está autenticado y es administrador
$es_administrador = false; // Supongamos que el usuario no es administrador por defecto
if (isset($_SESSION["usuario"])) {
    // Consulta la base de datos para obtener el rol del usuario
    include 'conexion.php'; // Incluir el archivo de conexión
    $stmt = $conn->prepare("SELECT rol FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->bindParam(':nombre_usuario', $_SESSION["usuario"], PDO::PARAM_STR);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si el rol del usuario es 'admin'
    if ($resultado && $resultado['rol'] == 'admin') {
        $es_administrador = true;
    }
}

// Consulta para obtener todos los productos
try {
    $stmt = $conn->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al recuperar los productos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="estilos/catalogo.css"> 
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
            <li><a class="nav-link" href="catalogo.php">Catálogo</a></li>
            <li><a class="nav-link" href="carrito.php">Carrito</a></li>
            <li><a class="nav-link" href="pedido_usuario.php">Pedidos</a></li>
          
            <!-- Mostrar enlace "Cerrar Sesión" solo si la sesión está iniciada -->
            <?php if(isset($_SESSION["usuario"])) { ?>
                <li><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
            <?php } else { ?>
                <li><a class="nav-link" href="acceso.php">Acceder</a></li>
            <?php } ?>
             <!-- Mostrar botón " Pedido admin" solo si el usuario es administrador -->
             <?php if ($es_administrador) { ?>
                <li><a class="nav-link" href="pedido_admin.php">Pedidos Admin</a></li>
            <?php } ?>

            <!-- Mostrar botón "Agregar Producto" solo si el usuario es administrador -->
            <?php if ($es_administrador) { ?>
                <li><a class="nav-link" href="agregar_producto.php">Agregar Producto</a></li>
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

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="catalogo">
    <?php foreach ($productos as $producto) { ?>
        <div class="producto">
            <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
            <h3><?php echo $producto['nombre']; ?></h3>
            <p><?php echo $producto['descripcion']; ?></p>
            <span class="precio">$<?php echo $producto['precio']; ?></span>
            <form action="agregar_al_carrito.php" method="post">
                <!-- Campos ocultos para enviar los detalles del producto al carrito -->
                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                <input type="hidden" name="nombre" value="<?php echo $producto['nombre']; ?>">
                <input type="hidden" name="descripcion" value="<?php echo $producto['descripcion']; ?>">
                <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                <input type="hidden" name="imagen" value="<?php echo $producto['imagen']; ?>">
                <!-- Campo de cantidad -->
                <input type="number" name="cantidad" value="1" min="1" style="width: 2em;">
                <!-- Botón para añadir al carrito -->
                <input type="submit" value="Añadir al carrito" name="agregar_al_carrito">
            </form>
        </div>
        <br>
    <?php } ?>
</div>




</body>
</html>
