<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?= base_url('public/assets/') ?>"
    data-template="vertical-menu-template-free">

<?= $head; ?>

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inventarios /</span> Productos</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregarProducto"><i class='bx bx-plus-circle'></i>Agregar usuario</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm" id="tbl_requisicon">
                                            <thead>
                                                <tr>
                                                    <th>ID Variante</th>
                                                    <th>Nombre General</th>
                                                    <th>Características</th>
                                                    <th>Categoría</th>
                                                    <th>Stock Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0 table-striped table-hover">

                                            </tbody>
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
            $('#tbl_requisicon').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'data-table',
                    type: 'POST',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('Error en DataTables AJAX:', xhr.responseText);
                    }
                },
                columns: [{
                        data: 'id_variante'
                    },
                    {
                        data: 'nombre_general'
                    },
                    {
                        data: 'caracteristicas'
                    },
                    {
                        data: 'categoria'
                    },
                    {
                        data: 'stock_total'
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-paste'></i> Acciones
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li class="ver_solicitud"><a class="dropdown-item" href="#" ><i class='bx bx-list-ul'></i> Proovedores</a></li>
                                        <li><a class="dropdown-item" href="#"><i class='bx bx-trash'></i> Asignaciones</a></li>
                                    </ul>
                                </div>
                            `;
                        }
                    }
                ]
            });
        });

        $("#agregarProducto").on("click", function(event) {

            $.confirm({
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

            </style>

            <div class="container-fluid" style="padding: 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group pb-3">
                            <label for="nombreProducto">Nombre del producto</label>
                            <select class="form-control" id="nombreProducto">
                                <option value="">Seleccione una categoría</option>
                            </select>
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
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="sProductos">
                </div>

            </div>
            `,
            buttons: false,
            onContentReady: function () {
                
                $.ajax({
                    url: "http://127.0.0.1/requisiciones/inventario/obtenerTipoInventario",
                    type: "GET",
                    success: function(articulosInventario) {

                        if(articulosInventario.status == "success"){

                            if (Array.isArray(articulosInventario.data)) {
                                articulosInventario.data.forEach(function(articulo) {
                                    $("#nombreProducto").append(`
                                        <option value="${articulo.id}">${articulo.nombre}</option>
                                    `);
                                });
                            }

                        }else{

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

                if(caracteristicasProducto.toLowerCase() == caracteristicas.toLowerCase()){
                    alert("El producto ya existe ...");
                    verificaProducto = true; 
                }

            });

            if(verificaProducto == true){
                return;
            }

            if(caracteristicasProducto != "" && valorProducto != ""){

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

            }else{
                alert("Error el campo caracteristicas o el campo valor esta vacio ...");
            }

        });

        $(document).on("click", "#guardar", function() {

            let nombreProducto = $("#nombreProducto").val();
            let categoria = $("#categoria").val();

            let datosProductos = [];

            $("#tablaProductos tr").each(function() {
                let caracteristicas = $(this).find("td:eq(0)").text().trim();
                let valor = $(this).find("td:eq(1)").text().trim();

                if (caracteristicas && valor) {
                    datosProductos.push({ caracteristicas: caracteristicas, valor: valor });
                }

            });

            let datosEnviar = JSON.stringify({
                nombre: nombreProducto,
                categoria: categoria,
                productos: datosProductos
            });

            if(nombreProducto == ""){
                alert("El nombre del producto esta vacio ..");
                return false;
            }

            if(categoria == ""){
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
                url: "http://127.0.0.1/requisiciones/inventario/guardar",
                type: "POST",
                data: datosEnviar,
                contentType: "application/json",
                success: function(respuesta) {

                    if(respuesta == true){
                        $(".sProductos").html(`
                            <div style="padding-top: 15px;position: relative;" class="RespuestaFinal">
                                <div style="padding-top: 4px;">Datos cargados correctamente</div>
                                <svg style="position: absolute; left: 208px; top: 63%; transform: translateY(-50%);" width="24" height="24" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 25 L20 40 L40 10" stroke="#4CAF50" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        `);
                        $(".RespuestaFinal").fadeOut(10000);
                    }else{
                        $(".sProductos").html(`<div style="padding-top: 15px;" class="RespuestaFinal">Los datos no fueron cargados correctamente ... </div>`);
                        $(".RespuestaFinal").fadeOut(10000);

                    }

                    $("#nombreProducto").val("");
                    $("#categoria").val("");
                    $("#caracteristicasProducto").val("");
                    $("#valorProducto").val("");
                    $("#tablaProductos").empty();

                }
            });

        });

        $(document).on("change", "#nombreProducto", function() {

            let idInventario = $(this).val();

            $.ajax({
                url: "http://127.0.0.1/requisiciones/inventario/obtenerCategoria/" + idInventario,
                type: "GET",
                success: function(respuesta) {
                    
                    if(respuesta.status == "success"){
                        $("#categoria").val(respuesta.data.categoria);
                    }else{
                        alert("Error en la base de datos");
                    }

                }
            });

        });

        $(document).on('click', '.btn-warning', function () {

            $(this).closest('tr').remove();

        });

    </script>
</body>

</html>
