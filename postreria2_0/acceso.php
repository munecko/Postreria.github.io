<?php
include 'conexion.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/acceso.css">
    <title>Document</title>
</head>
<body>
      <!-- formulario de contacto en html y css -->  

  <div class="contact_form">
<div class="formulario">
  <center><h1>Inicia Sesion</h1></center>
    <h3>Con tu registro sera de mayor facilidad  para realizar una compra</h3>
      <form action="login.php" method="post">
            <p>
              <label for="nombre" class="colocar_nombre">Nombre
                <span class="obligatorio">*</span>
              </label>
                <input type="text" name="usuario" id="nombre_usuario" required="obligatorio" placeholder="Escribe tu nombre">
            </p>
          
            <p>
              <label for="email" class="colocar_email">Contraseña
                <span class="obligatorio">*</span>
              </label>
                <input type="password"  name="contrasena" id="contrasena" required="obligatorio" placeholder="Escribe tu Contraseña">
            </p>
            <input type="checkbox" name="guardar_sesion"> Guardar Sesión<br>
            <input type="submit" value="Iniciar Sesión">

           
            <p class="aviso">
              <span class="obligatorio"> * </span>los campos son obligatorios.
            </p>          
        
      </form>
      <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
</div>  
</div>

</body>
</html>