<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Solicitud de Convenio</title>
    <link rel="stylesheet" href="datosConvenio.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <a href="index.php">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Datos de Convenio</h2>
        <form action="submitSoliConve.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Nombre de la organización con opciones dinámicas -->
                <div>
                    <label for="nombre_organizacion">Nombre de la organización</label>
                    <select id="nombre_organizacion" name="nombre_organizacion" required>
                        <option value="">Selecciona una organización</option>
                        <?php
                        include 'config.php';
                        $sql = "SELECT id, nombre FROM organizacion WHERE estatus = 'Activo'";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Nombre del representante (automático) -->
                <div>
                    <label for="nombre_representante">Nombre del representante</label>
                    <input type="text" id="nombre_representante" name="nombre_representante" readonly required>
                </div>

                <!-- Cargo del representante (automático) -->
                <div>
                    <label for="cargo_representante">Cargo del representante</label>
                    <input type="text" id="cargo_representante" name="cargo_representante" readonly required>
                </div>

                <div>
                    <label for="razon_social">Razón social</label>
                    <input type="text" id="razon_social" name="razon_social" required>
                </div>

                <div>
                    <label for="domicilio_fiscal">Domicilio fiscal</label>
                    <input type="text" id="domicilio_fiscal" name="domicilio_fiscal" required>
                </div>

                <div>
                    <label for="acta_constitutiva">Acta constitutiva</label>
                    <input type="file" id="acta_constitutiva" name="acta_constitutiva" accept="application/pdf" required>
                </div>

                <div>
                    <label for="fecha_inicio_convenio">Fecha de inicio de convenio</label>
                    <input type="date" id="fecha_inicio_convenio" name="fecha_inicio_convenio" required>
                </div>

                <div>
                    <label for="fecha_fin_convenio">Fecha fin de convenio</label>
                    <input type="date" id="fecha_fin_convenio" name="fecha_fin_convenio" required>
                </div>

                <div>
                    <label for="estatus_convenio">Estatus del convenio</label>
                    <select id="estatus_convenio" name="estatus_convenio" readonly>
                        <option value="Activo" selected>Activo</option>
                    </select>
                </div>

                <!-- Nombre de la solicitud del convenio (con opciones dinámicas) -->
                <div>
                    <label for="nombre_solicitud">Nombre de solicitud de convenio</label>
                    <select id="nombre_solicitud" name="nombre_solicitud" required>
                        <option value="">Selecciona una solicitud</option>
                        <?php
                        $sql = "SELECT id, nombre_organizacion FROM solicitud_convenios WHERE estatus='Aprobado'";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['nombre_organizacion']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="objetivos_convenio">Objetivos del convenio</label>
                    <textarea id="objetivos_convenio" name="objetivos_convenio" rows="4" readonly required></textarea>
                </div>

                <div>
                    <label for="alcance_convenio">Alcance del convenio</label>
                    <textarea id="alcance_convenio" name="alcance_convenio" rows="4" readonly required></textarea>
                </div>

                <div>
                    <label for="beneficios_comerciales">Beneficios comerciales</label>
                    <textarea id="beneficios_comerciales" name="beneficios_comerciales" rows="4" readonly required></textarea>
                </div>

                <div>
                    <label for="documentos_convenio">Documentos del convenio</label>
                    <input type="file" id="documentos_convenio" name="documentos_convenio" accept="application/pdf" required>
                </div>

                <div>
                    <label for="responsable_convenio">Responsable del convenio</label>
                    <input type="text" id="responsable_convenio" name="responsable_convenio" readonly required>
                </div>

                <div>
                    <label for="total_renovaciones">Total de renovaciones</label>
                    <input type="number" id="total_renovaciones" name="total_renovaciones" value="0" readonly>
                </div>

                <div>
                    <label for="notas_generales">Notas generales</label>
                    <textarea id="notas_generales" name="notas_generales" rows="4" required></textarea>
                </div>

                <div class="button-container">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="window.location.href='tablaConvenio.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Cuando cambias el valor de la organización
        $('#nombre_organizacion').change(function() {
            var organizacion_id = $(this).val();
            $.ajax({
                url: 'fetch_organizacion.php',
                method: 'POST',
                data: {organizacion_id: organizacion_id},
                dataType: 'json',
                success: function(response) {
                    $('#nombre_representante').val(response.nombre_representante);
                    $('#cargo_representante').val(response.cargo_representante);
                }
            });
        });

        // Cuando cambias el valor de la solicitud de convenio
        $('#nombre_solicitud').change(function() {
            var solicitud_id = $(this).val();
            $.ajax({
                url: 'fetch_solicitud.php',
                method: 'POST',
                data: {solicitud_id: solicitud_id},
                dataType: 'json',
                success: function(response) {
                    $('#objetivos_convenio').val(response.objetivos);
                    $('#alcance_convenio').val(response.alcance);
                    $('#beneficios_comerciales').val(response.beneficios_comerciales);
                    $('#responsable_convenio').val(response.nombre_solicitante);
                }
            });
        });
    </script>
</body>
</html>
