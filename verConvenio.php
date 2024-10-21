<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta
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

    // Verificar si se obtuvo un resultado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("No se encontró el convenio.");
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
    <title>Datos del Convenio</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Datos de Solicitud de Convenio</h2>
        <form>
            <!-- Datos que siempre se muestran -->
            <div class="form-group">
                <label for="logotipo"></label>
                <img src="uploads/<?php echo $row['logotipo']; ?>" width="100" alt="Logotipo">
            </div>
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
                <label for="responsable_convenio">Responsable del convenio</label>
                <input type="text" id="responsable_convenio" class="form-control" value="<?php echo $row['responsable_convenio']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nombre_solicitud">Nombre de solicitud de convenio</label>
                <input type="text" id="nombre_solicitud" class="form-control" value="<?php echo $row['nombre_solicitud']; ?>" readonly>
            </div>


            <!-- Datos que se ocultan inicialmente -->
            <div id="informacion-oculta" style="display: none;">
                <div class="form-group">
                    <label for="razon_social">Razón social</label>
                    <input type="text" id="razon_social" class="form-control" value="<?php echo $row['razon_social']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="domicilio_fiscal">Domicilio fiscal</label>
                    <input type="text" id="domicilio_fiscal" class="form-control" value="<?php echo $row['domicilio_fiscal']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio_convenio">Fecha de inicio de convenio</label>
                    <input type="date" id="fecha_inicio_convenio" class="form-control" value="<?php echo $row['fecha_inicio_convenio']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="fecha_fin_convenio">Fecha fin de convenio</label>
                    <input type="date" id="fecha_fin_convenio" class="form-control" value="<?php echo $row['fecha_fin_convenio']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="estatus_convenio">Estatus del convenio</label>
                    <input type="text" id="estatus_convenio" class="form-control" value="<?php echo $row['estatus_convenio']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="objetivo_convenio">Objetivos del convenio</label>
                    <textarea id="objetivo_convenio" class="form-control" rows="4" readonly><?php echo $row['objetivo_convenio']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="alcance_convenio">Alcance del convenio</label>
                    <textarea id="alcance_convenio" class="form-control" rows="4" readonly><?php echo $row['alcance_convenio']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="beneficios_comerciales">Beneficios comerciales</label>
                    <textarea id="beneficios_comerciales" class="form-control" rows="4" readonly><?php echo $row['beneficios_comerciales']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="total_renovaciones">Total de renovaciones</label>
                    <input type="text" id="total_renovaciones" class="form-control" value="<?php echo $row['total_renovaciones']; ?>" readonly>
                </div>
            </div>

            <!-- Datos que siempre se muestran al final -->
            <div class="form-group">
                <label for="documentos">Documentos</label>
                <p>
                    <?php if (!empty($row['acta_constitutiva'])): ?>
                        <a href="verDocumento.php?id=<?php echo $row['id']; ?>&tipo=acta" target="_blank">Ver Acta Constitutiva</a>
                    <?php else: ?>
                        Acta Constitutiva no disponible
                    <?php endif; ?>
                </p>
                <p>
                    <?php if (!empty($row['documento_convenio'])): ?>
                        <a href="verDocumento.php?id=<?php echo $row['id']; ?>&tipo=convenio" target="_blank">Ver Documento de Convenio</a>
                    <?php else: ?>
                        Documento de Convenio no disponible
                    <?php endif; ?>
                </p>
            </div> 
            <div class="form-group">
                <label for="notas_generales">Notas generales</label>
                <input type="text" id="notas_generales" class="form-control" value="<?php echo $row['notas_generales']; ?>" readonly>
            </div>

                        <!-- Botón Ver más -->
                        <button type="button" class="btn btn-outline-info" id="toggleInfo">Ver más informacion</button>


            <!-- Botones finales -->
            <div class="text-center">
                <a href="tablaConvenio.php" class="btn btn-primary">Regresar</a>
                <a href="editarDatosConv.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Editar</a>
                <a href="renovarConvenio.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Renovar</a>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="toggleInfo.js"></script> <!-- Se importa el script separado -->
</body>
</html>

