<?php
require 'config.php';

// Comprobar si se proporciona un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta para eliminar el registro
    $sql = "DELETE FROM solicitud_convenios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirigir de nuevo a la lista después de eliminar
        header("Location: listaSoliConv.php");
        exit;
    } else {
        echo "Error al eliminar la solicitud: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}

$conn->close();
?>
