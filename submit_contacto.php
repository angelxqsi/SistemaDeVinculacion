<?php 
require 'config.php'; // Incluye tu archivo de configuración con los detalles de la base de datos

// Verificar si se ha enviado una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $cargo = $conn->real_escape_string($_POST['cargo']);
    $area = $conn->real_escape_string($_POST['area']);
    $organizacion_id = $conn->real_escape_string($_POST['organizacion_id']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $telefono_celular = $conn->real_escape_string($_POST['telefono_celular']);
    $telefono_oficina = isset($_POST['telefono_oficina']) ? $conn->real_escape_string($_POST['telefono_oficina']) : null;
    $principal = $conn->real_escape_string($_POST['principal']);
    $estatus = $conn->real_escape_string($_POST['estatus']);

    // Concatenar los horarios en un solo string
    $horarios = $conn->real_escape_string($_POST['horarios_dia1'] . ' ' . $_POST['horarios_hora1'] . ' ' . $_POST['horarios_am_pm1'] . ' - ' .
                                         $_POST['horarios_dia2'] . ' ' . $_POST['horarios_hora2'] . ' ' . $_POST['horarios_am_pm2']);

    // Preparar la consulta de inserción
    $sql_contacto = "INSERT INTO contactos (nombre, apellido, cargo, area_departamento, organizacion_id, correo_electronico, telefono_celular, telefono_oficina_ext, principal, estatus, horarios_atencion)
                    VALUES ('$nombre', '$apellido', '$cargo', '$area', '$organizacion_id', '$correo', '$telefono_celular', '$telefono_oficina', '$principal', '$estatus', '$horarios')";

    // Ejecutar la consulta
    if ($conn->query($sql_contacto) === TRUE) {
        // Redirigir a la página de contactos
        header("Location: contactos.php"); 
        exit;
    } else {
        echo "Error al guardar el contacto: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Solicitud no válida";
}
