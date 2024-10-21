<?php
include 'config.php'; // Conexión a la base de datos

// Verificar si el formulario se envió correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $organizacion_id = $_POST['nombre_organizacion'];
    $solicitud_convenio_id = $_POST['nombre_solicitud'];
    $razon_social = $_POST['razon_social'];
    $domicilio_fiscal = $_POST['domicilio_fiscal'];
    $fecha_inicio_convenio = $_POST['fecha_inicio_convenio'];
    $fecha_fin_convenio = $_POST['fecha_fin_convenio'];
    $estatus_convenio = $_POST['estatus_convenio'];
    $objetivo_convenio = $_POST['objetivos_convenio'];
    $alcance_convenio = $_POST['alcance_convenio'];
    $beneficios_comerciales = $_POST['beneficios_comerciales'];
    $responsable_convenio = $_POST['responsable_convenio'];
    $total_renovaciones = $_POST['total_renovaciones'];
    $notas_generales = $_POST['notas_generales'];

    // Procesar archivos PDF (acta constitutiva y documentos del convenio)
    $acta_constitutiva = $_FILES['acta_constitutiva'];
    $documentos_convenio = $_FILES['documentos_convenio'];

    // Leer archivos PDF en variables de tipo LONGBLOB
    $acta_constitutiva_blob = file_get_contents($acta_constitutiva['tmp_name']);
    $documento_convenio_blob = file_get_contents($documentos_convenio['tmp_name']);

    // Obtener el contacto principal automáticamente
    $stmt_contacto = $conn->prepare("SELECT id FROM contactos WHERE organizacion_id = ? AND principal = 1 AND estatus = 'Activo' LIMIT 1");
    $stmt_contacto->bind_param("i", $organizacion_id);
    $stmt_contacto->execute();
    $result_contacto = $stmt_contacto->get_result();
    $contacto_principal_id = $result_contacto->fetch_assoc()['id'];
    $stmt_contacto->close();

    // Insertar datos en la tabla nuevos_convenios
    $sql = "INSERT INTO nuevos_convenios (
                organizacion_id, contacto_principal_id, solicitud_convenio_id,
                razon_social, domicilio_fiscal, fecha_inicio_convenio,
                fecha_fin_convenio, acta_constitutiva, documento_convenio,
                total_renovaciones, estatus_convenio, objetivo_convenio,
                alcance_convenio, beneficios_comerciales, responsable_convenio, notas_generales
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iiissssssissssss",
        $organizacion_id,
        $contacto_principal_id,
        $solicitud_convenio_id,
        $razon_social,
        $domicilio_fiscal,
        $fecha_inicio_convenio,
        $fecha_fin_convenio,
        $acta_constitutiva_blob,
        $documento_convenio_blob,
        $total_renovaciones,
        $estatus_convenio,
        $objetivo_convenio,
        $alcance_convenio,
        $beneficios_comerciales,
        $responsable_convenio,
        $notas_generales
    );

    if ($stmt->execute()) {
        echo "Los datos se guardaron correctamente.";
        header("Location: tablaConvenio.php"); // Redirigir a página de éxito
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
