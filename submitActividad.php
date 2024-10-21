<?php
// Incluye el archivo de configuración para la conexión a la base de datos
include 'config.php';

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los datos del formulario
    $nombre_actividades = $_POST['nombre_actividades'] ?? '';
    $area_coordinacion = $_POST['area_coordinacion'] ?? '';
    $nombre_solicitante = $_POST['nombre_solicitante'] ?? '';
    $correo_solicitante = $_POST['correo_solicitante'] ?? '';
    $telefono_celular_solicitante = $_POST['telefono_celular_solicitante'] ?? '';
    $se_usara_convenio = $_POST['se_usara_convenio'] ?? '';
    $nombre_convenio = $_POST['nombre_convenio'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';
    $programa_academico = $_POST['programa_academico'] ?? '';
    $modalidad = $_POST['modalidad'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $objetivos = $_POST['objetivos'] ?? '';
    $responsable_actividad = $_POST['responsable_actividad'] ?? '';
    $total_asistentes = $_POST['total_asistentes'] ?? '';
    $logistica = $_POST['logistica'] ?? '';
    $tipo_transportacion = $_POST['tipo_transportacion'] ?? '';
    $estatus_solicitud = $_POST['estatus_solicitud'] ?? 'Sin revisar'; // Valor por defecto

    // Prepara la consulta SQL
    $sql = "INSERT INTO actividades (
        nombre_actividad, 
        area_coordinacion, 
        nombre_solicitante, 
        correo_solicitante, 
        telefono_celular_solicitante, 
        se_usara_convenio, 
        nombre_convenio, 
        fecha_inicio, 
        fecha_fin, 
        hora_inicio, 
        hora_fin, 
        programa_academico, 
        modalidad, 
        grupo, 
        objetivos, 
        responsable_actividad, 
        total_asistentes, 
        logistica, 
        tipo_transportacion, 
        estatus_solicitud
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara la declaración
    $stmt = $conn->prepare($sql);

    // Vincula los parámetros
    $stmt->bind_param("ssssssssssssssssssss", 
        $nombre_actividades, 
        $area_coordinacion, 
        $nombre_solicitante, 
        $correo_solicitante, 
        $telefono_celular_solicitante, 
        $se_usara_convenio, 
        $nombre_convenio, 
        $fecha_inicio, 
        $fecha_fin, 
        $hora_inicio, 
        $hora_fin, 
        $programa_academico, 
        $modalidad, 
        $grupo, 
        $objetivos, 
        $responsable_actividad, 
        $total_asistentes, 
        $logistica, 
        $tipo_transportacion, 
        $estatus_solicitud
    );

    // Ejecuta la consulta
    if ($stmt->execute()) {
        // Redirige a lista_act.php después de guardar con éxito
        header('Location: listaAct.php');
        exit(); // Asegúrate de usar exit() después de header
    } else {
        echo "Error: " . $stmt->error; // Muestra el error si la inserción falla
    }

    // Cierra la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>

