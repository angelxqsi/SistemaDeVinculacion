<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta
    $sql = "
        SELECT *
        FROM actividades
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo un resultado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Crear el contenido del archivo Word
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment; filename=actividad_{$id}.doc");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Estilos y contenido
        echo "<html>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
                .container { display: flex; justify-content: space-between; }
                .column { width: 48%; padding: 10px; box-sizing: border-box; }
                .section-title { font-weight: bold; text-decoration: underline; margin-bottom: 5px; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: gray; }
              </style>";
        echo "</head>";
        echo "<body>";

        // Título del documento
        echo "<h1>Detalles de la Actividad</h1>";

        // Secciones de información en columnas
        echo "<div class='container'>";
        
        echo "<div class='column'>";
        echo "<p class='section-title'>Nombre de la actividad:</p> <p>{$row['nombre_actividad']}</p>";
        echo "<p class='section-title'>Área de Coordinación:</p> <p>{$row['area_coordinacion']}</p>";
        echo "<p class='section-title'>Nombre del solicitante:</p> <p>{$row['nombre_solicitante']}</p>";
        echo "<p class='section-title'>Correo del solicitante:</p> <p>{$row['correo_solicitante']}</p>";
        echo "<p class='section-title'>Teléfono del solicitante:</p> <p>{$row['telefono_celular_solicitante']}</p>";
        echo "</div>";

        echo "<div class='column'>";
        echo "<p class='section-title'>Estatus de la solicitud:</p> <p>{$row['estatus_solicitud']}</p>";
        echo "<p class='section-title'>Fecha de inicio:</p> <p>{$row['fecha_inicio']}</p>";
        echo "<p class='section-title'>Fecha de fin:</p> <p>{$row['fecha_fin']}</p>";
        echo "<p class='section-title'>Hora de inicio:</p> <p>{$row['hora_inicio']}</p>";
        echo "<p class='section-title'>Hora de fin:</p> <p>{$row['hora_fin']}</p>";
        echo "<p class='section-title'>Objetivos:</p> <p>{$row['objetivos']}</p>";
        echo "<p class='section-title'>Logística:</p> <p>{$row['logistica']}</p>";
        echo "<p class='section-title'>Total de asistentes:</p> <p>{$row['total_asistentes']}</p>";
        echo "</div>";

        echo "</div>"; // Cierre de container

        // Agregar un pie de página
        echo "<div class='footer'>";
        echo "<p>Este documento ha sido generado automáticamente.</p>";
        echo "</div>";

        echo "</body>";
        echo "</html>";
        exit;
    } else {
        echo "No se encontró la actividad.";
    }
} else {
    echo "ID no proporcionado.";
}
?>
