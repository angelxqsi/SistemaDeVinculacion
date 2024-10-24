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
        $error_message = "No se encontró la actividad.";
    }
} else {
    $error_message = "ID no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Detalles de la Actividad</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Detalles de la Actividad</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form>
            <div class="form-group">
                <label for="nombre_actividad">Nombre de la actividad</label>
                <input type="text" id="nombre_actividad" class="form-control" value="<?php echo isset($row) ? $row['nombre_actividad'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="area_coordinacion">Área de Coordinación</label>
                <input type="text" id="area_coordinacion" class="form-control" value="<?php echo isset($row) ? $row['area_coordinacion'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nombre_solicitante">Nombre del solicitante</label>
                <input type="text" id="nombre_solicitante" class="form-control" value="<?php echo isset($row) ? $row['nombre_solicitante'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="correo_solicitante">Correo del solicitante</label>
                <input type="text" id="correo_solicitante" class="form-control" value="<?php echo isset($row) ? $row['correo_solicitante'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="telefono_celular_solicitante">Teléfono del solicitante</label>
                <input type="text" id="telefono_celular_solicitante" class="form-control" value="<?php echo isset($row) ? $row['telefono_celular_solicitante'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="estatus_solicitud">Estatus de la solicitud</label>
                <input type="text" id="estatus_solicitud" class="form-control" value="<?php echo isset($row) ? $row['estatus_solicitud'] : ''; ?>" readonly>
            </div>

            <!-- Datos que se ocultan inicialmente -->
            <div id="informacion-oculta" style="display: none;">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input type="date" id="fecha_inicio" class="form-control" value="<?php echo isset($row) ? $row['fecha_inicio'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha de fin</label>
                    <input type="date" id="fecha_fin" class="form-control" value="<?php echo isset($row) ? $row['fecha_fin'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="hora_inicio">Hora de inicio</label>
                    <input type="time" id="hora_inicio" class="form-control" value="<?php echo isset($row) ? $row['hora_inicio'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="hora_fin">Hora de fin</label>
                    <input type="time" id="hora_fin" class="form-control" value="<?php echo isset($row) ? $row['hora_fin'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="objetivos">Objetivos</label>
                    <textarea id="objetivos" class="form-control" rows="4" readonly><?php echo isset($row) ? $row['objetivos'] : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="logistica">Logística</label>
                    <textarea id="logistica" class="form-control" rows="4" readonly><?php echo isset($row) ? $row['logistica'] : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="total_asistentes">Total de asistentes</label>
                    <input type="number" id="total_asistentes" class="form-control" value="<?php echo isset($row) ? $row['total_asistentes'] : ''; ?>" readonly>
                </div>
            </div>

            <!-- Botón Ver más -->
            <button type="button" class="btn btn-outline-info" id="toggleInfo">Ver más información</button>

            <!-- Botones finales -->
            <div class="text-center mt-4">
                <a href="listaAct.php" class="btn btn-primary">Regresar</a>
                <a href="editarActividad.php?id=<?php echo isset($row) ? $row['id'] : ''; ?>" class="btn btn-warning" id="editarBtn">Editar</a>
                <a href="cancelarActividad.php?id=<?php echo isset($row) ? $row['id'] : ''; ?>" class="btn btn-danger" id="cancelarBtn" style="display: none;">Cancelar</a>
                <a href="descargar.php?id=<?php echo isset($row) ? $row['id'] : ''; ?>" class="btn btn-success">Descargar en Word</a>

            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar u ocultar información oculta
            $("#toggleInfo").click(function() {
                $("#informacion-oculta").toggle();
                // Cambiar el texto del botón según el estado
                $(this).text($(this).text() === "Ver más información" ? "Ver menos" : "Ver más información");
            });

            // Mostrar u ocultar botones según el estatus de la solicitud
            var estatus = "<?php echo isset($row) ? $row['estatus_solicitud'] : ''; ?>";
            var editarBtn = $("#editarBtn");
            var cancelarBtn = $("#cancelarBtn");

            // Deshabilitar el botón de editar si el estatus es "Cancelado"
            if (estatus === "Cancelado") {
                editarBtn.addClass("disabled").css("pointer-events", "none"); // Agregar clase para deshabilitar
                editarBtn.text("Actividad Cancelada"); // Cambiar texto
            } else if (estatus === "Aprobado") {
                editarBtn.hide();
                cancelarBtn.show();
            } else {
                editarBtn.show();
                cancelarBtn.hide();
            }
        });
    </script>
</body>
</html>



