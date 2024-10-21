<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM contactos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header('Location: contactos.php'); // Redirigir después de la eliminación
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
