<?php 
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$database = 'riviera'; 

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
