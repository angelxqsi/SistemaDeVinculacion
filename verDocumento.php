<?php
// Incluir la conexión a la base de datos
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Preparar la consulta para obtener el documento solicitado
    $sql = "SELECT acta_constitutiva, documento_convenio FROM nuevos_convenios WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar si se ha solicitado un documento específico
        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            if ($tipo === 'acta') {
                $documento = $row['acta_constitutiva'];
                $nombreDocumento = 'acta_constitutiva.pdf'; // Cambia esto si es necesario
            } elseif ($tipo === 'convenio') {
                $documento = $row['documento_convenio'];
                $nombreDocumento = 'documento_convenio.pdf'; // Cambia esto si es necesario
            } else {
                die("Tipo de documento no válido.");
            }
            
            // Configurar las cabeceras para la visualización
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"$nombreDocumento\""); // Cambia a 'inline' para mostrar en lugar de descargar
            echo $documento;
            exit;
        }
    } else {
        die("No se encontró el convenio.");
    }
} else {
    die("ID no proporcionado.");
}
?>

