<?php
// Incluir el script de actualización
include 'actualizar_estatus.php';
?>
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
        <h2 class="text-center">Lista de Convenios</h2>

        <div class="row g-4 align-items-center mb-4">
            <!-- Configuración de registros, búsqueda y botones -->
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
                <select id="estatus" class="form-select">
                    <option value="">Todos los Estatus</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                    <option value="Vencido">Vencido</option>
                    <option value="Por Vencer">Por  Vencer</option>
                </select>
            </div>

            <div class="col ms-auto">
                <input type="text" id="search" class="form-control" placeholder="Buscar...">
            </div>

            <div class="col-auto">
                <a href="convenio.php" class="btn btn-primary">Crear Nuevo Convenio</a>
            </div>
            <div class="col-auto">
                <a href="convenios.html" class="btn btn-secondary">Menú</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    
                    <th class="text-center">Logotipo</th>
                    <th class="text-center">Nombre de la Organización</th>
                    <th class="text-center">Nombre del Representante</th>
                    <th class="text-center">Fecha de Fin</th>
                    <th class="text-center">Estatus del Convenio</th>
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
                    url: "loadSoliConve.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        page: page, 
                        search: search,
                        registros: registros,
                        estatus: estatus
                    },
                    success: function(data) {
                        var table_body = '';
                        $.each(data.data, function(key, value){
                            table_body += '<tr>';
                            
                            table_body += '<td><img src="uploads/' + value.logotipo + '" width="50"></td>';  // Logotipo
                            table_body += '<td>' + value.nombre_organizacion + '</td>';  // Organización
                            table_body += '<td>' + value.nombre_representante + '</td>';  // Representante
                            table_body += '<td>' + value.fecha_fin_convenio + '</td>';  // Fecha fin
                            table_body += '<td>' + value.estatus_convenio + '</td>';  // Estatus
                            // table_body += '<td><a href="verConvenio.php?id=' + value.id + '" class="btn btn-info">Ver Info</a></td>';
                            table_body += '<td><a href="verConvenio.php?id=' + value.id + '" class="btn btn-info d-flex align-items-center"><i class="fas fa-file-alt" style="margin-right: 5px;"></i>Ver Info</a></td>';

                            // table_body += '<td><a href="#" class="btn btn-warning">Editar</a>';
                            //<a href="#" class="btn btn-danger">Eliminar</a></td>';  // Acciones
                            table_body += '</tr>';
                        });
                        $('#table-body').html(table_body);

                        var pagination_html = '';
                        for (var i = 1; i <= data.totalPages; i++) {
                            pagination_html += '<button class="btn btn-outline-primary mx-1 page-link" data-page="' + i + '">' + i + '</button>';
                        }
                        $('#pagination').html(pagination_html);
                    }
                });
            }

            // Carga inicial
            load_data(1);

            // Cambio en la cantidad de registros
            $('#num_registros').change(function(){
                var registros = $(this).val();
                load_data(1, $('#search').val(), registros, $('#estatus').val());
            });

            // Búsqueda
            $('#search').keyup(function(){
                var search = $(this).val();
                load_data(1, search, $('#num_registros').val(), $('#estatus').val());
            });

            // Cambio de estatus
            $('#estatus').change(function(){
                var estatus = $(this).val();
                load_data(1, $('#search').val(), $('#num_registros').val(), estatus);
            });

            // Paginación
            $(document).on('click', '.page-link', function(){
                var page = $(this).data('page');
                load_data(page, $('#search').val(), $('#num_registros').val(), $('#estatus').val());
            });
        });
    </script>
</body>
</html>
