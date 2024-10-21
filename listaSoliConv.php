<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Solicitud de Convenio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Lista de Solicitud de Convenio</h2>

        <!-- Barra de búsqueda, selector de registros y botones en la misma fila -->
        <div class="row g-4 align-items-center mb-4">
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

            <!-- Filtro por estatus -->
            <div class="col-auto">
                <select id="estatus" class="form-select">
                    <option value="">Todos los Estatus</option>
                    <option value="En revisión">En Revisión</option>
                    <option value="En aprobación">En Aprobación</option>
                    <option value="En validación">En Validación</option>
                    <option value="Aprobado">Aprobado</option>
                    <option value="No aprobado">No Aprobado</option>
                </select>
            </div>

            <!-- Barra de búsqueda -->
            <div class="col ms-auto">
                <input type="text" id="search" class="form-control" placeholder="Buscar...">
            </div>

            <!-- Botones de "Crear Nueva Solicitud" y "Menú" alineados a la derecha -->
            <div class="col-auto">
                <a href="registroSoliConv.html" class="btn btn-primary">Crear Nueva Solicitud</a>
            </div>
            <div class="col-auto">
                <a href="index.html" class="btn btn-secondary">Menú</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Logotipo</th>
                    <th class="text-center">Nombre de la Organización</th>
                    <th class="text-center">Nombre del Solicitante</th>
                    <th class="text-center">Área que Solicita el Convenio</th>
                    <th class="text-center">Alcance de Solicitud</th>
                    <th class="text-center">Beneficios Comerciales</th>
                    <th class="text-center">Etapa de la Solicitud</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Aquí se insertarán los resultados -->
            </tbody>
        </table>

        <div id="pagination" class="mt-2 d-flex justify-content-center">
            <!-- Aquí se generará la paginación -->
        </div>
    </div>

    <script>
        $(document).ready(function(){
            function load_data(page, search = '', registros = 10, estatus = '') {
                $.ajax({
                    url: "loadSoliConv.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        page: page, 
                        search: search,
                        registros: registros,
                        estatus: estatus
                    },
                    success: function(data) {
                        console.log(data); 
                        if (!data.data || data.data.length === 0) {
                            alert("No se encontraron resultados.");
                        }
                        var table_body = '';
                        $.each(data.data, function(key, value){
                            table_body += '<tr>';
                            table_body += '<td>' + value.id + '</td>'; 
                            table_body += '<td><img src="uploads/' + value.logotipo + '" width="50"></td>';
                            table_body += '<td>' + value.nombre_organizacion + '</td>';
                            table_body += '<td>' + value.nombre_solicitante + '</td>';
                            table_body += '<td>' + value.area_solicitante + '</td>';
                            table_body += '<td>' + value.alcance + '</td>';
                            table_body += '<td>' + value.beneficios_comerciales + '</td>';
                            table_body += '<td>' + value.estatus + '</td>'; 
                            
                            table_body += '<td>';
                            table_body += '<div class="d-flex justify-content-between">';
                            table_body += '<a href="editarConvenio.php?id=' + value.id + '" class="btn btn-sm btn-warning mx-2 d-flex align-items-center"><i class="fas fa-edit" style="margin-right: 5px;"></i>Editar</a>';
                            table_body += '<a href="eliminar.php?id=' + value.id + '" class="btn btn-sm btn-danger mx-2 d-flex align-items-center"><i class="fas fa-trash-alt" style="margin-right: 5px;"></i>Eliminar</a>';
                            table_body += '</div>';
                            table_body += '</td>';
                            
                            table_body += '</tr>';
                        });
                        $('#table-body').html(table_body);

                        var pagination_html = '';
                        for (var i = 1; i <= data.totalPages; i++) {
                            pagination_html += '<button class="btn btn-primary mx-1 page-link" data-page="' + i + '">' + i + '</button>';
                        }
                        $('#pagination').html(pagination_html);
                    }
                });
            }

            load_data(1);

            $('#search').keyup(function(){
                var search = $(this).val();
                var registros = $('#num_registros').val();
                var estatus = $('#estatus').val();
                load_data(1, search, registros, estatus);
            });

            $('#num_registros').change(function(){
                var registros = $(this).val();
                var search = $('#search').val();
                var estatus = $('#estatus').val();
                load_data(1, search, registros, estatus);
            });

            $('#estatus').change(function(){
                var estatus = $(this).val();
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                load_data(1, search, registros, estatus);
            });

            $(document).on('click', '.page-link', function(){
                var page = $(this).data('page');
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                var estatus = $('#estatus').val();
                load_data(page, search, registros, estatus);
            });
        });
    </script>
</body>
</html>



