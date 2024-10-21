<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM contactos WHERE id = $id";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $contacto = $resultado->fetch_assoc();
    } else {
        echo "Contacto no encontrado.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contacto</title>
    <link rel="stylesheet" href="styleContacto.css">
</head>
<body>
    <header>
        <a href="index.html">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Editar Contacto</h2>
        <form action="update_contacto.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $contacto['id']; ?>">

            <div class="form-grid">
                <div>
                    <label for="nombre">Nombre(s)</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($contacto['nombre']); ?>" required>
                </div>

                <div>
                    <label for="apellido">Apellido(s)</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($contacto['apellido']); ?>" required>
                </div>

                <div>
                    <label for="cargo">Cargo</label>
                    <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($contacto['cargo']); ?>" required>
                </div>

                <div>
                    <label for="area">Área o Departamento</label>
                    <select id="area" name="area" required>
                        <option value="administracion" <?php echo ($contacto['area_departamento'] == 'administracion') ? 'selected' : ''; ?>>Administración</option>
                        <option value="ventas" <?php echo ($contacto['area_departamento'] == 'ventas') ? 'selected' : ''; ?>>Ventas</option>
                        <option value="recursos_humanos" <?php echo ($contacto['area_departamento'] == 'recursos_humanos') ? 'selected' : ''; ?>>Recursos Humanos</option>
                        <option value="tecnologia" <?php echo ($contacto['area_departamento'] == 'tecnologia') ? 'selected' : ''; ?>>Tecnología</option>
                        <option value="marketing" <?php echo ($contacto['area_departamento'] == 'marketing') ? 'selected' : ''; ?>>Marketing</option>
                        <option value="finanzas" <?php echo ($contacto['area_departamento'] == 'finanzas') ? 'selected' : ''; ?>>Finanzas</option>
                    </select>
                </div>

                <div>
                    <label for="nombre_organizacion">Nombre de la organización</label>
                    <select id="nombre_organizacion" name="organizacion_id" required>
                        <?php
                        $sql_org = "SELECT id, nombre FROM organizacion";
                        $resultado_org = $conn->query($sql_org);
                        while ($row_org = $resultado_org->fetch_assoc()) {
                            $selected = ($row_org['id'] == $contacto['organizacion_id']) ? 'selected' : '';
                            echo "<option value='{$row_org['id']}' $selected>{$row_org['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($contacto['correo_electronico']); ?>" required>
                </div>

                <div>
                    <label for="telefono_celular">Teléfono Celular</label>
                    <input type="tel" id="telefono_celular" name="telefono_celular" value="<?php echo htmlspecialchars($contacto['telefono_celular']); ?>" required>
                </div>

                <div>
                    <label for="telefono_oficina">Teléfono Oficina con Extensión</label>
                    <input type="tel" id="telefono_oficina" name="telefono_oficina" value="<?php echo htmlspecialchars($contacto['telefono_oficina_ext']); ?>" placeholder="Teléfono con Extensión">
                </div>

                <div>
                    <label for="principal">Principal</label>
                    <select id="principal" name="principal" required>
                        <option value="1" <?php echo ($contacto['principal'] == '1') ? 'selected' : ''; ?>>Sí</option>
                        <option value="0" <?php echo ($contacto['principal'] == '0') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div>
                    <label for="estatus">Estatus</label>
                    <select id="estatus" name="estatus" required>
                        <option value="activo" <?php echo ($contacto['estatus'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo ($contacto['estatus'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>

                <div>
                    <label for="horarios_dia1">Días de Atención</label>
                    <div style="display: flex; align-items: center;">
                        <select id="horarios_dia1" name="horarios_dia1" required>
                            <option value="lunes" <?php echo ($contacto['horarios_atencion'] == 'lunes') ? 'selected' : ''; ?>>Lunes</option>
                            <option value="martes" <?php echo ($contacto['horarios_atencion'] == 'martes') ? 'selected' : ''; ?>>Martes</option>
                            <option value="miercoles" <?php echo ($contacto['horarios_atencion'] == 'miercoles') ? 'selected' : ''; ?>>Miércoles</option>
                            <option value="jueves" <?php echo ($contacto['horarios_atencion'] == 'jueves') ? 'selected' : ''; ?>>Jueves</option>
                            <option value="viernes" <?php echo ($contacto['horarios_atencion'] == 'viernes') ? 'selected' : ''; ?>>Viernes</option>
                            <option value="sabado" <?php echo ($contacto['horarios_atencion'] == 'sabado') ? 'selected' : ''; ?>>Sábado</option>
                            <option value="domingo" <?php echo ($contacto['horarios_atencion'] == 'domingo') ? 'selected' : ''; ?>>Domingo</option>
                        </select>
                        <span style="margin: 0 10px;">a</span>
                        <select id="horarios_dia2" name="horarios_dia2" required>
                            <option value="lunes" <?php echo ($contacto['horarios_atencion'] == 'lunes') ? 'selected' : ''; ?>>Lunes</option>
                            <option value="martes" <?php echo ($contacto['horarios_atencion'] == 'martes') ? 'selected' : ''; ?>>Martes</option>
                            <option value="miercoles" <?php echo ($contacto['horarios_atencion'] == 'miercoles') ? 'selected' : ''; ?>>Miércoles</option>
                            <option value="jueves" <?php echo ($contacto['horarios_atencion'] == 'jueves') ? 'selected' : ''; ?>>Jueves</option>
                            <option value="viernes" <?php echo ($contacto['horarios_atencion'] == 'viernes') ? 'selected' : ''; ?>>Viernes</option>
                            <option value="sabado" <?php echo ($contacto['horarios_atencion'] == 'sabado') ? 'selected' : ''; ?>>Sábado</option>
                            <option value="domingo" <?php echo ($contacto['horarios_atencion'] == 'domingo') ? 'selected' : ''; ?>>Domingo</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="horarios_hora1">Horario de Atención</label>
                    <div style="display: flex; align-items: center;">
                        <select id="horarios_hora1" name="horarios_hora1" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($contacto['horarios_atencion'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select id="horarios_am_pm1" name="horarios_am_pm1" required>
                            <option value="am" <?php echo ($contacto['horarios_atencion'] == 'am') ? 'selected' : ''; ?>>AM</option>
                            <option value="pm" <?php echo ($contacto['horarios_atencion'] == 'pm') ? 'selected' : ''; ?>>PM</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="horarios_hora2">Hasta</label>
                    <div style="display: flex; align-items: center;">
                        <select id="horarios_hora2" name="horarios_hora2" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($contacto['horarios_atencion'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select id="horarios_am_pm2" name="horarios_am_pm2" required>
                            <option value="am" <?php echo ($contacto['horarios_atencion'] == 'am') ? 'selected' : ''; ?>>AM</option>
                            <option value="pm" <?php echo ($contacto['horarios_atencion'] == 'pm') ? 'selected' : ''; ?>>PM</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit">Actualizar Contacto</button>
            <button type="button" onclick="window.location.href='contactos.php'">Cancelar</button>
        </form>
    </div>
</body>
</html>










