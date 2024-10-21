<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM organizacion WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: organizaciones.php"); // Redirige después de eliminar
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: organizaciones.php");
}
?>


