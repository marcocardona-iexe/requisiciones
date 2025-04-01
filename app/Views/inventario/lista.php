<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?= base_url('public/assets/') ?>"
    data-template="vertical-menu-template-free">

<?= $head; ?>
<style>
tbody {
    font-size: 12px;
}   

</style>
<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?= $menu; ?>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?= $nav; ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inventario /</span> Lista</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row align-items-center mt-3">
                                    <div class="col-md-3">
                                        <label for="terminal" class="form-label">Area</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-map"></i></span>
                                            <select class="form-select" id="filtroArea">
                                                <option value="0">Seleccione un area</option>
                                                <?php foreach ($areas as $area) : ?>
                                                    <option value="<?= $area->id; ?>"><?= $area->area; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="terminal" class="form-label">Categorias</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-map"></i></span>
                                            <select class="form-select" id="filtroCategoria">
                                                <option value="0">Seleccione una categoria</option>
                                                <?php foreach ($categorias as $categoria) : ?>
                                                    <option value="<?= $categoria->id; ?>"><?= $categoria->categoria; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Botón de búsqueda -->
                                    <div class=" col-auto mt-4">
                                        <button class="btn btn-info btn-sm" id="btnFiltrar">
                                            <i class='bx bx-search-alt'></i> Buscar
                                        </button>
                                    </div>
                                    <div class=" col-auto mt-4">
                                        <button class="btn btn-info btn-sm" id="reiniciar">
                                            <i class='bx bx-select-multiple'></i> Ver todo
                                        </button>
                                    </div>
                                </div>
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregarProducto"><i class='bx bx-plus-circle'></i>Agregar productos</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-striped table-hover table-bordered" id="tablaInventario">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Características</th>
                                                    <th>Categoría</th>
                                                    <th>Área</th>
                                                    <th>Stock</th>
                                                    <th>Stock Mínimo</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <!--/ Basic Bootstrap Table -->
                        <hr class="my-5" />
                        <!--/ Responsive Table -->
                    </div>
                    <!-- / Content -->

                    <?= $footer; ?>

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <?= $js; ?>
    <script>
        $(document).ready(function() {

            let ventanaProductosInventario = null;
            let proovedores = "";

            var tabla = $('#tablaInventario').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "get-inventario-table",
                    type: "POST",
                    data: function(d) {
                        d.categoria = $('#filtroCategoria').val(); // Se envía el ID de la categoría seleccionada
                        d.area = $('#filtroArea').val(); // Se envía el ID del área seleccionada
                    }
                },
                order: [
                    [0, "asc"]
                ],
                columns: [{
                        data: "id_variante",
                    }, {
                        data: "nombre",
                    }, {
                        data: "caracteristicas",
                    }, {
                        data: "categoria_nombre",
                    }, {
                        data: "area_nombre",
                    }, {
                        data: "stock",
                    }, {
                        data: "stock_minimo",
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {

                            return `
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-paste'></i> Acciones
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li class="ver_solicitud"><a class="dropdown-item proovedores" href="#" data-proovedores="${row.id_variante}" ><i class='bx bx-list-ul'></i> Proovedores</a></li>
                                    </ul>
                                </div>
                            `;

                        }
                    }
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "No hay registros disponibles",
                    infoFiltered: "(filtrado de _MAX_ registros en total)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último",
                    },
                }
            });

            // Al hacer clic en "Buscar", se guardan los valores seleccionados y se recarga la tabla
            $('#btnFiltrar').on("click", function() {
                $('#filtroCategoria').data('selected', $('#filtroCategoria').val());
                $('#filtroArea').data('selected', $('#filtroArea').val());
                tabla.ajax.reload();
            });


            $('#reiniciar').on("click", function() {
                $('#filtroCategoria').data('selected', '').val('0');
                $('#filtroArea').data('selected', '').val('0');
                tabla.ajax.reload();
            });


        });



        // Al hacer clic en "Ver todo", se eliminan los filtros y se recarga la tabla sin filtros


        $("#agregarProducto").on("click", function(event) {

            ventanaProductosInventario = $.confirm({
                title: false,
                boxWidth: '600px',
                useBootstrap: false,
                content: `

            <style>

                .spinner {
                    width: 24px;
                    height: 24px;
                    border: 5px solid rgba(0, 0, 0, 0.3);
                    border-top-color: black;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .form-check-input:checked {
                    background-color: #66b0ff;
                    border-color: #66b0ff;
                }

                .form-check-input:focus {
                    box-shadow: none;
                    border: 1px solid #d9dee3;
                }

                .form-check-input:not(:focus) {
                    border: 1px solid #d9dee3 !important;
                }

                .form-check-input:not(:focus):checked {
                    border: 1px solid #d9dee3 !important;
                    box-shadow: none;
                }

                #guardar {
                    display: flex;
                    align-items: center;
                }

                #guardar i {
                    margin-right: 8px;
                }

            </style>

            <div class="container-fluid" style="padding: 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group pb-3">
                            
                            <label for="nombreProducto">Nombre del producto</label>

                            <div class="row">
                                <div class="col-md-9">
                                    <select class="form-control" id="nombreProducto">
                                        <option value="">Seleccione una categoría</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary" type="button" id="agregarInventario">Agregar</button>
                                </div>
                            </div>

                            <small class="form-text text-muted">Escribe el nombre del producto en la casiilla de texto</small>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group  pb-4">
                            <label for="categoria">Categoría</label>
                            <input type="text" class="form-control" id="categoria" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check pb-4">
                        <input class="form-check-input" type="checkbox" id="consumo">
                        <label class="form-check-label" for="consumo" style="user-select: none;">
                            Consumo
                        </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="caracteristicas">Caracteristicas</label>
                            <input type="text" class="form-control" id="caracteristicasProducto">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" class="form-control" id="valorProducto">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" style="margin-top: 24.5px; background-color: #3498db; font-weight: bold; border: none;" id="agregar">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 pt-4">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Caracteristicas</th>
                                    <th>Valor</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tablaProductos">
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center mt-2">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button type="button" class="btn btn-dark" id="guardar">
                                <i class="bx bx-save"></i> 
                                <span>Guardar</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="sProductos">
                </div>

            </div>
            `,
                buttons: false,
                onContentReady: function() {

                    $.ajax({
                        url: "http://127.0.0.1:8080/requisiciones/inventario/obtenerTipoInventario",
                        type: "GET",
                        success: function(articulosInventario) {

                            if (articulosInventario.status == "success") {

                                if (Array.isArray(articulosInventario.data)) {
                                    articulosInventario.data.forEach(function(articulo) {
                                        $("#nombreProducto").append(`
                                        <option value="${articulo.id}">${articulo.nombre}</option>
                                    `);
                                    });
                                }

                            } else {

                                alert("Error en base de datos");

                            }
                        }
                    });

                }
            });

        });

        $(document).on("click", "#agregar", function() {

            let caracteristicasProducto = $("#caracteristicasProducto").val().trim();
            let valorProducto = $("#valorProducto").val().trim();
            let verificaProducto = false;

            $("#tablaProductos tr").each(function() {
                let caracteristicas = $(this).find("td:eq(0)").text().trim();

                if (caracteristicasProducto.toLowerCase() == caracteristicas.toLowerCase()) {
                    alert("El producto ya existe ...");
                    verificaProducto = true;
                }

            });

            if (verificaProducto == true) {
                return;
            }

            if (caracteristicasProducto != "" && valorProducto != "") {

                $("#tablaProductos").append(`
                    <tr>
                        <td>${caracteristicasProducto}</td>
                        <td>${valorProducto}</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-warning">Eliminar</button>
                        </td>
                    </tr>
                `);

                $("#caracteristicasProducto").val("");
                $("#valorProducto").val("");

            } else {
                alert("Error el campo caracteristicas o el campo valor esta vacio ...");
            }

        });

        $(document).on("click", "#guardar", function() {

            let nombreProducto = $("#nombreProducto").val();
            let categoria = $("#categoria").val();
            let isChecked = $("#consumo").prop("checked");
            let consumo = 0;

            if (isChecked) {
                consumo = 1;
            } else {
                consumo = 0;
            }

            let datosProductos = [];

            $("#tablaProductos tr").each(function() {
                let caracteristicas = $(this).find("td:eq(0)").text().trim();
                let valor = $(this).find("td:eq(1)").text().trim();

                if (caracteristicas && valor) {
                    datosProductos.push({
                        caracteristicas: caracteristicas,
                        valor: valor
                    });
                }

            });

            let datosEnviar = JSON.stringify({
                nombre: nombreProducto,
                categoria: categoria,
                productos: datosProductos,
                consumo: consumo
            });

            if (nombreProducto == "") {
                alert("El nombre del producto esta vacio ..");
                return false;
            }

            if (categoria == "") {
                alert("El nombre de la categoria esta vacio ...");
                return false;
            }

            if (datosProductos.length === 0) {
                alert("No hay productos ingresados...");
                return false;
            }

            $(".sProductos").html(`
                <div style="display: flex;">
                    <div class="spinner"></div> 
                    <div style="
                    padding-top: 8px;
                    padding-bottom: 0px;
                    padding-left: 6px;">Procesando datos...</div>
                </div>
            `);

            $.ajax({
                url: "http://127.0.0.1:8080/requisiciones/inventario/guardar",
                type: "POST",
                data: datosEnviar,
                contentType: "application/json",
                success: function(respuesta) {

                    if (respuesta == true) {
                        $(".sProductos").html(`
                            <div style="padding-top: 15px;position: relative;" class="RespuestaFinal">
                                <div style="padding-top: 4px;">Datos cargados correctamente</div>
                                <svg style="position: absolute; left: 208px; top: 63%; transform: translateY(-50%);" width="24" height="24" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 25 L20 40 L40 10" stroke="#4CAF50" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        `);
                        $(".RespuestaFinal").fadeOut(10000);
                    } else {
                        $(".sProductos").html(`<div style="padding-top: 15px;" class="RespuestaFinal">Los datos no fueron cargados correctamente ... </div>`);
                        $(".RespuestaFinal").fadeOut(10000);

                    }

                    $("#nombreProducto").val("");
                    $("#categoria").val("");
                    $("#caracteristicasProducto").val("");
                    $("#valorProducto").val("");
                    $("#tablaProductos").empty();
                    $("#consumo").prop("checked", false);

                }
            });

        });

        $(document).on("change", "#nombreProducto", function() {

            let idInventario = $(this).val();

            $.ajax({
                url: "http://127.0.0.1:8080/requisiciones/inventario/obtenerCategoria/" + idInventario,
                type: "GET",
                success: function(respuesta) {

                    if (respuesta.status == "success") {
                        $("#categoria").val(respuesta.data.categoria);
                    } else {
                        alert("Error en la base de datos");
                    }

                }
            });

        });

        $(document).on('click', '.btn-warning', function() {

            $(this).closest('tr').remove();

        });

        $(document).on('click', '#agregarInventario', function() {

            if (ventanaProductosInventario) {
                ventanaProductosInventario.close();
                ventanaProductosInventario = null;
            }

            $.confirm({
                title: false,
                boxWidth: '600px',
                useBootstrap: false,
                content: `

            <div class="container-fluid py-3">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group pb-3">
                            <label for="nombreProductoAgregarInventario">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombreProductoAgregarInventario" placeholder="Ingrese el nombre del producto">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div id="bloque-categoria">
                            <label for="categoriaProductoAgregarInventario" style="margin-right: 10px;">Categoría</label>
                            <div class="form-group" style="display: flex; align-items: center;">
                                <select class="form-control" id="categoriaProductoAgregarInventario" style="margin-right: 10px;">
                                    <option value="">Seleccione una categoría</option>
                                </select>
                                <button class="btn btn-primary" type="button" id="agregarCategoria">Agregar</button>
                            </div>
                        </div>

                        <div id="bloque-AgregarCategoria" style="display: none;">
                            <label for="nuevaCategoria" style="margin-right: 10px;"><i class="bx bx-plus"></i> Agregar categoria</label>
                            <div class="form-group" style="display: flex; align-items: center;">
                                <input type="text" class="form-control" placeholder="Agregar nueva categoría" id="nuevaCategoria" style="margin-right: 10px;">
                                <button class="btn btn-primary" type="button" id="guardarCategoria">Guardar</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center mt-4">
                        <button type="button" class="btn btn-dark" id="guardarInventario">
                            Guardar
                        </button>
                    </div>
                </div>

            </div>

            `,
                buttons: false,
                onContentReady: function() {

                    $.ajax({
                        url: "http://127.0.0.1:8080/requisiciones/inventario/obtenerTodasCategorias/",
                        type: "GET",
                        success: function(respuesta) {

                            respuesta.data.forEach(function(elemento) {
                                $("#categoriaProductoAgregarInventario").append('<option value="' + elemento.id + '">' + elemento.categoria + '</option>');
                            });

                        }
                    });

                }
            });

        });

        $(document).on('click', '#guardarInventario', function() {

            let nombreProductoAgregarInventario = $("#nombreProductoAgregarInventario").val().trim();
            let categoriaProductoAgregarInventario = $("#categoriaProductoAgregarInventario").val();

            if (nombreProductoAgregarInventario == "") {
                alert("El nombre del producto esta vacio ..");
                return false;
            }

            if (categoriaProductoAgregarInventario == "") {
                alert("El nombre de la categoria esta vacio ..");
                return false;
            }

            let datosEnviar = JSON.stringify({
                nombreProductoAgregarInventario: nombreProductoAgregarInventario,
                categoriaProductoAgregarInventario: categoriaProductoAgregarInventario
            });

            $("#nombreProductoAgregarInventario").val("");
            $("#categoriaProductoAgregarInventario").val("");

            $.ajax({
                url: "http://127.0.0.1:8080/requisiciones/inventario/buscarProducto",
                type: "POST",
                dataType: "json",
                data: {
                    producto: nombreProductoAgregarInventario
                },
                success: function(respuesta) {

                    if (respuesta.status == "error") {
                        alert("El producto ya existe ...");
                    }

                }
            });

        });

        $(document).on('click', '#agregarCategoria', function() {

            $("#bloque-categoria").hide();
            $("#bloque-AgregarCategoria").show();

        });

        $(document).on('click', '#guardarCategoria', function() {

            $("#bloque-categoria").show();
            $("#bloque-AgregarCategoria").hide();
            $("#nuevaCategoria").val("");

            $("#categoriaProductoAgregarInventario").val("");

            /*
            $.ajax({
            url: "",
            type: "POST",
            dataType: "json",
            success: function(respuesta) {
            }
            });
            */

        });

        $(document).on('click', '.proovedores', function() {

            proovedores = $(this).data("proovedores");

            $.confirm({
                title: false,
                boxWidth: '850px',
                useBootstrap: false,
                content: `

                    <style>

                    tr:first-child td:first-child {
                        width: 100px;
                    }

                    .form-check-input:checked {
                        background-color: #66b0ff;
                        border-color: #66b0ff;
                        color: #b1bbc5;
                    }

                    .form-check-input:focus {
                        box-shadow: none;
                        border: 1px solid #b1bbc5;
                    }

                    .form-check-input:not(:focus) {
                        border: 1px solid #b1bbc5 !important;
                    }

                    .form-check-input:not(:focus):checked {
                        border: 1px solid #b1bbc5 !important;
                        box-shadow: none;
                    }

                    .checkbox-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        font-weight: 400;
                    }

                    </style>

                    <div style="text-align: center;">
                        <h6 style="margin-top: 5px;">Lista de Proveedores</h6>
                    </div>

                    <div id="loader" style="text-align: center;margin-top: 35px;">
                        <img src="http://127.0.0.1:8080/requisiciones/public/assets/img/llF5iyg.gif" width="30" height="30"/>
                         Cargando...
                    </div>

                    <table class="table" id="Proveedores">
                        <thead>
                            <tr>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Monto</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <small class="form-text text-muted">Seleccion nombre del proveedor en la tabla</small>

                `,
                buttons: false,
                onContentReady: function() {

                    setTimeout(function() {
                        $('#loader').show();

                        if ($.fn.DataTable.isDataTable('#Proveedores')) {
                            var table = $('#Proveedores').DataTable();
                            table.clear().draw();
                            table.destroy();
                        }

                        $('#Proveedores').DataTable({
                            "ajax": {
                                "url": "http://127.0.0.1/requisiciones/inventario/get_proveedores-inventario/" + proovedores,
                                "type": "GET",
                                "dataSrc": "data"
                            },
                            "columns": [{
                                    "data": "proveedor"
                                },
                                {
                                    "data": "precio",
                                    "render": function(data, type, row) {
                                        return '<input type="text" class="form-control precio-input" value="' + data + '" style="width: 300px;">';
                                    }
                                },
                                {
                                    "data": "chec",
                                    "render": function(data, type, row) {
                                        var checked = data == 1 ? "checked" : "";
                                        return '<div class="form-check text-center">' +
                                            '<input class="form-check-input checkbox-proveedor" type="checkbox" ' + checked + ' data-proveedor="' + row.id_proveedor + '">';
                                        '</div>';
                                    }
                                }
                            ],
                            "columnDefs": [{
                                    "width": "200px",
                                    "targets": 0
                                },
                                {
                                    "className": "text-center",
                                    "targets": 2
                                }
                            ],
                            "initComplete": function(settings, json) {
                                console.log("Datos recibidos:", json);
                                $('#loader').hide();
                            }
                        });

                    }, 100);

                }
            });

        });

        $(document).on('change', '.checkbox-proveedor', function() {

            proovedores
            let proveedor = $(this).data("proveedor");
            let isChecked = $(this).prop('checked');

            let data = {
                proovedores: proovedores,
                proveedor: proveedor,
                estado: isChecked ? 1 : 0
            };

            let jsonData = JSON.stringify(data);

            console.log(jsonData);

        });
    </script>
</body>

</html>