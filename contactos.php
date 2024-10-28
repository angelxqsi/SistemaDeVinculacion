<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Buscar contactos en tiempo real con PHP, MySQL y AJAX">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contactos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Lista de Contactos</h2>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">Mostrar:</label>
                </div>
                <div class="col-auto">
                    <select name="num_registros" id="num_registros" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">registros</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="search" class="form-control" placeholder="Buscar...">
                </div>
            </div>

            <div>
                <a href="registrocontacto.php" class="btn btn-primary ms-2">Crear Nuevo Contacto</a>
                <a href="index.php" class="btn btn-secondary ms-2">Menú</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre Completo</th>
                    <th class="text-center">Área/Departamento</th>
                    <th class="text-center">Correo Electrónico</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Horarios de Atención</th>
                    <th class="text-center">Organización</th> <!-- Nueva columna -->
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Aquí se insertarán los resultados -->
            </tbody>
        </table>

        <div id="pagination" class="mt-3 d-flex justify-content-center">
            <!-- Aquí se generará la paginación -->
        </div>
    </div>

    <script>
        $(document).ready(function(){
            function load_data(page, search = '', registros = 10) {
                $.ajax({
                    url: "load_contactos.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        page: page,
                        search: search,
                        registros: registros
                    },
                    success: function(data) {
                        var table_body = '';
                        $.each(data.data, function(key, value){
                            table_body += '<tr>';
                            table_body += '<td>' + value.id + '</td>';
                            table_body += '<td>' + value.nombre_completo + '</td>';
                            table_body += '<td>' + value.area_departamento + '</td>';
                            table_body += '<td>' + value.correo_electronico + '</td>';
                            table_body += '<td>' + value.telefono + '</td>';
                            table_body += '<td>' + value.horarios_atencion + '</td>';
                            table_body += '<td>' + value.organizacion_nombre + '</td>'; // Nueva columna para la organización
                            table_body += '<td>';
                            table_body += '<div class="d-flex justify-content-between">';
                            table_body += '<a href="editar_contacto.php?id=' + value.id + '" class="btn btn-sm btn-warning mx-2 d-flex align-items-center"><i class="fas fa-edit" style="margin-right: 5px;"></i>Editar</a>';
                            table_body += '<a href="eliminar_contacto.php?id=' + value.id + '" class="btn btn-sm btn-danger mx-2 d-flex align-items-center"><i class="fas fa-trash-alt" style="margin-right: 5px;"></i>Eliminar</a>';
                            table_body += '</div>';
                            table_body += '</td>';
                            table_body += '</tr>';
                        });
                        $('#table-body').html(table_body);

                        var pagination_html = '<nav aria-label="Page navigation"><ul class="pagination">';
                        for (var i = 1; i <= data.totalPages; i++) {
                            pagination_html += '<li class="page-item"><button class="btn btn-light page-link" data-page="' + i + '">' + i + '</button></li>';
                        }
                        pagination_html += '</ul></nav>';
                        $('#pagination').html(pagination_html);
                    }
                });
            }

            load_data(1);

            $('#search').keyup(function(){
                var search = $(this).val();
                var registros = $('#num_registros').val();
                load_data(1, search, registros);
            });

            $('#num_registros').change(function(){
                var registros = $(this).val();
                var search = $('#search').val();
                load_data(1, search, registros);
            });

            $(document).on('click', '.page-link', function(){
                var page = $(this).data('page');
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                load_data(page, search, registros);
            });
        });
    </script>
</body>
</html>











