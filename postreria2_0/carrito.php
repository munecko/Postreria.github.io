 
 <?php
 session_start();
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

      <<!-- Verificar si el usuario es administrador para mostrar el enlace de Pedidos admin -->
      <?php if(isset($_SESSION["usuario"]) && $_SESSION["usuario_id"] === "admin") { ?>
            <li><a class="nav-link" href="pedido_admin.php">Pedidos admin</a></li>
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

  <form action="vaciar_carrito.php" method="post">
    <input type="submit" value="Vaciar carrito">
</form>


  <?php
// Consulta SQL para seleccionar todos los registros de la tabla carrito
$sql = "SELECT * FROM carrito";

// Ejecuta la consulta
$resultado = $conn->query($sql);


include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Consulta SQL para seleccionar todos los registros de la tabla carrito
$sql = "SELECT * FROM carrito";

// Ejecuta la consulta
$resultado = $conn->query($sql);


include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Consulta SQL para seleccionar todos los registros de la tabla carrito
$sql = "SELECT c.*, u.id AS usuario_id FROM carrito c JOIN usuarios u ON c.usuario_id = u.id";

// Ejecuta la consulta
$resultado = $conn->query($sql);

// Verifica si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["producto_id"])) {
    // Obtén el ID del producto a agregar al carrito
    $producto_id = $_POST["producto_id"];

    // Verifica si hay una sesión de usuario activa
    if (isset($_SESSION["usuario"])) {
        // Obtiene el ID de usuario de la sesión
        $usuario_id = $_SESSION["usuario"]["id"];

        // Consulta SQL para insertar el producto en el carrito
        $sql = "INSERT INTO carrito (producto_id, usuario_id) VALUES (:producto_id, :usuario_id)";

        // Prepara la consulta
        $stmt = $conn->prepare($sql);

        // Vincula los parámetros
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':usuario_id', $usuario_id);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            // Producto agregado al carrito correctamente
            echo "Producto agregado al carrito.";
        } else {
            // Error al agregar el producto al carrito
            echo "Error al agregar el producto al carrito.";
        }
    } else {
        // Si no hay una sesión de usuario activa, redirige a la página de inicio de sesión o muestra un mensaje de error
        echo "Inicia sesión para agregar productos al carrito.";
    }
}

// Consulta SQL para seleccionar todos los registros de la tabla carrito
$sql = "SELECT * FROM carrito";

// Ejecuta la consulta
$resultado = $conn->query($sql);

// Verifica si hay resultados
if ($resultado && $resultado->rowCount() > 0) {
    // Comienza la tabla con estilos más compactos
    echo "<table style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th style='border: 1px solid black; padding: 5px;'>ID</th><th style='border: 1px solid black; padding: 5px;'>Usuario ID</th><th style='border: 1px solid black; padding: 5px;'>Producto ID</th><th style='border: 1px solid black; padding: 5px;'>Nombre</th><th style='border: 1px solid black; padding: 5px;'>Descripción</th><th style='border: 1px solid black; padding: 5px;'>Precio</th><th style='border: 1px solid black; padding: 5px;'>Imagen</th><th style='border: 1px solid black; padding: 5px;'>Cantidad</th><th style='border: 1px solid black; padding: 5px;'>Fecha de Creación</th><th style='border: 1px solid black; padding: 5px;'>Acciones</th></tr>";
    
    // Itera sobre cada fila de resultados
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        // Imprime cada fila como una fila de la tabla
        echo "<tr>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["id"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["usuario_id"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["producto_id"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["nombre"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["descripcion"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>$" . $fila["precio"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'><img src='" . $fila["imagen"] . "' alt='Imagen del producto' style='max-width: 100px; max-height: 100px;'></td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["cantidad"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 1px;'>" . $fila["creado_en"] . "</td>";
        // Agregar botón de eliminar
        echo "<td style='border: 1px solid black; padding: 1px;'><form action='eliminar_producto.php' method='post'><input type='hidden' name='producto_id' value='" . $fila["id"] . "'><input type='submit' value='Eliminar'></form></td>";
        echo "</tr>";
    }
    
    // Cierra la tabla
    echo "</table>";

    // Agregar formulario para seleccionar el modo de pago y enviar los datos a la base de datos de pedidos
    echo "<br><br>";
    echo "<form action='guardar_pedido.php' method='post'>";
    echo "<label for='modo_pago'>Selecciona el modo de pago:</label>";
    echo "<select name='modo_pago' id='modo_pago'>";
    echo "<option value='efectivo'>Efectivo</option>";
    echo "<option value='tarjeta_credito'>Tarjeta de Crédito</option>";
    echo "<option value='transferencia_bancaria'>Transferencia Bancaria</option>";
    echo "</select>";
    echo "<input type='submit' value='Finalizar Pedido'>";
    echo "</form>";
} else {
    echo "No se encontraron registros en la tabla carrito.";
}