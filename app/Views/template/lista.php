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
    .btn-info {
        color: #fff; /* Color del texto */
        background-color: #3facd4; /* Azul */
        border-color: #3facd4; /* Borde azul */
        box-shadow: 0 0.125rem 0.25rem 0 rgba(0, 123, 255, 0.4); /* Sombra con tono azul */
    }

    .btn-info:hover, 
    .btn-info:not(:active):focus {
        background-color: #3890b0; /* Azul más tenue */
        border-color: #3890b0; /* Borde más tenue */
        box-shadow: 0 0.125rem 0.25rem 0 rgba(90, 156, 245, 0.4); /* Sombra más tenue */
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inventarios /productos</span></h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregarProducto"><i class="fa fa-plus"></i> Agregar producto</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm" id="tbl_inventario">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Categoria</th>
                                                    <th>Descripción</th>
                                                    <th>Stock</th>
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

        $("#tbl_inventario").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "https://sandbox.iexe.app/inventario-lista-ajax",
                type: "POST",
            },
            columns: [
                {data: "id"},
                {data: "nombre_general", className: "dt-left"},
                {data: "categoria", className: "dt-left"},
                {data: "caracteristicas", className: "dt-left"},
                {data: "stock_individual"},
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row) {
                        return `
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="height: 30px;line-height: 15px;">
                                Proveedores
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Asignaciones</a></li>
                            </ul>
                        </div>
                        `;
                    },
                },
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json",
            },
        });

        $("#agregarProducto").on("click", function(event) {

        $.confirm({
        title: false,
        boxWidth: '600px', // Personaliza el ancho
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
                position: absolute;
                top: 18%;
                left: 58%;
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
                        <input type="text" class="form-control" id="nombreProducto">
                        <small class="form-text text-muted">Escribe el nombre del producto en la casiilla de texto</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group  pb-4">
                        <label for="categoria">Categoría</label>
                        <select class="form-control" id="categoria">
                            <option value="">Seleccione una categoría</option>
                            <option value="Cómputo">Cómputo</option>
                            <option value="Mobiliario">Mobiliario</option>
                            <option value="Abarrotes">Abarrotes</option>
                            <option value="Electronica">Electronica</option>
                        </select>
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
                        <div class="sProductos">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        `,
        buttons: false
        });    

        });

        $(document).on("click", "#agregar", function() {

        let caracteristicasProducto = $("#caracteristicasProducto").val();
        let valorProducto = $("#valorProducto").val();

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

        $(".sProductos").html(`<div class="spinner"></div>`);

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

        console.log(datosEnviar);
        return;

        $.ajax({
            url: "procesar.php",
            type: "POST",
            contentType: "application/json",
            data: datosEnviar,
            success: function(respuesta) {

                $("#nombreProducto").val("");
                $("#categoria").val("");
                $("#caracteristicasProducto").val("");
                $("#valorProducto").val("");
                $("#tablaProductos").remove();

            },
            error: function(xhr, status, error) {
            }
        });

        });

    </script>
</body>

</html>
