<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $giro = $conn->real_escape_string($_POST['giro']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $web = $conn->real_escape_string($_POST['web']);
    $redes = $conn->real_escape_string($_POST['redes']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "UPDATE organizacion SET 
            nombre='$nombre', 
            giro='$giro', 
            categoria='$categoria', 
            pagina_web='$web', 
            redes_sociales='$redes', 
            descripcion='$descripcion' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: organizaciones.php"); // Redirige a la lista después de actualizar
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
