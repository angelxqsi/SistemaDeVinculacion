<?php
include 'config.php'; // Asegúrate de incluir el archivo de conexión a la base de datos

if (isset($_POST['organizacion_id'])) {
    $organizacion_id = $_POST['organizacion_id'];

    // Consulta para obtener el contacto principal de la organización seleccionada
    $sql = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo, cargo FROM contactos WHERE organizacion_id = ? AND principal = 1 AND estatus = 'Activo' LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $organizacion_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contacto = $result->fetch_assoc();
        echo json_encode([
            'nombre_representante' => $contacto['nombre_completo'],
            'cargo_representante' => $contacto['cargo']
        ]);
    } else {
        // Si no se encuentra contacto, devolver valores vacíos
        echo json_encode(['nombre_representante' => '', 'cargo_representante' => '']);
    }

    $stmt->close();
}
$conn->close();
?>
