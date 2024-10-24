<?php
require 'config.php';

// Obtener los datos del formulario
$id = intval($_POST['id']);
$nombre_organizacion = $_POST['nombre_organizacion'];
$objetivos = $_POST['objetivos'];
$nombre_solicitante = $_POST['nombre_solicitante'];
$area_departamento = $_POST['area_departamento'];
$beneficiarios = $_POST['beneficiarios'];
$alcance = $_POST['alcance'];
$beneficios_comerciales = $_POST['beneficios_comerciales'];

// Obtener el estatus existente desde la base de datos
$query = "SELECT estatus, logotipo FROM solicitud_convenios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$currentData = $result->fetch_assoc();
$currentStatus = $currentData['estatus'];
$currentLogo = $currentData['logotipo'];

// Manejo del logotipo
if (isset($_FILES['logotipo']) && $_FILES['logotipo']['error'] == UPLOAD_ERR_OK) {
    $logotipo = $_FILES['logotipo']['name'];
    $uploadDir = 'uploads/'; // Ruta a la carpeta donde se guardan los logotipos
    $uploadFile = $uploadDir . basename($logotipo);

    // Mueve el archivo a la carpeta deseada
    if (move_uploaded_file($_FILES['logotipo']['tmp_name'], $uploadFile)) {
        // Archivo subido con éxito
    } else {
        echo "Error al mover el archivo.";
        exit();
    }
} else {
    // Mantener el logotipo actual si no se subió uno nuevo
    $logotipo = $currentLogo;
}

// Si el estatus actual es "Aprobado", no se puede cambiar
if ($currentStatus === 'Aprobado') {
    $estatus = $currentStatus; // Mantener el estatus existente
} else {
    $estatus = $_POST['estatus']; // Si no es "Aprobado", actualizar el estatus
}

// Actualizar la solicitud de convenio en la base de datos
$query = "UPDATE solicitud_convenios SET 
            nombre_organizacion=?, 
            logotipo=?, 
            objetivos=?, 
            nombre_solicitante=?, 
            area_solicitante=?, 
            beneficiarios=?, 
            alcance=?, 
            beneficios_comerciales=?, 
            estatus=?, 
            fecha_modificacion_estatus = NOW() 
          WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sssssssssi', 
    $nombre_organizacion, 
    $logotipo, 
    $objetivos, 
    $nombre_solicitante, 
    $area_departamento, 
    $beneficiarios, 
    $alcance, 
    $beneficios_comerciales, 
    $estatus, 
    $id
);

if ($stmt->execute()) {
    // Redirigir a la lista de solicitudes de convenio
    header("Location: listaSoliConv.php");
    exit(); // Asegúrate de salir después de redirigir
} else {
    echo "Error al actualizar la solicitud: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>




