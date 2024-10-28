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

// Parámetros para la búsqueda y la paginación
$search = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 10; // Límite de filas por página
$offset = ($page - 1) * $limit;

// Consulta SQL con JOIN entre las tablas contactos y organizacion
$query = "
    SELECT 
        contactos.id, 
        contactos.nombre AS contacto_nombre, 
        contactos.apellido AS contacto_apellido, 
        contactos.cargo, 
        contactos.area_departamento, 
        contactos.correo_electronico, 
        contactos.telefono_celular, 
        contactos.telefono_oficina_ext, 
        contactos.principal, 
        contactos.estatus, 
        contactos.horarios_atencion,
        organizacion.nombre AS organizacion_nombre
    FROM contactos
    LEFT JOIN organizacion ON contactos.organizacion_id = organizacion.id
    WHERE 
        contactos.nombre LIKE ? OR 
        contactos.apellido LIKE ? OR 
        contactos.cargo LIKE ? OR 
        organizacion.nombre LIKE ?
    LIMIT ?, ?
";

// Prepara la consulta
$stmt = $mysqli->prepare($query);
$searchTerm = "%" . $search . "%";
$stmt->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $limit);
$stmt->execute();

// Obtiene los resultados
$result = $stmt->get_result();

// Construye la tabla de resultados
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'nombre_completo' => $row['contacto_nombre'] . ' ' . $row['contacto_apellido'], // Nombre completo
        'telefono' => $row['telefono_celular'], // Teléfono celular
        'telefono_oficina_ext' => $row['telefono_oficina_ext'],
        'cargo' => $row['cargo'],
        'area_departamento' => $row['area_departamento'],
        'correo_electronico' => $row['correo_electronico'],
        'principal' => $row['principal'],
        'estatus' => $row['estatus'],
        'horarios_atencion' => $row['horarios_atencion'],
        'organizacion_nombre' => $row['organizacion_nombre']
    ];
}

// Conteo total para la paginación
$countQuery = "
    SELECT COUNT(contactos.id) AS total
    FROM contactos
    LEFT JOIN organizacion ON contactos.organizacion_id = organizacion.id
    WHERE 
        contactos.nombre LIKE ? OR 
        contactos.apellido LIKE ? OR 
        contactos.cargo LIKE ? OR 
        organizacion.nombre LIKE ?
";
$countStmt = $mysqli->prepare($countQuery);
$countStmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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



