<?php
require 'config.php';

// Verificar si se ha pasado un ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM organizacion WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $org = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Organización</title>
    <link rel="stylesheet" href="styleRegistro.css">
</head>
<body>
    <header>
        <a href="index.html">
            <img src="img/LOGO_RIVIERA.png" alt="Logo" class="logo">
        </a>
    </header>

    <div class="form-container">
        <h2>Editar Datos de la Organización</h2>
        <form action="updateOrg.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $org['id'] ?? ''; ?>">
            <div class="form-grid">
                <div>
                    <label for="nombre">Nombre de la organización</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($org['nombre'] ?? ''); ?>" required>
                </div>
                <div>
                    <label for="giro">Giro de la empresa</label>
                    <input type="text" id="giro" name="giro" value="<?php echo htmlspecialchars($org['giro'] ?? ''); ?>" required>
                </div>
                <div>
                    <label for="domicilio">Domicilio</label>
                    <input type="text" id="domicilio" name="domicilio" value="<?php echo htmlspecialchars($org['domicilio'] ?? ''); ?>" required>
                </div>
                <div>
                    <label for="web">Página web</label>
                    <input type="url" id="web" name="web" value="<?php echo htmlspecialchars($org['pagina_web'] ?? ''); ?>">
                </div>
                <div>
                    <label for="redes">Redes sociales</label>
                    <input type="text" id="redes" name="redes" value="<?php echo htmlspecialchars($org['redes'] ?? ''); ?>">
                </div>
                <div>
                    <label for="status">Estado</label>
                    <select id="status" name="status" required>
                        <option value="activo" <?php echo ($org['estatus'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo ($org['estatus'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
                <div>
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria">
                        <option value="a" <?php echo ($org['categoria'] === 'a') ? 'selected' : ''; ?>>A</option>
                        <option value="aa" <?php echo ($org['categoria'] === 'aa') ? 'selected' : ''; ?>>AA</option>
                        <option value="aaa" <?php echo ($org['categoria'] === 'aaa') ? 'selected' : ''; ?>>AAA</option>
                    </select>
                </div>
                <div class="full-width">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($org['descripcion'] ?? ''); ?></textarea>
                </div>
                <div class="full-width">
                    <label for="logotipo">Logotipo</label>
                    <input type="file" id="logotipo" name="logotipo" accept="image/*">
                </div>
                <div class="button-container">
                    <button type="submit">Actualizar</button>
                    <button type="button" onclick="window.location.href='organizaciones.php'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>

<?php
$conn->close();
?>


