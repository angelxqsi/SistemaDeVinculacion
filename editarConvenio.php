<?php
require 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM solicitud_convenios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $solicitud = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud de Convenio</title>
    <link rel="stylesheet" href="styleSoliConv.css">
</head>
<body>
    <header>
        <a href="index.html">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Editar Datos de Solicitud de Convenio</h2>
        <form action="updateSoliConv.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $solicitud['id'] ?? ''; ?>">
            <div class="form-grid">
                <div>
                    <label for="nombre_organizacion">Nombre de la organización</label>
                    <input type="text" id="nombre_organizacion" name="nombre_organizacion" value="<?php echo htmlspecialchars($solicitud['nombre_organizacion'] ?? ''); ?>" required>
                </div>

                <div>
                    <label for="logotipo">Logotipo (dejar en blanco para no cambiar)</label>
                    <input type="file" id="logotipo" name="logotipo" accept="image/*">
                </div>

                <div>
                    <label for="objetivos">Objetivos del convenio</label>
                    <textarea id="objetivos" name="objetivos" rows="4" required><?php echo htmlspecialchars($solicitud['objetivos'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label for="nombre_solicitante">Nombre del solicitante</label>
                    <input type="text" id="nombre_solicitante" name="nombre_solicitante" value="<?php echo htmlspecialchars($solicitud['nombre_solicitante'] ?? ''); ?>" required>
                </div>

                <div>
                    <label for="area_departamento">Área o departamento solicitante</label>
                    <input type="text" id="area_departamento" name="area_departamento" value="<?php echo htmlspecialchars($solicitud['area_solicitante'] ?? ''); ?>" required>
                </div>

                <div>
                    <label for="beneficiarios">Beneficiarios del convenio</label>
                    <textarea id="beneficiarios" name="beneficiarios" rows="4" required><?php echo htmlspecialchars($solicitud['beneficiarios'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label for="alcance">Alcance del convenio</label>
                    <textarea id="alcance" name="alcance" rows="4" required><?php echo htmlspecialchars($solicitud['alcance'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label for="beneficios_comerciales">Beneficios comerciales</label>
                    <textarea id="beneficios_comerciales" name="beneficios_comerciales" rows="4" required><?php echo htmlspecialchars($solicitud['beneficios_comerciales'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label for="estatus">Estatus del convenio</label>
                    <select id="estatus" name="estatus" required <?php echo ($solicitud['estatus'] === 'Aprobado') ? 'disabled' : ''; ?>>
    <option value="En revisión" <?php echo ($solicitud['estatus'] === 'En revisión') ? 'selected' : ''; ?>>En revisión</option>
    <option value="En validación" <?php echo ($solicitud['estatus'] === 'En validación') ? 'selected' : ''; ?>>En validación</option>
    <option value="En aprobación" <?php echo ($solicitud['estatus'] === 'En aprobación') ? 'selected' : ''; ?>>En aprobación</option>
    <option value="Aprobado" <?php echo ($solicitud['estatus'] === 'Aprobado') ? 'selected' : ''; ?>>Aprobado</option>
    <option value="No aprobado" <?php echo ($solicitud['estatus'] === 'No aprobado') ? 'selected' : ''; ?>>No aprobado</option>
</select>
                </div>

                <div class="button-container">
                    <button type="submit">Actualizar</button>
                    <button type="button" onclick="window.location.href='listaSoliConv.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>

