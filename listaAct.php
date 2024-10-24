<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Actividades</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Solicitud de Actividades</h2>

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
                    <option value="Aprobado">Aprobado</option>
                    <option value="Sin revisar">Sin Revisar</option>
                </select>
            </div>

            <!-- Barra de búsqueda -->
            <div class="col ms-auto">
                <input type="text" id="search" class="form-control" placeholder="Buscar...">
            </div>

            <!-- Botones de "Crear Nueva Solicitud" y "Menú" alineados a la derecha -->
            <div class="col-auto">
                <a href="actividades.php" class="btn btn-primary">Crear Nueva Solicitud</a>
            </div>
            <div class="col-auto">
                <a href="index.html" class="btn btn-secondary">Menú</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre de la Actividad</th>
                    <th class="text-center">Área Solicitante</th>
                    <th class="text-center">Nombre Solicitante</th>
                    <th class="text-center">Correo Solicitante</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Convenio Utilizado</th>
                    <th class="text-center">Fecha de Inicio</th>
                    <th class="text-center">Fecha de Fin</th>
                    <th class="text-center">Estatus de la Solicitud</th>
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
                    url: "load_act.php",
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
                        
                        // Si no hay datos y no se está filtrando por estatus
                        if ((!data.data || data.data.length === 0) && estatus === '' && search !== '') {
                            alert("No se encontraron resultados.");
                        }
                        
                        var table_body = '';
                        $.each(data.data, function(key, value){
                            table_body += '<tr>';
                            table_body += '<td>' + value.id + '</td>'; 
                            table_body += '<td>' + value.nombre_actividad + '</td>';
                            table_body += '<td>' + value.area_coordinacion + '</td>';
                            table_body += '<td>' + value.nombre_solicitante + '</td>';
                            table_body += '<td>' + value.correo_solicitante + '</td>';
                            table_body += '<td>' + value.telefono_celular_solicitante + '</td>';
                            table_body += '<td>' + (value.nombre_convenio ? value.nombre_convenio : 'Sin convenio') + '</td>';
                            table_body += '<td>' + value.fecha_inicio + '</td>';
                            table_body += '<td>' + value.fecha_fin + '</td>';
                            table_body += '<td>' + value.estatus_solicitud + '</td>';
                            // table_body += '<td><button class="btn btn-info btn-sm">Ver Info</button></td>';
                            table_body += '<td><a href="verActividad.php?id=' + value.id + '" class="btn btn-info d-flex align-items-center text-nowrap"><i class="fas fa-file-alt" style="margin-right: 5px;"></i>Ver Info</a></td>';
                            table_body += '</tr>';
                        });
                        $('#table-body').html(table_body);

                        // Generar paginación
                        var pagination = '';
                        for (var i = 1; i <= data.totalPages; i++) {
                            pagination += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                        }
                        $('#pagination').html('<ul class="pagination">' + pagination + '</ul>');
                    }
                });
            }

            // Carga inicial
            load_data(1);

            // Búsqueda
            $('#search').keyup(function(){
                var search = $(this).val();
                var registros = $('#num_registros').val();
                var estatus = $('#estatus').val();
                load_data(1, search, registros, estatus);
            });

            // Filtro por estatus
            $('#estatus').change(function(){
                var estatus = $(this).val();
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                load_data(1, search, registros, estatus);
            });

            // Cambiar el número de registros por página
            $('#num_registros').change(function(){
                var registros = $(this).val();
                var search = $('#search').val();
                var estatus = $('#estatus').val();
                load_data(1, search, registros, estatus);
            });

            // Manejo de la paginación
            $(document).on('click', '.page-link', function(event) {
                event.preventDefault();
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



