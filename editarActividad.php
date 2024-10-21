<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta para obtener los detalles de la actividad
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

// Manejar el envío del formulario para actualizar la actividad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombre_actividad = $_POST['nombre_actividad'];
    $area_coordinacion = $_POST['area_coordinacion'];
    $nombre_solicitante = $_POST['nombre_solicitante'];
    $correo_solicitante = $_POST['correo_solicitante'];
    $telefono_celular_solicitante = $_POST['telefono_celular_solicitante'];
    $estatus_solicitud = $_POST['estatus_solicitud'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $objetivos = $_POST['objetivos'];
    $logistica = $_POST['logistica'];
    $total_asistentes = $_POST['total_asistentes'];

    // Preparar la consulta de actualización
    $update_sql = "
        UPDATE actividades
        SET nombre_actividad = ?, area_coordinacion = ?, nombre_solicitante = ?, correo_solicitante = ?, 
            telefono_celular_solicitante = ?, estatus_solicitud = ?, fecha_inicio = ?, fecha_fin = ?, 
            hora_inicio = ?, hora_fin = ?, objetivos = ?, logistica = ?, total_asistentes = ?
        WHERE id = ?
    ";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssssiissii", $nombre_actividad, $area_coordinacion, $nombre_solicitante, 
        $correo_solicitante, $telefono_celular_solicitante, $estatus_solicitud, $fecha_inicio, $fecha_fin, 
        $hora_inicio, $hora_fin, $objetivos, $logistica, $total_asistentes, $id);
    
    if ($update_stmt->execute()) {
        // Redirigir a verActividad.php después de actualizar
        header("Location: verActividad.php?id=" . $id);
        exit(); // Asegurarse de que no se ejecute más código después de la redirección
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar la actividad: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Editar Actividad</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Editar Actividad</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nombre_actividad">Nombre de la actividad</label>
                <input type="text" id="nombre_actividad" name="nombre_actividad" class="form-control" value="<?php echo $row['nombre_actividad']; ?>" required>
            </div>
            <div class="form-group">
                <label for="area_coordinacion">Área de Coordinación</label>
                <input type="text" id="area_coordinacion" name="area_coordinacion" class="form-control" value="<?php echo $row['area_coordinacion']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre_solicitante">Nombre del solicitante</label>
                <input type="text" id="nombre_solicitante" name="nombre_solicitante" class="form-control" value="<?php echo $row['nombre_solicitante']; ?>" required>
            </div>
            <div class="form-group">
                <label for="correo_solicitante">Correo del solicitante</label>
                <input type="email" id="correo_solicitante" name="correo_solicitante" class="form-control" value="<?php echo $row['correo_solicitante']; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono_celular_solicitante">Teléfono del solicitante</label>
                <input type="text" id="telefono_celular_solicitante" name="telefono_celular_solicitante" class="form-control" value="<?php echo $row['telefono_celular_solicitante']; ?>" required>
            </div>
            <div class="form-group">
                <label for="estatus_solicitud">Estatus de la solicitud</label>
                <select id="estatus_solicitud" name="estatus_solicitud" class="form-control" required <?php echo ($row['estatus_solicitud'] == 'Aprobado') ? 'disabled' : ''; ?>>
                    <option value="Sin revisar" <?php echo ($row['estatus_solicitud'] == 'Sin revisar') ? 'selected' : ''; ?>>Sin revisar</option>
                    <option value="En revisión" <?php echo ($row['estatus_solicitud'] == 'En revisión') ? 'selected' : ''; ?>>En revisión</option>
                    <option value="Aprobado" <?php echo ($row['estatus_solicitud'] == 'Aprobado') ? 'selected' : ''; ?>>Aprobado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo $row['fecha_inicio']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo $row['fecha_fin']; ?>" required>
            </div>
            <div class="form-group">
                <label for="hora_inicio">Hora de inicio</label>
                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="<?php echo $row['hora_inicio']; ?>" required>
            </div>
            <div class="form-group">
                <label for="hora_fin">Hora de fin</label>
                <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="<?php echo $row['hora_fin']; ?>" required>
            </div>
            <div class="form-group">
                <label for="objetivos">Objetivos</label>
                <textarea id="objetivos" name="objetivos" class="form-control" rows="4" required><?php echo $row['objetivos']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="logistica">Logística</label>
                <textarea id="logistica" name="logistica" class="form-control" rows="4" required><?php echo $row['logistica']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="total_asistentes">Total de asistentes</label>
                <input type="number" id="total_asistentes" name="total_asistentes" class="form-control" value="<?php echo $row['total_asistentes']; ?>" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="verActividad.php?id=<?php echo $id; ?>" class="btn btn-primary">Cancelar</a>
                </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

