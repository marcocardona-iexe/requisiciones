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
                                items.push({
                                    id_detalle: f.id_detalle,
                                    check: false
                                });
                                item_aprobado = (f.validado == "1"); // Convierte "1" en true, cualquier otro valor será false.

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
                                            <p>Comentario de seguimiento: ${response.requisicion.comentario_estatus}</p>
                                            </div>
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





        });
    </script>
</body>

</html>