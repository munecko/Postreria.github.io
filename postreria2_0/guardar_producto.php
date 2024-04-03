<?php
session_start();
include 'conexion.php'; // Asegúrate de incluir la conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    
    // Verificar si se ha subido una imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        // Procesar la imagen
        $imagen_temporal = $_FILES["imagen"]["tmp_name"];
        $imagen_tipo = $_FILES["imagen"]["type"];
        
        // Verificar si el archivo es una imagen usando getimagesize()
         $tipo_imagen = @getimagesize($_FILES["imagen"]["tmp_name"]);
         if ($tipo_imagen === false) {
         echo "El archivo no es una imagen válida.";
         exit;
        }

        
        // Carpeta de destino para guardar las imágenes
        $carpeta_destino = "imagen_bd/";
        
        // Generar un nombre único para la imagen
       $nombre_imagen = uniqid('imagen_') . '_' . time() . '.' . pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);

        
        // Ruta completa de la imagen
        $ruta_imagen = $carpeta_destino . $nombre_imagen;
        
        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($imagen_temporal, $ruta_imagen)) {
            // Insertar los datos en la base de datos
            try {
                $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)");
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
                $stmt->bindParam(':imagen', $ruta_imagen, PDO::PARAM_STR); // Guardamos la ruta de la imagen en la base de datos
                $stmt->execute();
                
                echo "Producto guardado correctamente.";
            } catch (PDOException $e) {
                echo "Error al guardar el producto: " . $e->getMessage();
            }
        } else {
            echo "Error al mover la imagen a la carpeta de destino.";
        }
    } else {
        echo "No se ha subido ninguna imagen.";
    }
} else {
    echo "No se han recibido datos del formulario.";
}
?>
