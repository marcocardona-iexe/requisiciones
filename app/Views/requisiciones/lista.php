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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Usuarios /</span> Lista</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregar_usuario"><i class='bx bx-plus-circle'></i>Agregar usuario</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm" id="tbl_requisicones">
                                            <thead>
                                                <tr>
                                                    <th>No. Folio</th>
                                                    <th>Fecha de solicitud</th>
                                                    <th>Status</th>
                                                    <th>Justificación</th>
                                                    <th>Comentario de seguimiento</th>
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
            $('#tbl_requisicones').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'data-table', // Asegura que la URL es correcta
                    type: 'POST',
                    error: function(xhr, error, thrown) {
                        console.error('Error en DataTables AJAX:', xhr.responseText);
                    }
                },
                columns: [{
                        data: "id"
                    },
                    {
                        data: "created_at",
                        className: "dt-left"
                    },
                    {
                        data: null,
                        className: "dt-left",
                        render: function(data, type, row) {
                            console.log(data);
                            let labelstatus = "";
                            switch (data.id_estatus) {
                                case "1":
                                    labelstatus = `<span class="badge bg-warning text-dark">Nueva</span>`;
                                    break;
                                case "2":
                                    labelstatus = `<span class="badge bg-primary">Validado Parcial</span>`;
                                    break;
                                case "3":
                                    labelstatus = `<span class="badge bg-info text-dark">Autorizada</span>`;
                                    break;
                                case "4":
                                    labelstatus = `<span class="badge bg-success">Comprado</span>`;
                                    break;
                                case "5":
                                    labelstatus = `<span class="badge bg-danger">Cancelado</span>`;
                                    break;
                            }
                            return `${labelstatus}`;
                        },
                    },
                    {
                        data: "justificacion"
                    },
                    {
                        data: "comentario_estatus"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            switch (data.id_estatus) {
                                case "1":
                                    return `
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class='bx bxs-paste'></i> Acciones
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li class="ver_solicitud" data-id='${data.id}'><a class="dropdown-item" href="#" ><i class='bx bx-list-ul'></i> Ver solicitud</a></li>
                                                <li><a class="dropdown-item" href="#"><i class='bx bx-trash'></i> Cancelar solicitud</a></li>
                                            </ul>
                                        </div>`;
                                    break;
                                case "2":
                                    return `
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class='bx bxs-paste'></i> Acciones
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li><a class="dropdown-item" href="#"><i class='bx bx-list-ul'></i> Autorizar</a></li>
                                                <li><a class="dropdown-item" href="#"><i class='bx bx-trash'></i> Cancelar solicitud</a></li>
                                            </ul>
                                        </div>`;
                                    break;
                                case "3":
                                    return `<button type="button" class="btn btn-success btn-sm"><i class='bx bx-cart-alt' ></i> Comprar</button>
`;
                                    break;
                                case "4":
                                    return `<button type="button" class="btn btn-secondary btn-sm"><i class='bx bx-list-ul' ></i> Ver compra</button>`;
                                    break;
                                case "5":
                                    return `<button type="button" class="btn btn-secondary btn-sm"><i class='bx bx-list-ul' ></i> Ver detalle</button>`;
                                    break;
                            }
                        },
                    },
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
                },
            });


            $(document).on("click", ".ver_solicitud", function() {
                let id_requisicion = $(this).attr("data-id");

                $.ajax({
                    url: "obtener-detalle-requisicion/" + id_requisicion,
                    dataType: "json",
                    method: "POST",
                    success: function(response) {
                        if (response.status == 'success') {
                            let fila = '';
                            let items = []; // Array para almacenar los objetos
                            $.each(response.data, function(i, f) {
                                items.push({
                                    id_detalle: f.id_detalle,
                                    check: false
                                });

                                fila += `
                                    <tr class="click_fila" data-id="${f.id_detalle}">
                                        <td>${f.categoria}</td>
                                        <td>${f.detalle}</td>
                                        <td>${f.cantidad}</td>
                                        <td>${f.stock}</td>
                                        <td>
                                            <input type="checkbox" class="select-item" data-id="${f.id_detalle}">
                                        </td>
                                    </tr>`;
                            });
                            $.confirm({
                                title: null,
                                columnClass: "col-md-8 col-md-offset-2",
                                content: `
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Artículo</th>
                                                        <th>Descripción</th>
                                                        <th>Cant. solicitada</th>
                                                        <th>Stock</th>
                                                        <th>
                                                            Aprobar todas <input type="checkbox" id="select-all"> <label for="select-all"></label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>${fila}</tbody>

                                            </table>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="motivo">Motivo del rechazo:</label>
                                                <textarea id="motivo" class="form-control" rows="4" maxlength="100"></textarea>
                                                <small id="charCount">100 caracteres restantes</small>
                                                <div id="errorMessage" style="color: red; display: none;">El motivo debe ser llenado.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`,
                                type: "blue",
                                typeAnimated: true,
                                buttons: {
                                    validar: {
                                        text: "Validación Parcial",
                                        btnClass: "btn-info",
                                        action: function() {

                                        },
                                    },
                                    Cerrar: function() {},
                                },

                                onContentReady: function() {


                                    // Actualizar array cuando los checkboxes cambien manualmente
                                    modal.find(".select-item").on("change", function() {
                                        let id = $(this).data("id");
                                        let isChecked = $(this).prop("checked");

                                        let item = itemsSeleccionados.find(item => item.id_detalle == id);
                                        if (item) {
                                            item.check = isChecked;
                                        }
                                    });






                                    var $motivo = this.$content.find('#motivo');
                                    var $charCount = this.$content.find('#charCount');
                                    var $errorMessage = this.$content.find('#errorMessage');

                                    $motivo.on('input', function() {
                                        var remaining = 100 - $(this).val().length;
                                        $charCount.text(remaining + ' caracteres restantes');
                                        if (remaining <= 0) {
                                            $motivo.val($motivo.val().substring(0, 100)); // Limita a 100 caracteres
                                        }
                                    });
                                },
                            });
                        }
                    }
                });



            });


            function validarRequisicion(idRequisicion) {

                $.confirm({
                    title: null,
                    columnClass: "col-md-10 col-md-offset-1",
                    content: function() {
                        var self = this;
                        return $.ajax({
                                url: "https://sandbox.iexe.app/obtener-detalle-requisicion/" + idRequisicion,
                                dataType: "json",
                                method: "GET",
                            })
                            .done(function(response) {
                                console.log(response);

                                if ($.isArray(response)) {
                                    self.setContent(buildTableArticulos(response));
                                    let $tableContainer = $(self.$content).find(".container-table-articulos");
                                    $tableContainer.DataTable();
                                } else {
                                    self.setContent("Servicio no disponible, <br> Vuelve a intentarlo.");
                                }
                            })
                            .fail(function() {
                                self.setContent("Something went wrong.");
                            });
                    },
                    type: "blue",
                    typeAnimated: true,
                    buttons: {
                        validar: {
                            text: "Validación Parcial",
                            btnClass: "btn-info",
                            action: function() {

                                let productos = [];
                                let comentarios = $("#comentarios").val();
                                let verificarValidado = false;

                                $(".articulos-validar").each(function() {

                                    let validado = $(this).prop("checked") ? 1 : 0;
                                    productos.push({
                                        idDetalle: $(this).data("id"),
                                        validado: validado,
                                    });

                                    if (validado === 1) {
                                        verificarValidado = true;
                                    }

                                });

                                if (verificarValidado == false) {

                                    alert("Seleccione almenos un validado ...");
                                    return false;

                                }

                                if (comentarios == "") {

                                    alert("Escriba algo en el comentario ...");
                                    return false;

                                }

                                let data = {
                                    idRequisicion,
                                    productos: productos,
                                    comentarios: comentarios
                                };

                                let $btn = this.$$validar;
                                //$btn.html(`${spinnner} `).prop("disabled", true);


                                $("#letrero").html(`

                        <b style="line-height: 30px;margin-top: 8px;font-weight: 500;">Procesar validados</b>
                        <div class="spinner-border" role="status" style="width: 20px;height: 20px;">
                        </div>
                    
                    `);

                                console.log("Validando", data);
                                console.log("Validando");
                                sendValidarArticulos(data, this);

                                return false;
                            },
                        },
                        Cerrar: function() {},
                    },

                    onContentReady: function() {},
                });
            }



        });
    </script>
</body>

</html>