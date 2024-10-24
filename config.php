<?php 
// Datos de conexión
$host = 'databaserv.cx8y42y62ekz.us-east-2.rds.amazonaws.com'; 
$user = 'admin'; 
$password = 'root2024'; 
$database = 'riviera';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    error_log("Error de conexión: " . $conn->connect_error); // Loguea el error en lugar de mostrarlo
    die("Lo sentimos, estamos experimentando problemas técnicos."); // Mensaje más amigable para el usuario
}
?>
