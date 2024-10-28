<?php
// Configuración de la conexión a la base de datos
$host = 'databaserv.cx8y42y62ekz.us-east-2.rds.amazonaws.com'; 
$user = 'admin'; 
$password = 'root2024'; 
$database = 'riviera';

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para actualizar los convenios que están por vencer
    $sql_por_vencer = "UPDATE nuevos_convenios 
                        SET estatus_convenio = 'Por Vencer' 
                        WHERE fecha_fin_convenio BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                          AND estatus_convenio != 'Vencido' 
                          AND estatus_convenio != 'Por Vencer'";

    // Ejecutar la consulta para convenios por vencer
    $stmt = $conn->prepare($sql_por_vencer);
    $stmt->execute();

    // Consulta SQL para actualizar los convenios vencidos
    $sql_vencidos = "UPDATE nuevos_convenios 
                     SET estatus_convenio = 'Vencido' 
                     WHERE fecha_fin_convenio < CURDATE() 
                       AND estatus_convenio != 'Vencido'";

    // Ejecutar la consulta para convenios vencidos
    $stmt = $conn->prepare($sql_vencidos);
    $stmt->execute();

    // Consulta SQL para actualizar los convenios activos
    $sql_activos = "UPDATE nuevos_convenios 
                    SET estatus_convenio = 'Activo' 
                    WHERE fecha_fin_convenio > DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                      AND estatus_convenio != 'Activo'";

    // Ejecutar la consulta para convenios activos
    $stmt = $conn->prepare($sql_activos);
    $stmt->execute();

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>

