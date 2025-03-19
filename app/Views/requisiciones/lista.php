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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Requisiciones /</span> Lista</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row justify-content-end align-items-center mt-3">
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
            console.log("API URL:", window.env.API_URL);
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
                                                <li class="cancelar_solicitud" data-id='${data.id}'><a class="dropdown-item" href="#"><i class='bx bx-trash'></i> Cancelar solicitud</a></li>
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
                                                <li class="autorizar" data-id='${data.id}'><a class="dropdown-item" href="#"><i class='bx bx-list-ul'></i> Autorizar</a></li>
                                                <li class="cancelar_solicitud" data-id='${data.id}'><a class="dropdown-item" href="#"><i class='bx bx-trash'></i> Cancelar solicitud</a></li>
                                            </ul>
                                        </div>`;
                                    break;
                                case "3":
                                    return `<button type="button" class="btn btn-success btn-sm realizar_compra" data-id='${data.id}'><i class='bx bx-cart-alt' ></i> Comprar</button>
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

            $(document).on("click", ".cancelar_solicitud", function() {
                let id_requisicion = $(this).attr("data-id");
                $.confirm({
                    type: 'red',
                    closeIcon: true,
                    animationBounce: 1.5, // default is 1.2 whereas 1 is no bounce.
                    title: 'Cancelar solicitud',
                    content: `
                            <form>
                                <div class="form-group">
                                    <label for="motivo">Motivo del rechazo:</label>
                                    <textarea id="motivo" class="form-control" rows="4" maxlength="100"></textarea>
                                    <small id="charCount">100 caracteres restantes</small>
                                    <div id="errorMessage" style="color: red; display: none;">El motivo debe ser llenado.</div>
                                </div>
                            </form>
                        `,
                    buttons: {
                        deleteUser: {
                            text: 'Rechazar confirmación',
                            action: function() {
                                var motivo = $('#motivo').val().trim();
                                if (motivo.length === 0) {
                                    $('#errorMessage').show(); // Mostrar mensaje de error
                                    return false; // Evitar que se cierre el modal
                                } else {
                                    $('#errorMessage').hide(); // Ocultar mensaje de error

                                    $.confirm({
                                        title: "Confirmación",
                                        content: "Estás a punto de rechazar la sollcitud, ¿Deseas continuar?",
                                        type: "orange",
                                        typeAnimated: true,
                                        buttons: {
                                            confirmar: {
                                                text: "Sí, continuar",
                                                btnClass: "btn-info",
                                                action: function() {
                                                    // Cerrar todos los modales abiertos antes de proceder
                                                    $(".jconfirm").remove();

                                                    // Mostrar modal de carga
                                                    let loadingDialog = $.dialog({
                                                        title: false,
                                                        content: `
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-center">
                                                                        <p><img src="http://127.0.0.1/requisiciones/public/assets/img/sistema/cargando.gif" style="width: 50%;"></p>
                                                                        <p>Procesando...</p>
                                                                        </div>
                                                                    </div>
                                                                </div>`,
                                                        closeIcon: false,
                                                        columnClass: "col-md-4"
                                                    });

                                                    // Simulación del AJAX (Reemplaza con tu URL real)
                                                    // Petición AJAX
                                                    $.ajax({
                                                        url: "rechazar/" + id_requisicion,

                                                        method: 'POST',
                                                        data: {
                                                            motivo: motivo
                                                        },
                                                        success: function(response) {


                                                            loadingDialog.close(); // Cerrar el loading

                                                            if (response.status) {
                                                                // Mostrar mensaje de éxito
                                                                $.alert({
                                                                    title: 'Respuesta',
                                                                    content: "Requisicion rechazada correctamente",
                                                                    type: 'green', // Tipo verde para éxito
                                                                    autoClose: 'cancelAction|8000', // Cerrar automáticamente después de 8 segundos
                                                                    buttons: {
                                                                        cancelAction: {
                                                                            text: 'Aceptar',
                                                                            action: function() {
                                                                                $('#tbl_requisicones').DataTable().ajax.reload(null, false);


                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            } else {
                                                                $.alert("Hubo un problema en la validación.");
                                                            }
                                                        },
                                                        error: function() {
                                                            $.alert('Hubo un error al procesar la solicitud.');
                                                        }
                                                    });
                                                }
                                            },
                                            cancelar: {
                                                text: "No",
                                                btnClass: "btn-danger"
                                            }
                                        }
                                    });





                                }
                            }
                        },
                        Cerrar: function() {
                            this.close(); // Cerrar el modal si se cancela
                        }
                    },
                    onContentReady: function() {
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
                    }
                });
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
                                    <tr class="click_fila" data-id="${f.id_detalle}" style="cursor:pointer;">
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
                                                <div id="alertMessage" class="alert alert-danger d-none"></div>
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
                                                    <label for="motivo">Comentario:</label>
                                                    <textarea id="comentario" class="form-control" rows="4" maxlength="100"></textarea>
                                                    <small id="charCount">100 caracteres restantes</small>
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
                                            let comentario = $("#comentario").val().trim();
                                            let aprobados = items.filter(item => item.check).length;
                                            let alertBox = $("#alertMessage");

                                            // Verificar si se cumple la validación
                                            if (!comentario) {
                                                alertBox.text("Debes ingresar un comentario sobre el seguimiento.").removeClass("d-none");
                                                return false; // Evita que se cierre el diálogo
                                            }

                                            if (aprobados === 0) {
                                                alertBox.text("Debes aprobar al menos un producto.").removeClass("d-none");
                                                return false;
                                            }



                                            $.confirm({
                                                title: "Confirmación",
                                                content: "Estás a punto de validar un producto. ¿Deseas continuar?",
                                                type: "orange",
                                                typeAnimated: true,
                                                buttons: {
                                                    confirmar: {
                                                        text: "Sí, continuar",
                                                        btnClass: "btn-info",
                                                        action: function() {
                                                            // Cerrar todos los modales abiertos antes de proceder
                                                            $(".jconfirm").remove();

                                                            // Mostrar modal de carga
                                                            let loadingDialog = $.dialog({
                                                                title: false,
                                                                content: `
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-center">
                                                                        <p><img src="http://127.0.0.1/requisiciones/public/assets/img/sistema/cargando.gif" style="width: 50%;"></p>
                                                                        <p>Procesando...</p>
                                                                        </div>
                                                                    </div>
                                                                </div>`,
                                                                closeIcon: false,
                                                                columnClass: "col-md-4"
                                                            });

                                                            // Simulación del AJAX (Reemplaza con tu URL real)
                                                            $.ajax({
                                                                url: "validar-parcialmente/" + id_requisicion,
                                                                method: "POST",
                                                                data: {
                                                                    comentario,
                                                                    items
                                                                },
                                                                dataType: "json",
                                                                success: function(response) {
                                                                    loadingDialog.close(); // Cerrar el loading

                                                                    if (response.status) {
                                                                        // Mostrar mensaje de éxito
                                                                        $.alert({
                                                                            title: 'Respuesta',
                                                                            content: "Productos validados correctamente.",
                                                                            type: 'green', // Tipo verde para éxito
                                                                            autoClose: 'cancelAction|8000', // Cerrar automáticamente después de 8 segundos
                                                                            buttons: {
                                                                                cancelAction: {
                                                                                    text: 'Aceptar',
                                                                                    action: function() {
                                                                                        $('#tbl_requisicones').DataTable().ajax.reload(null, false);


                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                    } else {
                                                                        $.alert("Hubo un problema en la validación.");
                                                                    }
                                                                },
                                                                error: function() {
                                                                    loadingDialog.close(); // Cerrar el loading
                                                                    $.alert("Hubo un error en el proceso.");
                                                                }
                                                            });
                                                        }
                                                    },
                                                    cancelar: {
                                                        text: "No",
                                                        btnClass: "btn-danger"
                                                    }
                                                }
                                            });

                                            return false; // Evita que se cierre el primer diálogo
                                        }
                                    },
                                    Cerrar: function() {}
                                },

                                onContentReady: function() {

                                    // Evento para manejar cambios manuales en los checkboxes individuales
                                    $(".select-item").on("change", function(event) {
                                        console.log(items);
                                        event.stopPropagation(); // Evita que el clic en el checkbox active el evento de la fila

                                        let id = $(this).data("id");
                                        let isChecked = $(this).prop("checked");

                                        // Buscar el item en el array y actualizar su estado
                                        let item = items.find(item => item.id_detalle == id);
                                        if (item) {
                                            item.check = isChecked;
                                        }

                                        console.log("Checkbox manualmente cambiado:", id, "Estado:", isChecked);

                                        // Verificar si todos los checkboxes están marcados para actualizar "Aprobar todas"
                                        let allChecked = $(".select-item").length === $(".select-item:checked").length;
                                        $("#select-all").prop("checked", allChecked);
                                    });

                                    // Evento para marcar/desmarcar el checkbox al hacer clic en la fila
                                    $(".click_fila").click(function(event) {
                                        console.log(items);
                                        // Verificar si el clic fue directamente en un checkbox para evitar conflicto
                                        if ($(event.target).is("input[type='checkbox']")) {
                                            return; // No ejecutar la función si el clic fue en el checkbox
                                        }

                                        let checkbox = $(this).find("td:last-child input[type='checkbox']");

                                        // Alternar el estado del checkbox
                                        let newState = !checkbox.prop("checked");
                                        checkbox.prop("checked", newState).trigger("change"); // Disparar el evento change manualmente

                                        // Actualizar el array items
                                        let id = checkbox.data("id");
                                        let item = items.find(item => item.id_detalle == id);
                                        if (item) {
                                            item.check = newState;
                                        }

                                        console.log("Checkbox cambiado desde la fila:", id, "Nuevo estado:", newState);
                                    });

                                    // Evento para seleccionar/deseleccionar todos los checkboxes con "Aprobar todas"
                                    $("#select-all").on("change", function() {
                                        console.log(items);
                                        let isChecked = $(this).prop("checked"); // Obtener estado del checkbox "Aprobar todas"

                                        // Marcar o desmarcar todos los checkboxes individuales y disparar el evento "change"
                                        $(".select-item").prop("checked", isChecked).trigger("change");

                                        // Actualizar todos los elementos del array items
                                        items.forEach(item => item.check = isChecked);

                                        console.log("Todos los checkboxes fueron", isChecked ? "seleccionados" : "deseleccionados");
                                    });


                                    var $comentario = this.$content.find('#comentario');
                                    var $charCount = this.$content.find('#charCount');
                                    var $errorMessage = this.$content.find('#errorMessage');

                                    $comentario.on('input', function() {
                                        var remaining = 100 - $(this).val().length;
                                        $charCount.text(remaining + ' caracteres restantes');
                                        if (remaining <= 0) {
                                            $comentario.val($comentario.val().substring(0, 100)); // Limita a 100 caracteres
                                        }
                                    });
                                },
                            });
                        }
                    }
                });



            });

            $(document).on("click", ".autorizar", function() {
                let id_requisicion = $(this).attr("data-id");

                $.ajax({
                    url: "obtener-detalle-requisicion-parcial/" + id_requisicion,
                    dataType: "json",
                    method: "POST",
                    success: function(response) {
                        if (response.status == 'success') {
                            let fila = '';
                            let items = []; // Array para almacenar los objetos
                            $.each(response.data, function(i, f) {
                                item_aprobado = (f.validado == "1"); // Convierte "1" en true, cualquier otro valor será false.

                                items.push({
                                    id_detalle: f.id_detalle,
                                    check: item_aprobado ? true : false
                                });

                                fila += `
                                    <tr class="click_fila" data-id="${f.id_detalle}" style="cursor:pointer;">
                                        <td>${f.categoria}</td>
                                        <td>${f.detalle}</td>
                                        <td>${f.cantidad}</td>
                                        <td>${f.stock}</td>
                                        <td>
                                            <input type="checkbox" ${item_aprobado ? "checked" : ""} class="select-item" data-id="${f.id_detalle}">
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
                                                <div id="alertMessage" class="alert alert-danger d-none"></div>
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
                                                <p><b>Comentario de seguimiento:</b> ${response.requisicion.comentario_estatus}</p>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="motivo">Comentario:</label>
                                                    <textarea id="comentario" class="form-control" rows="4" maxlength="100"></textarea>
                                                    <small id="charCount">100 caracteres restantes</small>
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
                                            let comentario = $("#comentario").val().trim();
                                            let aprobados = items.filter(item => item.check).length;
                                            let alertBox = $("#alertMessage");

                                            // Verificar si se cumple la validación
                                            if (!comentario) {
                                                alertBox.text("Debes ingresar un comentario sobre el seguimiento.").removeClass("d-none");
                                                return false; // Evita que se cierre el diálogo
                                            }

                                            if (aprobados === 0) {
                                                alertBox.text("Debes aprobar al menos un producto.").removeClass("d-none");
                                                return false;
                                            }



                                            $.confirm({
                                                title: "Confirmación",
                                                content: "Estás a punto de validar la compra. ¿Deseas continuar?",
                                                type: "orange",
                                                typeAnimated: true,
                                                buttons: {
                                                    confirmar: {
                                                        text: "Sí, continuar",
                                                        btnClass: "btn-info",
                                                        action: function() {
                                                            // Cerrar todos los modales abiertos antes de proceder
                                                            $(".jconfirm").remove();

                                                            // Mostrar modal de carga
                                                            let loadingDialog = $.dialog({
                                                                title: false,
                                                                content: `
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-center">
                                                                        <p><img src="http://127.0.0.1/requisiciones/public/assets/img/sistema/cargando.gif" style="width: 50%;"></p>
                                                                        <p>Procesando...</p>
                                                                        </div>
                                                                    </div>
                                                                </div>`,
                                                                closeIcon: false,
                                                                columnClass: "col-md-4"
                                                            });

                                                            // Simulación del AJAX (Reemplaza con tu URL real)
                                                            $.ajax({
                                                                url: "validar-compra/" + id_requisicion,
                                                                method: "POST",
                                                                data: {
                                                                    comentario,
                                                                    items
                                                                },
                                                                dataType: "json",
                                                                success: function(response) {
                                                                    loadingDialog.close(); // Cerrar el loading

                                                                    if (response.status) {
                                                                        // Mostrar mensaje de éxito
                                                                        $.alert({
                                                                            title: 'Respuesta',
                                                                            content: "Solicitud validada correctamente.",
                                                                            type: 'green', // Tipo verde para éxito
                                                                            autoClose: 'cancelAction|8000', // Cerrar automáticamente después de 8 segundos
                                                                            buttons: {
                                                                                cancelAction: {
                                                                                    text: 'Aceptar',
                                                                                    action: function() {
                                                                                        $('#tbl_requisicones').DataTable().ajax.reload(null, false);


                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                    } else {
                                                                        $.alert("Hubo un problema en la validación.");
                                                                    }
                                                                },
                                                                error: function() {
                                                                    loadingDialog.close(); // Cerrar el loading
                                                                    $.alert("Hubo un error en el proceso.");
                                                                }
                                                            });
                                                        }
                                                    },
                                                    cancelar: {
                                                        text: "No",
                                                        btnClass: "btn-danger"
                                                    }
                                                }
                                            });

                                            return false; // Evita que se cierre el primer diálogo
                                        }
                                    },
                                    Cerrar: function() {}
                                },

                                onContentReady: function() {

                                    // Evento para manejar cambios manuales en los checkboxes individuales
                                    $(".select-item").on("change", function(event) {
                                        console.log(items);
                                        event.stopPropagation(); // Evita que el clic en el checkbox active el evento de la fila

                                        let id = $(this).data("id");
                                        let isChecked = $(this).prop("checked");

                                        // Buscar el item en el array y actualizar su estado
                                        let item = items.find(item => item.id_detalle == id);
                                        if (item) {
                                            item.check = isChecked;
                                        }

                                        console.log("Checkbox manualmente cambiado:", id, "Estado:", isChecked);

                                        // Verificar si todos los checkboxes están marcados para actualizar "Aprobar todas"
                                        let allChecked = $(".select-item").length === $(".select-item:checked").length;
                                        $("#select-all").prop("checked", allChecked);
                                    });

                                    // Evento para marcar/desmarcar el checkbox al hacer clic en la fila
                                    $(".click_fila").click(function(event) {
                                        console.log(items);
                                        // Verificar si el clic fue directamente en un checkbox para evitar conflicto
                                        if ($(event.target).is("input[type='checkbox']")) {
                                            return; // No ejecutar la función si el clic fue en el checkbox
                                        }

                                        let checkbox = $(this).find("td:last-child input[type='checkbox']");

                                        // Alternar el estado del checkbox
                                        let newState = !checkbox.prop("checked");
                                        checkbox.prop("checked", newState).trigger("change"); // Disparar el evento change manualmente

                                        // Actualizar el array items
                                        let id = checkbox.data("id");
                                        let item = items.find(item => item.id_detalle == id);
                                        if (item) {
                                            item.check = newState;
                                        }

                                        console.log("Checkbox cambiado desde la fila:", id, "Nuevo estado:", newState);
                                    });

                                    // Evento para seleccionar/deseleccionar todos los checkboxes con "Aprobar todas"
                                    $("#select-all").on("change", function() {
                                        console.log(items);
                                        let isChecked = $(this).prop("checked"); // Obtener estado del checkbox "Aprobar todas"

                                        // Marcar o desmarcar todos los checkboxes individuales y disparar el evento "change"
                                        $(".select-item").prop("checked", isChecked).trigger("change");

                                        // Actualizar todos los elementos del array items
                                        items.forEach(item => item.check = isChecked);

                                        console.log("Todos los checkboxes fueron", isChecked ? "seleccionados" : "deseleccionados");
                                    });


                                    var $comentario = this.$content.find('#comentario');
                                    var $charCount = this.$content.find('#charCount');
                                    var $errorMessage = this.$content.find('#errorMessage');

                                    $comentario.on('input', function() {
                                        var remaining = 100 - $(this).val().length;
                                        $charCount.text(remaining + ' caracteres restantes');
                                        if (remaining <= 0) {
                                            $comentario.val($comentario.val().substring(0, 100)); // Limita a 100 caracteres
                                        }
                                    });
                                },
                            });
                        }
                    }
                });



            });



            $(document).on("click", ".realizar_compra", function() {
                let id_requisicion = $(this).attr("data-id");

                $.ajax({
                    url: "obtener-compra-requisicion/" + id_requisicion,
                    dataType: "json",
                    method: "POST",
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {



                            let filas = "";
                            let options = "";
                            let venta = {
                                orden_compra: '------',
                                fecha_compra: '-------',
                                iva_aplicado: false,
                                subtotal: 0,
                                descuento_total: 0,
                                iva: 0,
                                total: 0
                            };


                            let items_venta = []; // Array para almacenar los objetos
                            let ordenes_compra = {}; // Asegurar que la variable existe

                            $.each(response.data, function(i, p) {
                                options = '<option value=0>Seleccione un proveedor</option>';
                                $.each(p.proveedor, function(j, pp) {
                                    options += `<option value=${pp.id_proveedor} data-precio=${pp.precio}>${pp.proveedor}</option>`;
                                });
                                items_venta.push({
                                    id_detalle: p.id_detalle,
                                    id_provedor: null,
                                    fecha_entrega: null,
                                    cantidad: p.cantidad,
                                    pu: 0,
                                    du: 0,
                                    total: 0
                                });
                                filas += `
                                    <tr>
                                        <td>${p.categoria}</td>
                                        <td>${p.detalle}</td>
                                        <td>
                                            <select class="form-select form-select-sm select_prove" id="prove_${p.id_detalle}" data-id=${p.id_detalle} aria-label="Default select example">
                                                ${options}
                                            </select>
                                        </td>
                                        <td><input type="date" class="form-control" value=""></td>
                                        <td>${p.cantidad}</td>
                                        <td>
                                            <div class="input-group input-group-sm has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                <input type="text" class="form-control moneda precio_unitario" id="pu_${p.id_detalle}" data-id=${p.id_detalle} value=0.00 readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                <input type="text" class="form-control moneda descuento_unitario" id="du_${p.id_detalle}" data-id=${p.id_detalle} value=00.00 readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                <input type="text" class="form-control moneda total_producto" id="total_${p.id_detalle}" data-id=${p.id_detalle} value=0.00 readonly>
                                            </div>
                                        </td>
                                    </tr>`;
                            });

                            $.confirm({
                                title: null,
                                columnClass: "col-md-12",
                                content: `
                                    <div class="container">
                                        <div class="row">
                                            
                                            <div class="col-md-12">
                                                <h3>Realizar venta</h3>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" readonly class="form-control" id="ordenCompra" value="V-0001">
                                                            <label for="ordenCompra">Orden de la venta</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="date" class="form-control" id="fechaCompra">
                                                            <label for="fechaCompra">Fecha de la compra</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-flex align-items-center">
                                                        <div class="form-check d-flex align-items-center">
                                                            <input class="form-check-input me-2" type="checkbox" id="masIva">
                                                            <label class="form-check-label" for="masIva">Más IVA</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-striped table-hover table-sm ">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th>Descripción</th>
                                                            <th>Proveedor</th>
                                                            <th>Fecha entrega</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio unitario</th>
                                                            <th>Descuento unitario</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${filas}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 offset-md-4">
                                                <table class="table  table-striped table-hover table-sm ">
                                                    <thead>
                                                        <tr>
                                                            <th>Orden de compra</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="ordenes_compra">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-4">
                                                <table class="table  table-striped table-hover table-sm ">
                                                    <tr>
                                                        <td><strong>Subtotal</strong></td>
                                                        <td>
                                                            <div class="input-group input-group-sm has-validation">
                                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                                <input type="text" class="form-control" id="subtotal" readonly aria-describedby="inputGroupPrepend" required>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Descuento total</strong></td>
                                                        <td>
                                                            <div class="input-group input-group-sm has-validation">
                                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                                <input type="text" class="form-control" id="descuento_total" readonly aria-describedby="inputGroupPrepend" required>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>IVA 16.00%</strong></td>
                                                        <td>
                                                            <div class="input-group input-group-sm has-validation">
                                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                                <input type="text" class="form-control" id="iva" readonly aria-describedby="inputGroupPrepend" required>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td>
                                                            <div class="input-group input-group-sm has-validation">
                                                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                                                <input type="text" class="form-control" id="total_pagar" readonly aria-describedby="inputGroupPrepend" required>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                    </div>`,
                                type: "blue",
                                typeAnimated: true,
                                buttons: {
                                    validar: {
                                        text: "Realizar Venta",
                                        btnClass: "btn-info",
                                        action: function() {


                                            $.confirm({
                                                title: "Confirmación",
                                                content: "Estás a punto de realizar la venta. ¿Deseas continuar?",
                                                type: "orange",
                                                typeAnimated: true,
                                                buttons: {
                                                    confirmar: {
                                                        text: "Sí, continuar",
                                                        btnClass: "btn-info",
                                                        action: function() {
                                                            // Cerrar todos los modales abiertos antes de proceder
                                                            $(".jconfirm").remove();

                                                            // Mostrar modal de carga
                                                            let loadingDialog = $.dialog({
                                                                title: false,
                                                                content: `
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-center">
                                                                            <p><img src="http://127.0.0.1/requisiciones/public/assets/img/sistema/cargando.gif" style="width: 50%;"></p>
                                                                            <p>Procesando...</p>
                                                                        </div>
                                                                    </div>
                                                                </div>`,
                                                                closeIcon: false,
                                                                columnClass: "col-md-4"
                                                            });

                                                            // Simulación del AJAX (Reemplaza con tu URL real)
                                                            $.ajax({
                                                                url: "realizar-compra/" + id_requisicion,
                                                                method: "POST",
                                                                data: {},
                                                                dataType: "json",
                                                                success: function(response) {
                                                                    loadingDialog.close(); // Cerrar el loading

                                                                    if (response.status) {
                                                                        // Mostrar mensaje de éxito
                                                                        $.alert({
                                                                            title: 'Respuesta',
                                                                            content: "Venta realizada correctamente.",
                                                                            type: 'green', // Tipo verde para éxito
                                                                            autoClose: 'cancelAction|8000', // Cerrar automáticamente después de 8 segundos
                                                                            buttons: {
                                                                                cancelAction: {
                                                                                    text: 'Aceptar',
                                                                                    action: function() {
                                                                                        $('#tbl_requisicones').DataTable().ajax.reload(null, false);
                                                                                    }
                                                                                }
                                                                            }
                                                                        });
                                                                    } else {
                                                                        $.alert("Hubo un problema en la validación.");
                                                                    }
                                                                },
                                                                error: function() {
                                                                    loadingDialog.close(); // Cerrar el loading
                                                                    $.alert("Hubo un error en el proceso.");
                                                                }
                                                            });
                                                        }
                                                    },
                                                    cancelar: {
                                                        text: "No",
                                                        btnClass: "btn-danger"
                                                    }
                                                }
                                            });

                                            return false; // Evita que se cierre el primer diálogo
                                        }
                                    },
                                    Cerrar: function() {}
                                },

                                onContentReady: function() {

                                    let verificar_iva = (subtotal, descuento_total) => {
                                        if ($('#masIva').is(':checked')) {
                                            total = (subtotal * .16) + subtotal;
                                            iva = subtotal * .16;
                                            $("#iva").val(iva);
                                        } else {
                                            total = subtotal;
                                        }
                                        $("#total_pagar").val(total);
                                        return;
                                    }


                                    let calculos = (item, precio_u, descuento) => {
                                        let cantidad = parseFloat(item.cantidad).toFixed(2);
                                        let total_producto = (cantidad * precio_u) - (cantidad * descuento);
                                        $(`#total_${item.id_detalle}`).val(total_producto);
                                        let subtotal = 0;
                                        let descuento_total = 0;
                                        $('.total_producto').each(function(i, tp) {
                                            subtotal += parseFloat($(this).val()) || 0; // Asegura que se sume como número
                                        });

                                        $('.descuento_unitario').each(function(i, tp) {
                                            descuento_total += parseFloat($(this).val()) || 0; // Asegura que se sume como número
                                        });

                                        $("#subtotal").val(subtotal.toFixed(2)); // Mostrar el subtotal con dos decimales
                                        $("#descuento_total").val(descuento_total.toFixed(2)); // Mostrar el subtotal con dos decimales

                                        verificar_iva(subtotal, descuento_total);
                                        return;
                                    }

                                    $(".moneda").on("input", function() {
                                        let input = $(this)[0]; // Obtiene el input nativo
                                        let start = input.selectionStart; // Guarda la posición del cursor

                                        // Elimina caracteres no numéricos excepto el punto decimal
                                        let value = $(this).val().replace(/[^0-9.]/g, "");

                                        // Asegura que solo haya un punto decimal
                                        let parts = value.split(".");
                                        if (parts.length > 2) {
                                            value = parts[0] + "." + parts.slice(1).join(""); // Deja solo el primer punto
                                        }

                                        // Limita a solo dos decimales
                                        if (parts.length === 2 && parts[1].length > 2) {
                                            value = parts[0] + "." + parts[1].substring(0, 2); // Máximo 2 decimales
                                        }

                                        // Actualiza el input sin formato de pesos
                                        $(this).val(value);

                                        // Ajusta la posición del cursor
                                        input.setSelectionRange(start, start);
                                    });

                                    $(".moneda").on("blur", function() {
                                        let value = $(this).val().trim();

                                        if (value) {
                                            let parts = value.split(".");

                                            if (parts.length === 1) {
                                                value = parseFloat(value).toFixed(2); // Agrega ".00" si no tiene decimales
                                            } else if (parts.length === 2 && parts[1].length === 1) {
                                                value = parts[0] + "." + parts[1] + "0"; // Agrega "0" si solo hay un decimal
                                            }
                                        }

                                        $(this).val(value ? value : "0.00");
                                    });

                                    $(".select_prove").on("change", function() {
                                        let id_detalle = $(this).attr("data-id");
                                        let id_proveedor = $(this).val();
                                        let precio = $(this).find('option:selected').data('precio');
                                        let nombreProveedor = $(this).find("option:selected").text();
                                        let descuento = parseFloat($(`#du_${id_detalle}`).val()).toFixed(2);
                                        let precio_u = parseFloat(precio).toFixed(2);

                                        if (precio != 0) {
                                            $(`#pu_${id_detalle}`).val(precio).removeAttr('readonly');
                                            $(`#du_${id_detalle}`).removeAttr('readonly');
                                            const item = items_venta.find(item => item.id_detalle === id_detalle);
                                            if (item) {
                                                calculos(item, precio_u, descuento);
                                            }
                                        } else {
                                            $(`#pu_${id_detalle}`).val(valor_unitario).attr('readonly', true);
                                            $(`#du_${id_detalle}`).val(valor_unitario).attr('readonly', true);
                                        }

                                        if (id_proveedor !== "0") {
                                            agregarOrdenCompra(id_proveedor, nombreProveedor, id_detalle, 1, precio, descuento);
                                        } else {
                                            eliminarProductoDeOrden(id_detalle); // Elimina si el proveedor es "0"
                                        }
                                        console.log(ordenes_compra);
                                    });

                                    // Función para agregar productos a la orden de compra
                                    let agregarOrdenCompra = (idProveedor, nombreProveedor, idProducto, cantidad, precio, descuento) => {
                                        // Verificar si el idProducto ya existe en otro proveedor y eliminarlo
                                        Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                            let index = proveedor.productos.findIndex(p => p.idProducto === idProducto);
                                            if (index !== -1) {
                                                delete ordenes_compra[id]; // Eliminar el proveedor que contenía el producto
                                            }
                                        });

                                        // Si el proveedor no existe, lo creamos
                                        if (!ordenes_compra[idProveedor]) {
                                            ordenes_compra[idProveedor] = {
                                                nombre: nombreProveedor,
                                                productos: []
                                            };
                                        }

                                        // Agregar el nuevo producto al proveedor
                                        ordenes_compra[idProveedor].productos.push({
                                            idProducto: idProducto,
                                            cantidad: cantidad,
                                            precio: precio,
                                            descuento: descuento
                                        });

                                        actualizarTablaOrdenes();
                                    };

                                    // Función para eliminar un producto de la orden si el proveedor es "0"
                                    let eliminarProductoDeOrden = (idProducto) => {
                                        let proveedorAEliminar = null;

                                        // Buscar en qué proveedor está el producto y eliminarlo
                                        Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                            let index = proveedor.productos.findIndex(p => p.idProducto === idProducto);
                                            if (index !== -1) {
                                                proveedor.productos.splice(index, 1); // Eliminar el producto de la lista
                                                if (proveedor.productos.length === 0) {
                                                    proveedorAEliminar = id; // Marcar para eliminar si ya no tiene productos
                                                }
                                            }
                                        });

                                        // Si el proveedor ya no tiene productos, eliminarlo completamente
                                        if (proveedorAEliminar) {
                                            delete ordenes_compra[proveedorAEliminar];
                                        }

                                        actualizarTablaOrdenes();
                                    };

                                    // Función para actualizar la tabla de órdenes de compra
                                    let actualizarTablaOrdenes = () => {
                                        $("#ordenes_compra").empty();

                                        let filas = "";
                                        Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                            filas += `<tr  style="cursor:pointer;"><td>${proveedor.nombre} <i class='bx bxs-file-pdf click1' data-id="${id}"></i></td></tr>`;
                                        });

                                        $("#ordenes_compra").append(filas);
                                    };


                                    $(document).on("click", ".click1", function() {
                                        let idProveedor = $(this).attr("data-id");
                                        console.log(ordenes_compra[idProveedor]);
                                        $.ajax({
                                            url: "http://127.0.0.1:8080/requisiciones/orden-de-compra", // Reemplaza con la URL correcta en tu backend
                                            type: "POST",
                                            dataType: "json",
                                            data: {
                                                proveedor: ordenes_compra[idProveedor],
                                                id_proveedor: idProveedor
                                            },
                                            success: function(response) {
                                                alert("Orden de compra enviada correctamente.");
                                                console.log(response);
                                            },
                                            error: function(xhr, status, error) {
                                                console.error("Error al enviar la orden:", error);
                                            }
                                        });
                                    })

                                    let enviarOrdenCompra = (idProveedor) => {
                                        if (!ordenes_compra[idProveedor]) {
                                            alert("No hay datos para este proveedor.");
                                            return;
                                        }


                                    };


                                    $(".precio_unitario").on("blur", function() {
                                        let pu = $(this).val();
                                        let id_detalle = $(this).attr("data-id");
                                        let precio_u = parseFloat(pu).toFixed(2);
                                        let descuento = parseFloat($(`#du_${id_detalle}`).val()).toFixed(2);
                                        const item = items_venta.find(item => item.id_detalle === id_detalle);
                                        if (item) {
                                            calculos(item, precio_u, descuento);
                                        }
                                    });

                                    $(".descuento_unitario").on("blur", function() {
                                        let du = $(this).val();
                                        let id_detalle = $(this).attr("data-id");
                                        let precio_u = parseFloat($(`#pu_${id_detalle}`).val()).toFixed(2);
                                        let descuento = parseFloat(du).toFixed(2);
                                        const item = items_venta.find(item => item.id_detalle === id_detalle);
                                        if (item) {
                                            calculos(item, precio_u, descuento);
                                        }
                                    });


                                    $('#masIva').change(function() {
                                        let subtotal = parseFloat($("#subtotal").val()) || 0; // Asegura que sea número
                                        let iva = $(this).is(':checked') ? subtotal * 0.16 : 0; // Calcula el IVA si está marcado
                                        let totalPagar = subtotal + iva; // Suma subtotal + IVA

                                        $("#iva").val(iva.toFixed(2)); // Mostrar solo el IVA
                                        $("#total_pagar").val(totalPagar.toFixed(2)); // Mostrar total a pagar
                                    });

                                },
                            });
                        }
                    }
                });



            });




        });
    </script>
</body>

</html>