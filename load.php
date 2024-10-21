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

// Parámetros para la búsqueda y la paginación
$search = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 10; // Límite de filas por página
$offset = ($page - 1) * $limit;
$tipo_organizacion = isset($_POST['tipo_organizacion']) ? $_POST['tipo_organizacion'] : '';

// Construcción de la consulta SQL con filtros de búsqueda y tipo de organización
$query = "
    SELECT 
        organizacion.id, 
        organizacion.logotipo, 
        organizacion.nombre, 
        organizacion.giro, 
        organizacion.categoria, 
        organizacion.estatus,
        organizacion.tipo_organizacion, 
        contactos.nombre AS contacto_nombre,
        contactos.apellido AS contacto_apellido, 
        contactos.telefono_celular AS contacto_telefono, 
        contactos.correo_electronico AS contacto_email
    FROM organizacion
    LEFT JOIN contactos ON contactos.organizacion_id = organizacion.id AND contactos.principal = 'Sí'
    WHERE 
        (organizacion.nombre LIKE ? OR 
        organizacion.giro LIKE ? OR 
        organizacion.categoria LIKE ? OR 
        contactos.nombre LIKE ?)
";

// Agregar condición para el filtro de tipo de organización si está seleccionado
if (!empty($tipo_organizacion)) {
    $query .= " AND organizacion.tipo_organizacion = ?";
}

// Añadir límites de paginación
$query .= " LIMIT ?, ?";

// Preparar la consulta
$stmt = $mysqli->prepare($query);
$searchTerm = "%" . $search . "%";

// Enlazar parámetros según si el filtro de tipo de organización está activo
if (!empty($tipo_organizacion)) {
    $stmt->bind_param('ssssssi', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $tipo_organizacion, $offset, $limit);
} else {
    $stmt->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $limit);
}

$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();

// Construir la respuesta con los datos de la tabla
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Contar el total de resultados para la paginación
$countQuery = "
    SELECT COUNT(organizacion.id) AS total
    FROM organizacion
    LEFT JOIN contactos ON contactos.organizacion_id = organizacion.id AND contactos.principal = 'Sí'
    WHERE 
        (organizacion.nombre LIKE ? OR 
        organizacion.giro LIKE ? OR 
        organizacion.categoria LIKE ? OR 
        contactos.nombre LIKE ?)
";

// Añadir filtro de tipo de organización para el conteo si es necesario
if (!empty($tipo_organizacion)) {
    $countQuery .= " AND organizacion.tipo_organizacion = ?";
}

$countStmt = $mysqli->prepare($countQuery);

// Enlazar parámetros según si el filtro de tipo de organización está activo
if (!empty($tipo_organizacion)) {
    $countStmt->bind_param('sssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $tipo_organizacion);
} else {
    $countStmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];

// Calcular el número de páginas
$totalPages = ceil($totalRows / $limit);

// Responder con los datos en formato JSON
$response = [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page
];

header('Content-Type: application/json');
echo json_encode($response);

$mysqli->close();
?>

