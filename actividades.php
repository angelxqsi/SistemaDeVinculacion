<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Actividades</title>
    <link rel="stylesheet" href="datosConvenio.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <header>
        <a href="index.html">
            <img src="https://6986956.fs1.hubspotusercontent-na1.net/hubfs/6986956/logo-azul-grd%20(1)-1.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Datos de la Solicitud de Actividad</h2>
        <form action="submitActividad.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">

                <!--Nommbre de la Actividad -->
                <div>
                    <label for="nombre_actividades">Nombre de la Actividad</label>
                    <input type="text" id="nombre_actividades" name="nombre_actividades" required>
                </div>

                <!-- Área/Coordinación/Dirección -->
                <div>
                    <label for="area_coordinacion">Área/Coordinación/Dirección</label>
                    <input type="text" id="area_coordinacion" name="area_coordinacion" required>
                </div>

                <!-- Nombre completo del solicitante -->
                <div>
                    <label for="nombre_solicitante">Nombre completo del solicitante</label>
                    <input type="text" id="nombre_solicitante" name="nombre_solicitante" required>
                </div>

                <!-- Correo electrónico del solicitante -->
                <div>
                    <label for="correo_solicitante">Correo electrónico del solicitante</label>
                    <input type="email" id="correo_solicitante" name="correo_solicitante" required>
                </div>

                <!-- Teléfono celular del solicitante -->
                <div>
                    <label for="telefono_celular_solicitante">Teléfono celular del solicitante</label>
                    <input type="text" id="telefono_celular_solicitante" name="telefono_celular_solicitante" required>
                </div>

                <!-- Se usará convenio -->
                <div>
                    <label for="se_usara_convenio">¿Se usará convenio?</label>
                    <select id="se_usara_convenio" name="se_usara_convenio" required>
                        <option value="No">No</option>
                        <option value="Sí">Sí</option>
                    </select>
                </div>

                <!-- Nombre del convenio (aparece solo si se selecciona 'Sí' en el convenio) -->
                <div id="nombre_convenio_div" style="display:none;">
                    <label for="nombre_convenio">Nombre del convenio</label>
                    <select id="nombre_convenio" name="nombre_convenio">
                        <option value="">Selecciona un convenio</option>
                        <?php
                        include 'config.php';

                        // Consulta para obtener convenios activos y por vencer
                        $sql = "SELECT sc.nombre_organizacion 
                        FROM nuevos_convenios nc 
                        JOIN solicitud_convenios sc ON nc.solicitud_convenio_id = sc.id 
                        WHERE nc.estatus_convenio IN ('Activo', 'Por vencer')"; 

                        $result = $conn->query($sql);

                        // Verificamos si hay resultados
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['nombre_organizacion']}'>{$row['nombre_organizacion']}</option>";
                            }
                        } else {
                            echo "<option value=''>No hay convenios disponibles</option>";
                        }
                        ?>
                    </select>
                </div>


                <!-- Fecha propuesta para realización de actividad -->
                <div>
                    <label for="fecha_inicio">Fecha propuesta para realización de actividad</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <!-- Fecha propuesta para finalización de actividad -->
                <div>
                    <label for="fecha_fin">Fecha propuesta para finalización de actividad</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" required>
                </div>

                <!-- Hora propuesta para realización de actividad -->
                <div>
                    <label for="hora_inicio">Hora propuesta para realización de actividad</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required>
                </div>

                <!-- Hora de finalización -->
                <div>
                    <label for="hora_fin">Hora de finalización</label>
                    <input type="time" id="hora_fin" name="hora_fin">
                </div>

                <!-- Programa académico -->
                <div>
                    <label for="programa_academico">Programa académico</label>
                    <input type="text" id="programa_academico" name="programa_academico" required>
                </div>

                <!-- Modalidad -->
                <div>
                    <label for="modalidad">Modalidad</label>
                    <input type="text" id="modalidad" name="modalidad" required>
                </div>

                <!-- Grupo -->
                <div>
                    <label for="grupo">Grupo</label>
                    <input type="text" id="grupo" name="grupo">
                </div>

                <!-- Objetivo(s) de la actividad -->
                <div>
                    <label for="objetivos">Objetivo(s) de la actividad</label>
                    <textarea id="objetivos" name="objetivos" rows="4" required></textarea>
                </div>

                <!-- Nombre completo del responsable de la actividad -->
                <div>
                    <label for="responsable_actividad">Nombre completo del responsable de la actividad</label>
                    <input type="text" id="responsable_actividad" name="responsable_actividad" required>
                </div>

                <!-- Total de personas que asisten -->
                <div>
                    <label for="total_asistentes">Total de personas que asisten</label>
                    <input type="number" id="total_asistentes" name="total_asistentes" required>
                </div>

                <!-- Descripción de la logística -->
                <div>
                    <label for="logistica">Descripción de la logística</label>
                    <textarea id="logistica" name="logistica" rows="4"></textarea>
                </div>

                <!-- Tipo de transportación -->
                <div>
                    <label for="tipo_transportacion">Tipo de transportación</label>
                    <input type="text" id="tipo_transportacion" name="tipo_transportacion">
                </div>

                <!-- Estatus de la solicitud -->
                <div>
                    <label for="estatus_solicitud">Estatus de la solicitud</label>
                    <select id="estatus_solicitud" name="estatus_solicitud" required>
                        <option value="Sin revisar">Sin revisar</option>
                    </select>
                </div>

                <div class="button-container">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="window.location.href='listaAct.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Mostrar/ocultar el campo de convenio según la selección
        $('#se_usara_convenio').change(function() {
            if ($(this).val() === 'Sí') {
                $('#nombre_convenio_div').show();
            } else {
                $('#nombre_convenio_div').hide();
            }
        });
    </script>
</body>

</html>