<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "riviera";

$mysqli = new mysqli($host, $user, $password, $database);

// Verifica la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Parámetros para la búsqueda, paginación y filtro de estatus
$search = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = isset($_POST['registros']) ? (int)$_POST['registros'] : 10; // Límite de filas por página
$estatus = isset($_POST['estatus']) ? $_POST['estatus'] : ''; // Filtro de estatus
$offset = ($page - 1) * $limit;

// Consulta base
$query = "SELECT * FROM actividades WHERE 1=1";

// Condiciones de búsqueda
if (!empty($search)) {
    $query .= " AND (nombre_actividad LIKE ? OR nombre_solicitante LIKE ? OR area_coordinacion LIKE ? OR programa_academico LIKE ? OR objetivos LIKE ?)";
}

// Si hay un estatus seleccionado, lo agregamos a la consulta
if (!empty($estatus)) {
    $query .= " AND estatus_solicitud = ?";
}

// Agregamos la paginación
$query .= " LIMIT ?, ?";

// Prepara la consulta
$stmt = $mysqli->prepare($query);

// Vincula los parámetros
$params = [];
if (!empty($search)) {
    $searchTerm = "%" . $search . "%";
    array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

// Si hay un estatus, también lo vinculamos
if (!empty($estatus)) {
    array_push($params, $estatus);
}

// Añade el offset y el limit al final
array_push($params, $offset, $limit);

// Prepara la declaración
$stmt->bind_param(str_repeat('s', count($params) - 2) . 'ii', ...$params);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// Obtener los resultados
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Retorna la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode(['data' => $data]);

$mysqli->close();
?>

