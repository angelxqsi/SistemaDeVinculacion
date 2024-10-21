<?php

require 'config.php'; // Incluye tu archivo de configuración con los detalles de la base de datos

// Verificar si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $giro = $conn->real_escape_string($_POST['giro']);
    $domicilio = $conn->real_escape_string($_POST['domicilio']);
    $web = isset($_POST['web']) ? $conn->real_escape_string($_POST['web']) : null;
    $redes = isset($_POST['redes']) ? $conn->real_escape_string($_POST['redes']) : null;
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $descripcion = isset($_POST['descripcion']) ? $conn->real_escape_string($_POST['descripcion']) : null;
    $tipo_organizacion = $conn->real_escape_string($_POST['tipo_organizacion']); // Nuevo campo

    // Manejo del archivo de logotipo
    $logotipo = null;
    if (isset($_FILES['logotipo']) && $_FILES['logotipo']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logotipo']['tmp_name'];
        $fileName = $_FILES['logotipo']['name'];
        $fileSize = $_FILES['logotipo']['size'];
        $fileType = $_FILES['logotipo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validar extensión del archivo
        $allowedExts = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedExts)) {
            // Generar nombre único para el archivo
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            
            // Verificar si la carpeta existe, si no, crearla
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true); // Crear carpeta con permisos 755
            }

            $dest_path = $uploadFileDir . $newFileName;

            // Mover el archivo al directorio de destino
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $logotipo = $newFileName;
            }
        } else {
            echo "Tipo de archivo no permitido. Los archivos permitidos son: jpg, jpeg, png, gif.";
            exit;
        }
    }

    // Preparar la consulta de inserción
    $sql = "INSERT INTO organizacion (nombre, giro, domicilio, pagina_web, redes_sociales, categoria, descripcion, logotipo, tipo_organizacion)
            VALUES ('$nombre', '$giro', '$domicilio', '$web', '$redes', '$categoria', '$descripcion', '$logotipo', '$tipo_organizacion')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de organizaciones
        header("Location: organizaciones.php"); // Cambia 'organizaciones.php' al nombre correcto de tu página
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Solicitud no válida";
}




