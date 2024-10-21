<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos del convenio actual
    $sql = "
        SELECT 
            nc.*, 
            o.nombre AS nombre_organizacion, 
            o.logotipo AS logotipo,  
            c.nombre AS nombre_representante, 
            c.cargo AS cargo_representante,
            s.nombre_organizacion AS nombre_solicitud
        FROM 
            nuevos_convenios AS nc 
        JOIN 
            organizacion AS o ON nc.organizacion_id = o.id 
        JOIN 
            contactos AS c ON nc.contacto_principal_id = c.id 
        JOIN 
            solicitud_convenios AS s ON nc.solicitud_convenio_id = s.id 
        WHERE 
            nc.id = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("No se encontró el convenio.");
    }

    // Procesar el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si se subió un nuevo archivo
        $nuevoDocumento = $_FILES['documento_convenio']['name'] ? $_FILES['documento_convenio']['name'] : $row['documento_convenio'];
        $nuevoActa = $_FILES['acta_constitutiva']['name'] ? $_FILES['acta_constitutiva']['name'] : $row['acta_constitutiva'];

        // Subir documentos si se han seleccionado nuevos
        if ($_FILES['documento_convenio']['name']) {
            move_uploaded_file($_FILES['documento_convenio']['tmp_name'], "uploads/" . $nuevoDocumento);
        }
        if ($_FILES['acta_constitutiva']['name']) {
            move_uploaded_file($_FILES['acta_constitutiva']['tmp_name'], "uploads/" . $nuevoActa);
        }

        // Actualizar la base de datos
        $fechaFinConvenio = $_POST['fecha_fin_convenio'];
        $totalRenovaciones = $row['total_renovaciones'] + 1;

        // Determinar el nuevo estatus
        $estatusConvenio = 'Activo'; // Por defecto

        // Calcular el nuevo estatus según la fecha de vencimiento
        $fechaActual = new DateTime();
        $fechaFin = new DateTime($fechaFinConvenio);
        $diferenciaDias = $fechaActual->diff($fechaFin)->days;

        if ($diferenciaDias < 0) {
            $estatusConvenio = 'Vencido';
        } elseif ($diferenciaDias < 30) {
            $estatusConvenio = 'Por vencer';
        }

        $sqlUpdate = "
            UPDATE nuevos_convenios 
            SET fecha_inicio_convenio = ?, fecha_fin_convenio = ?, documento_convenio = ?, acta_constitutiva = ?, estatus_convenio = ?, total_renovaciones = ? 
            WHERE id = ?
        ";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param(
            "sssssii", 
            $_POST['fecha_inicio_convenio'], 
            $fechaFinConvenio, 
            $nuevoDocumento, 
            $nuevoActa, 
            $estatusConvenio, 
            $totalRenovaciones, 
            $id
        );
        $stmtUpdate->execute();
        header("Location: verConvenio.php?id=$id");
        exit();
    }
} else {
    die("ID no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Renovar Convenio</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Renovar Convenio</h2>
        <form method="post" enctype="multipart/form-data">
            <!-- Campos no editables -->
            <div class="form-group">
                <label for="nombre_organizacion">Nombre de la organización</label>
                <input type="text" id="nombre_organizacion" class="form-control" value="<?php echo $row['nombre_organizacion']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nombre_representante">Nombre del representante</label>
                <input type="text" id="nombre_representante" class="form-control" value="<?php echo $row['nombre_representante']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="cargo_representante">Cargo del representante</label>
                <input type="text" id="cargo_representante" class="form-control" value="<?php echo $row['cargo_representante']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="nombre_solicitud">Nombre de solicitud de convenio</label>
                <input type="text" id="nombre_solicitud" class="form-control" value="<?php echo $row['nombre_solicitud']; ?>" readonly>
            </div>

            <!-- Fechas editables -->
            <div class="form-group">
                <label for="fecha_inicio_convenio">Fecha de inicio de convenio</label>
                <input type="date" id="fecha_inicio_convenio" name="fecha_inicio_convenio" class="form-control" value="<?php echo $row['fecha_inicio_convenio']; ?>">
            </div>

            <div class="form-group">
                <label for="fecha_fin_convenio">Fecha fin de convenio</label>
                <input type="date" id="fecha_fin_convenio" name="fecha_fin_convenio" class="form-control" value="<?php echo $row['fecha_fin_convenio']; ?>">
            </div>

            <!-- Documentos -->
            <div class="form-group">
                <label for="documento_convenio">Documento de Convenio</label>
                <input type="file" id="documento_convenio" name="documento_convenio" class="form-control">
                <p>Documento actual: 
                    <a href="mostrarDocumento.php?id=<?php echo $row['id']; ?>&tipo=convenio" target="_blank">Ver Documento de Convenio</a>
                </p>
            </div>

            <div class="form-group">
                <label for="acta_constitutiva">Acta Constitutiva</label>
                <input type="file" id="acta_constitutiva" name="acta_constitutiva" class="form-control">
                <p>Acta actual: 
                    <a href="mostrarDocumento.php?id=<?php echo $row['id']; ?>&tipo=acta" target="_blank">Ver Acta Constitutiva</a>
                </p>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success">Renovar Convenio</button>
                <a href="verConvenio.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

