<?php
include 'config.php';

if (isset($_POST['solicitud_id'])) {
    $solicitud_id = $_POST['solicitud_id'];

    // Consulta para obtener los datos de la solicitud de convenio
    $sql = "SELECT objetivos, alcance, beneficios_comerciales, nombre_solicitante FROM solicitud_convenios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $solicitud_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_assoc();

    // Enviar la respuesta en formato JSON
    echo json_encode($data);
}
?>

