<?php
// Conexión a la base de datos
include 'config.php';

// Obtener las organizaciones para el primer menú desplegable
$organizaciones_query = "SELECT id, nombre FROM organizacion";
$organizaciones_result = mysqli_query($conn, $organizaciones_query);

// Obtener las solicitudes con estatus "Aprobado" para el segundo menú desplegable
$solicitudes_query = "SELECT id, nombre_organizacion FROM solicitud_convenios WHERE estatus = 'Aprobado'";
$solicitudes_result = mysqli_query($conn, $solicitudes_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Solicitud de Convenio</title>
    <link rel="stylesheet" href="datosConvenio.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- JQuery para AJAX -->
</head>
<body>
    <header>
        <a href="index.html">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Datos de Solicitud de Convenio</h2>
        <form action="guardarConvenio.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <!-- Lista desplegable de organizaciones -->
                <div>
                    <label for="nombre_organizacion">Nombre de la organización</label>
                    <select id="nombre_organizacion" name="nombre_organizacion" required>
                        <option value="">Seleccione una organización</option>
                        <?php while ($row = mysqli_fetch_assoc($organizaciones_result)) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Campos que se llenan automáticamente desde contactos -->
                <div>
                    <label for="nombre_representante">Nombre del representante</label>
                    <input type="text" id="nombre_representante" name="nombre_representante" readonly>
                </div>

                <div>
                    <label for="cargo_representante">Cargo del representante</label>
                    <input type="text" id="cargo_representante" name="cargo_representante" readonly>
                </div>

                <!-- Lista desplegable de solicitudes aprobadas -->
                <div>
                    <label for="nombre_solicitud">Nombre de solicitud de convenio</label>
                    <select id="nombre_solicitud" name="nombre_solicitud" required>
                        <option value="">Seleccione una solicitud</option>
                        <?php while ($row = mysqli_fetch_assoc($solicitudes_result)) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre_organizacion']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Campos que se llenan automáticamente desde solicitudes aprobadas -->
                <div>
                    <label for="objetivos_convenio">Objetivos del convenio</label>
                    <textarea id="objetivos_convenio" name="objetivos_convenio" rows="4" readonly></textarea>
                </div>

                <div>
                    <label for="alcance_convenio">Alcance del convenio</label>
                    <textarea id="alcance_convenio" name="alcance_convenio" rows="4" readonly></textarea>
                </div>

                <div>
                    <label for="beneficios_comerciales">Beneficios comerciales</label>
                    <textarea id="beneficios_comerciales" name="beneficios_comerciales" rows="4" readonly></textarea>
                </div>

                <div>
                    <label for="responsable_convenio">Responsable del convenio</label>
                    <input type="text" id="responsable_convenio" name="responsable_convenio" readonly>
                </div>

                <!-- <div>
                    <label for="estatus">Estatus del convenio</label>
                    <input type="text" id="estatus" name="estatus" value="Activo" readonly>
                </div> -->
                <div>
                    <label for="estatus">Estatus del convenio</label>
                    <select id="estatus" name="estatus" required>
                        <option value="Activo">Activo</option>
                        <option value="Por vencer">Por vencer</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label for="total_renovaciones">Total de renovaciones</label>
                    <input type="number" id="total_renovaciones" name="total_renovaciones" value="0" required readonly>
                </div>

                <!-- Nuevos campos a llenar manualmente -->
                <div>
                    <label for="razon_social">Razón social</label>
                    <input type="text" id="razon_social" name="razon_social" required>
                </div>

                <div>
                    <label for="domicilio_fiscal">Domicilio fiscal</label>
                    <input type="text" id="domicilio_fiscal" name="domicilio_fiscal" required>
                </div>

                <div>
                    <label for="acta_constitutiva">Acta constitutiva (PDF)</label>
                    <input type="file" id="acta_constitutiva" name="acta_constitutiva" accept="application/pdf" required>
                </div>

                <div>
                    <label for="fecha_inicio_convenio">Fecha de inicio del convenio</label>
                    <input type="date" id="fecha_inicio_convenio" name="fecha_inicio_convenio" required>
                </div>

                <div>
                    <label for="fecha_fin_convenio">Fecha fin del convenio</label>
                    <input type="date" id="fecha_fin_convenio" name="fecha_fin_convenio" required>
                </div>

                <div>
                    <label for="documento_convenio">Documento del convenio (PDF)</label>
                    <input type="file" id="documento_convenio" name="documento_convenio" accept="application/pdf" required>
                </div>

                <div>
                    <label for="notas_generales">Notas generales</label>
                    <textarea id="notas_generales" name="notas_generales" rows="4"></textarea>
                </div>

                <div class="button-container">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="window.location.href='listaConvenios.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        // AJAX para obtener los datos del representante cuando se seleccione una organización
        $('#nombre_organizacion').change(function() {
            var organizacionId = $(this).val();
            if (organizacionId) {
                $.ajax({
                    url: 'getContactos.php',
                    type: 'POST',
                    data: { id: organizacionId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.nombre) {
                            $('#nombre_representante').val(data.nombre);
                            $('#cargo_representante').val(data.cargo);
                        } else {
                            $('#nombre_representante').val('');
                            $('#cargo_representante').val('');
                            alert("No se encontraron datos del representante.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                        alert("Ocurrió un error al obtener los datos.");
                    }
                });
            } else {
                $('#nombre_representante').val('');
                $('#cargo_representante').val('');
            }
        });

        // AJAX para obtener los datos de la solicitud cuando se seleccione una solicitud aprobada
        $('#nombre_solicitud').change(function() {
            var solicitudId = $(this).val();
            if (solicitudId) {
                $.ajax({
                    url: 'getDatosConvenio.php',
                    type: 'POST',
                    data: { id: solicitudId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.objetivos) {
                            $('#objetivos_convenio').val(data.objetivos);
                            $('#alcance_convenio').val(data.alcance);
                            $('#beneficios_comerciales').val(data.beneficios_comerciales);
                            $('#responsable_convenio').val(data.responsable);
                        } else {
                            $('#objetivos_convenio').val('');
                            $('#alcance_convenio').val('');
                            $('#beneficios_comerciales').val('');
                            $('#responsable_convenio').val('');
                            alert("No se encontraron datos de la solicitud.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                        alert("Ocurrió un error al obtener los datos.");
                    }
                });
            } else {
                $('#objetivos_convenio').val('');
                $('#alcance_convenio').val('');
                $('#beneficios_comerciales').val('');
                $('#responsable_convenio').val('');
            }
        });
    });
    </script>
</body>
</html>

