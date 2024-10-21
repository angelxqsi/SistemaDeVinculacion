<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $area = $_POST['area'];
    $correo = $_POST['correo'];
    $telefono_celular = $_POST['telefono_celular'];
    $telefono_oficina = $_POST['telefono_oficina'];
    $principal = intval($_POST['principal']);
    $estatus = $_POST['estatus'];
    
    // Captura el ID de la nueva organización
    $organizacion_id = intval($_POST['organizacion_id']); // Asegúrate de capturar el ID de la organización

    // Captura los días y horas de atención
    $horarios_dia1 = $_POST['horarios_dia1'];
    $horarios_dia2 = $_POST['horarios_dia2'];
    $horarios_hora1 = $_POST['horarios_hora1'];
    $horarios_am_pm1 = $_POST['horarios_am_pm1'];
    $horarios_hora2 = $_POST['horarios_hora2'];
    $horarios_am_pm2 = $_POST['horarios_am_pm2'];

    // Construcción del valor de horarios_atencion
    $horarios_atencion = "$horarios_dia1 a $horarios_dia2, $horarios_hora1 $horarios_am_pm1 a $horarios_hora2 $horarios_am_pm2";

    // Actualiza la consulta para incluir el ID de organización
    $stmt = $conn->prepare("UPDATE contactos SET nombre=?, apellido=?, cargo=?, area_departamento=?, correo_electronico=?, telefono_celular=?, telefono_oficina_ext=?, principal=?, estatus=?, horarios_atencion=?, organizacion_id=? WHERE id=?");
    $stmt->bind_param("sssssissssii", $nombre, $apellido, $cargo, $area, $correo, $telefono_celular, $telefono_oficina, $principal, $estatus, $horarios_atencion, $organizacion_id, $id);
    
    if ($stmt->execute()) {
        header('Location: contactos.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>







