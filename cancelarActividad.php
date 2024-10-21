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
    } else {
        die("No se encontró la actividad.");
    }
} else {
    die("ID no proporcionado.");
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motivo_cancelacion = $_POST['motivo_cancelacion'];

    // Manejar la carga del archivo PDF
    $archivo_pdf = null; // Inicializar la variable
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $archivo_pdf = $target_dir . basename($_FILES["archivo_pdf"]["name"]);
        move_uploaded_file($_FILES["archivo_pdf"]["tmp_name"], $archivo_pdf);
    }

    // Actualizar el estatus y el motivo de cancelación en la base de datos
    $sql_update = "UPDATE actividades SET estatus_solicitud = 'Cancelado', motivo_cancelacion = ?";
    if ($archivo_pdf) {
        $sql_update .= ", archivo_pdf = ?";
    }
    $sql_update .= " WHERE id = ?";
    
    $stmt_update = $conn->prepare($sql_update);
    
    // Determinar la cantidad de parámetros
    if ($archivo_pdf) {
        $stmt_update->bind_param("ssi", $motivo_cancelacion, $archivo_pdf, $id);
    } else {
        $stmt_update->bind_param("si", $motivo_cancelacion, $id);
    }

    $stmt_update->execute();

    // Redirigir a la lista de actividades después de la cancelación
    header("Location: listaAct.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Cancelar Actividad</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Cancelar Actividad</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_actividad">Nombre de la actividad</label>
                <input type="text" id="nombre_actividad" class="form-control" value="<?php echo $row['nombre_actividad']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="area_coordinacion">Área de Coordinación</label>
                <input type="text" id="area_coordinacion" class="form-control" value="<?php echo $row['area_coordinacion']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nombre_solicitante">Nombre del solicitante</label>
                <input type="text" id="nombre_solicitante" class="form-control" value="<?php echo $row['nombre_solicitante']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="motivo_cancelacion">Motivo de cancelación</label>
                <textarea id="motivo_cancelacion" name="motivo_cancelacion" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="archivo_pdf">Cargar archivo PDF (opcional)</label>
                <input type="file" id="archivo_pdf" name="archivo_pdf" class="form-control" accept=".pdf">
            </div>

            <div class="text-center mt-4">
                <a href="listaAct.php" class="btn btn-primary">Regresar</a>
                <button type="submit" class="btn btn-danger">Cancelar Actividad</button>
            </div>
        </form>
    </div>
</body>
</html>

