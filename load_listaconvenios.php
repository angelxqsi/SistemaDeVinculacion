<?php
// Conexión a la base de datos
$$host = 'databaserv.cx8y42y62ekz.us-east-2.rds.amazonaws.com'; 
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

// Consulta SQL para obtener los datos de la tabla solicitud_convenio_detalles
$query = "
    SELECT 
        sc.id, 
        sc.organizacion_id, 
        o.nombre AS nombre_organizacion,
        sc.nombre_representante, 
        sc.cargo_representante, 
        sc.nombre_solicitud, 
        sc.objetivos_convenio, 
        sc.alcance_convenio, 
        sc.beneficios_comerciales, 
        sc.responsable_convenio, 
        sc.estatus, 
        sc.total_renovaciones, 
        sc.razon_social, 
        sc.domicilio_fiscal, 
        sc.acta_constitutiva_pdf, 
        sc.fecha_inicio_convenio, 
        sc.documento_convenio_pdf, 
        sc.notas_generales, 
        sc.fecha_fin_convenio 
    FROM solicitud_convenio_detalles sc
    JOIN organizacion o ON sc.organizacion_id = o.id
    WHERE 
        sc.nombre_solicitud LIKE ? OR 
        sc.nombre_representante LIKE ? OR 
        sc.alcance_convenio LIKE ? OR 
        sc.beneficios_comerciales LIKE ?
    LIMIT ? OFFSET ?
";

// Prepara la consulta
$stmt = $mysqli->prepare($query);
$searchTerm = "%" . $search . "%";
$stmt->bind_param('ssssii', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
$stmt->execute();

// Obtiene los resultados
$result = $stmt->get_result();

// Construye la tabla de resultados
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'organizacion_id' => $row['organizacion_id'],
        'nombre_organizacion' => $row['nombre_organizacion'], // Ahora guardamos el nombre de la organización
        'nombre_representante' => $row['nombre_representante'],
        'cargo_representante' => $row['cargo_representante'],
        'nombre_solicitud' => $row['nombre_solicitud'],
        'objetivos_convenio' => $row['objetivos_convenio'],
        'alcance_convenio' => $row['alcance_convenio'],
        'beneficios_comerciales' => $row['beneficios_comerciales'],
        'responsable_convenio' => $row['responsable_convenio'],
        'estatus' => $row['estatus'],
        'total_renovaciones' => $row['total_renovaciones'],
        'razon_social' => $row['razon_social'],
        'domicilio_fiscal' => $row['domicilio_fiscal'],
        'acta_constitutiva_pdf' => $row['acta_constitutiva_pdf'],
        'fecha_inicio_convenio' => $row['fecha_inicio_convenio'],
        'documento_convenio_pdf' => $row['documento_convenio_pdf'],
        'notas_generales' => $row['notas_generales'],
        'fecha_fin_convenio' => $row['fecha_fin_convenio'],
    ];
}

// Conteo total para la paginación
$countQuery = "
    SELECT COUNT(sc.id) AS total
    FROM solicitud_convenio_detalles sc
    JOIN organizacion o ON sc.organizacion_id = o.id
    WHERE 
        sc.nombre_solicitud LIKE ? OR 
        sc.nombre_representante LIKE ? OR 
        sc.alcance_convenio LIKE ? OR 
        sc.beneficios_comerciales LIKE ?
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
