<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener el documento del convenio actual
    $sql = "SELECT documento_convenio, acta_constitutiva FROM nuevos_convenios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar si se ha solicitado el documento específico
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            $documento = $tipo === 'convenio' ? $row['documento_convenio'] : $row['acta_constitutiva'];

            // Enviar el documento al navegador
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"$documento\"");
            echo $documento; // Asumiendo que el documento se guarda en formato BLOB
        } else {
            echo "Tipo de documento no especificado.";
        }
    } else {
        echo "No se encontró el documento.";
    }
} else {
    echo "ID no proporcionado.";
}
?>
