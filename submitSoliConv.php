<?php
// Incluir el archivo de configuración
require 'config.php';

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre_organizacion = $_POST['nombre_organizacion'];
    $objetivos_convenio = $_POST['objetivos'];
    $nombre_solicitante = $_POST['nombre_solicitante'];
    $area_solicitante = $_POST['area_departamento'];
    $beneficiarios = $_POST['beneficiarios'];
    $alcance_convenio = $_POST['alcance'];
    $beneficios_comerciales = $_POST['beneficios_comerciales'];
    $estatus_convenio = $_POST['estatus']; // Asegúrate de que este nombre coincida con el del formulario

    // Manejar la carga del logotipo
    $logotipo_dir = 'uploads/'; // Carpeta donde se guardará el logotipo
    $logotipo_nombre = basename($_FILES['logotipo']['name']);
    $logotipo_ruta = $logotipo_dir . $logotipo_nombre;

    // Mover el archivo subido a la carpeta deseada
    if (move_uploaded_file($_FILES['logotipo']['tmp_name'], $logotipo_ruta)) {
        // Consulta SQL para insertar los datos
        $sql = "INSERT INTO solicitud_convenios (nombre_organizacion, objetivos, nombre_solicitante, area_solicitante, beneficiarios, alcance, beneficios_comerciales, estatus, logotipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        
        // Vincular los parámetros
        $stmt->bind_param("sssssssss", $nombre_organizacion, $objetivos_convenio, $nombre_solicitante, $area_solicitante, $beneficiarios, $alcance_convenio, $beneficios_comerciales, $estatus_convenio, $logotipo_nombre);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de solicitudes de convenio
            header("Location: listaSoliConv.php");
            exit; // Asegurarse de que el script se detenga después de la redirección
        } else {
            echo "Error al guardar la solicitud: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al cargar el logotipo.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>


