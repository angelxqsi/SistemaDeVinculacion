<?php 
// Conexión a la base de datos
$host = 'databaserv.cx8y42y62ekz.us-east-2.rds.amazonaws.com'; 
$user = 'admin'; 
$password = 'root2024'; 
$database = 'riviera';

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

// Construye la consulta base
$query = "
    SELECT 
        nc.id,
        o.logotipo AS logotipo,
        o.nombre AS nombre_organizacion,
        IFNULL(CONCAT(c.nombre, ' ', c.apellido), 'Sin contacto principal') AS nombre_representante, -- Mostrar mensaje si no hay contacto principal
        nc.responsable_convenio,
        nc.fecha_fin_convenio,
        nc.estatus_convenio
    FROM nuevos_convenios nc
    JOIN organizacion o ON nc.organizacion_id = o.id
    LEFT JOIN contactos c ON o.id = c.organizacion_id AND c.principal = 1 -- Solo incluye el contacto principal
    WHERE 
        o.nombre LIKE ? OR 
        CONCAT(c.nombre, ' ', c.apellido) LIKE ? OR 
        nc.responsable_convenio LIKE ? OR 
        nc.estatus_convenio LIKE ? OR 
        nc.fecha_fin_convenio LIKE ?";

// Si el filtro de estatus no está vacío, se agrega a la consulta
if (!empty($estatus)) {
    $query .= " AND nc.estatus_convenio = ?";
}

// Agrega la paginación
$query .= " LIMIT ?, ?";

// Prepara la consulta
$stmt = $mysqli->prepare($query);

// Construye el parámetro de búsqueda
$searchTerm = "%" . $search . "%";

// Vincula los parámetros a la consulta
if (!empty($estatus)) {
    $stmt->bind_param('ssssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $estatus, $offset, $limit);
} else {
    $stmt->bind_param('sssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $limit);
}

// Ejecuta la consulta y verifica errores
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

// Obtiene los resultados
$result = $stmt->get_result();

// Construye el array de datos
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Conteo total para la paginación
$countQuery = "
    SELECT COUNT(nc.id) AS total
    FROM nuevos_convenios nc
    JOIN organizacion o ON nc.organizacion_id = o.id
    LEFT JOIN contactos c ON o.id = c.organizacion_id AND c.principal = 1
    WHERE 
        o.nombre LIKE ? OR 
        CONCAT(c.nombre, ' ', c.apellido) LIKE ? OR 
        nc.responsable_convenio LIKE ? OR 
        nc.estatus_convenio LIKE ? OR 
        nc.fecha_fin_convenio LIKE ?";

// Prepara la consulta para el conteo
$countStmt = $mysqli->prepare($countQuery);

// Vincula los parámetros para el conteo
if (!empty($estatus)) {
    $countStmt->bind_param('ssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $estatus);
} else {
    $countStmt->bind_param('sssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];

// Calcula el número de páginas
$totalPages = ceil($totalRows / $limit);

// Retorna la respuesta en formato JSON
$response = [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page
];

header('Content-Type: application/json');
echo json_encode($response);

$mysqli->close();
?>
