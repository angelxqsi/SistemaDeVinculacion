<?php
require 'config.php'; // Conexión a la base de datos

// Consulta para obtener las organizaciones
$sql_org = "SELECT id, nombre FROM organizacion";
$resultado_org = $conn->query($sql_org);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="styleContacto.css">
</head>
<body>
    <header>
        <a href="index.html">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Datos de contacto</h2>
        <form action="submit_contacto.php" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div>
                    <label for="nombre">Nombre(s)</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div>
                    <label for="apellido">Apellido(s)</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>

                <div>
                    <label for="cargo">Cargo</label>
                    <input type="text" id="cargo" name="cargo" required>
                </div>

                <div>
                    <label for="area">Área o Departamento</label>
                    <select id="area" name="area" required>
                        <option value="administracion">Administración</option>
                        <option value="ventas">Ventas</option>
                        <option value="recursos_humanos">Recursos Humanos</option>
                        <option value="tecnologia">Tecnología</option>
                        <option value="marketing">Marketing</option>
                        <option value="finanzas">Finanzas</option>
                    </select>
                </div>

                <div>
                    <label for="nombre_organizacion">Nombre de la organización</label>
                    <select id="nombre_organizacion" name="organizacion_id" required>
                        <?php
                        while ($row_org = $resultado_org->fetch_assoc()) {
                            echo "<option value='{$row_org['id']}'>{$row_org['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" required>
                </div>

                <div>
                    <label for="telefono_celular">Teléfono Celular</label>
                    <input type="tel" id="telefono_celular" name="telefono_celular" required>
                </div>

                <div>
                    <label for="telefono_oficina">Teléfono Oficina con Extensión</label>
                    <input type="tel" id="telefono_oficina" name="telefono_oficina" placeholder="Teléfono con Extensión">
                </div>

                <div>
                    <label for="principal">Principal</label>
                    <select id="principal" name="principal" required>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div>
                    <label for="estatus">Estatus</label>
                    <select id="estatus" name="estatus" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label for="horarios_dias">Días de Atención</label>
                    <div style="display: flex; align-items: center;">
                        <select id="horarios_dia1" name="horarios_dia1" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sabado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                        <span style="margin: 0 10px;">a</span>
                        <select id="horarios_dia2" name="horarios_dia2" required>
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miércoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sabado">Sábado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="horarios_hora1">Horario de Atención</label>
                    <div style="display: flex; align-items: center;">
                        <select id="horarios_hora1" name="horarios_hora1" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select id="horarios_am_pm1" name="horarios_am_pm1" required>
                            <option value="am">AM</option>
                        </select>
                        <span style="margin: 0 10px;">-</span>
                        <select id="horarios_hora2" name="horarios_hora2" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select id="horarios_am_pm2" name="horarios_am_pm2" required>
                            <option value="pm">PM</option>
                        </select>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="window.location.href='contactos.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>





