<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Organizaciones</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Lista de Organizaciones</h2>

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

            <!-- Cambiar el filtro a Tipo de Organización -->
            <div class="col-auto">
                <select id="tipo_organizacion" class="form-select">
                    <option value="">Todos los Tipos</option>
                    <option value="Publica">Pública</option>
                    <option value="Privada">Privada</option>
                </select>
            </div>

            <div class="col-auto ml-auto">
                <input type="text" id="search" class="form-control" placeholder="Buscar...">
            </div>

            <div class="col-auto">
                <a href="RegistroOrg.html" class="btn btn-primary">Crear Nueva Organización</a>
            </div>
            <div class="col-auto">
                <a href="index.php" class="btn btn-secondary">Menú</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center">Logotipo</th>
                    <th class="text-center">Nombre de la Organización</th>
                    <th class="text-center">Tipo de Organización</th>
                    <th class="text-center">Giro</th>
                    <th class="text-center">Categoría</th>
                    <th class="text-center">Contacto Principal</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Email</th>
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
            function load_data(page, search = '', registros = 10, tipo_organizacion = '') {
                $.ajax({
                    url: "load.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        page: page, 
                        search: search,
                        registros: registros,
                        tipo_organizacion: tipo_organizacion // Enviar tipo de organización como parte de la solicitud
                    },
                    success: function(data) {
                        var table_body = '';
                        $.each(data.data, function(key, value){
                            table_body += '<tr>';
                            table_body += '<td><img src="uploads/' + value.logotipo + '" width="50"></td>';
                            table_body += '<td>' + value.nombre + '</td>';
                            table_body += '<td>' + value.tipo_organizacion + '</td>';
                            table_body += '<td>' + value.giro + '</td>';
                            table_body += '<td>' + value.categoria + '</td>';
                            // table_body += '<td>' + (value.contacto_nombre ? value.contacto_nombre : 'No disponible') + '</td>';
                            table_body += '<td>' + (value.contacto_nombre && value.contacto_apellido ? value.contacto_nombre + ' ' + value.contacto_apellido : 'No disponible') + '</td>';
                            table_body += '<td>' + (value.contacto_telefono ? value.contacto_telefono : 'No disponible') + '</td>';
                            table_body += '<td>' + (value.contacto_email ? value.contacto_email : 'No disponible') + '</td>';
                            table_body += '<td>';
                            table_body += '<div class="d-flex justify-content-between">';
                            table_body += '<a href="editarOrg.php?id=' + value.id + '" class="btn btn-sm btn-warning mx-2 d-flex align-items-center"><i class="fas fa-edit" style="margin-right: 5px;"></i>Editar</a>';
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
                var tipo_organizacion = $('#tipo_organizacion').val();
                load_data(1, search, registros, tipo_organizacion);
            });

            $('#num_registros').change(function(){
                var registros = $(this).val();
                var search = $('#search').val();
                var tipo_organizacion = $('#tipo_organizacion').val();
                load_data(1, search, registros, tipo_organizacion);
            });

            $('#tipo_organizacion').change(function(){
                var tipo_organizacion = $(this).val();
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                load_data(1, search, registros, tipo_organizacion);
            });

            $(document).on('click', '.page-link', function(){
                var page = $(this).data('page');
                var search = $('#search').val();
                var registros = $('#num_registros').val();
                var tipo_organizacion = $('#tipo_organizacion').val();
                load_data(page, search, registros, tipo_organizacion);
            });
        });
    </script>
</body>
</html>








