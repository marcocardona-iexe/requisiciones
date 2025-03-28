$(document).on("click", ".ver_solicitud", function () {
    let id_requisicion = $(this).attr("data-id");

    $.ajax({
        url: "obtener-detalle-requisicion/" + id_requisicion,
        dataType: "json",
        method: "POST",
        success: function (response) {
            if (response.status == "success") {
                let fila = "";
                let items = []; // Array para almacenar los objetos
                $.each(response.data, function (i, f) {
                    items.push({
                        id_detalle: f.id_detalle,
                        check: false,
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
                            action: function () {
                                let comentario = $("#comentario").val().trim();
                                let aprobados = items.filter((item) => item.check).length;
                                let alertBox = $("#alertMessage");

                                // Verificar si se cumple la validación
                                if (!comentario) {
                                    alertBox
                                    .text("Debes ingresar un comentario sobre el seguimiento.")
                                    .removeClass("d-none");
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
                                            action: function () {
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
                                                    columnClass: "col-md-4",
                                                });

                                                // Simulación del AJAX (Reemplaza con tu URL real)
                                                $.ajax({
                                                    url: "validar-parcialmente/" + id_requisicion,
                                                    method: "POST",
                                                    data: {
                                                        comentario,
                                                        items,
                                                    },
                                                    dataType: "json",
                                                    success: function (response) {
                                                        loadingDialog.close(); // Cerrar el loading

                                                        if (response.status) {
                                                            // Mostrar mensaje de éxito
                                                            $.alert({
                                                                title: "Respuesta",
                                                                content: "Productos validados correctamente.",
                                                                type: "green", // Tipo verde para éxito
                                                                autoClose: "cancelAction|8000", // Cerrar automáticamente después de 8 segundos
                                                                buttons: {
                                                                    cancelAction: {
                                                                        text: "Aceptar",
                                                                        action: function () {
                                                                            $("#tbl_requisicones")
                                                                            .DataTable()
                                                                            .ajax.reload(null, false);
                                                                        },
                                                                    },
                                                                },
                                                            });
                                                        } else {
                                                            $.alert("Hubo un problema en la validación.");
                                                        }
                                                    },
                                                    error: function () {
                                                        loadingDialog.close(); // Cerrar el loading
                                                        $.alert("Hubo un error en el proceso.");
                                                    },
                                                });
                                            },
                                        },
                                        cancelar: {
                                            text: "No",
                                            btnClass: "btn-danger",
                                        },
                                    },
                                });

                                return false; // Evita que se cierre el primer diálogo
                            },
                        },
                        Cerrar: function () {},
                    },

                    onContentReady: function () {
                        // Evento para manejar cambios manuales en los checkboxes individuales
                        $(".select-item").on("change", function (event) {
                            console.log(items);
                            event.stopPropagation(); // Evita que el clic en el checkbox active el evento de la fila

                            let id = $(this).data("id");
                            let isChecked = $(this).prop("checked");

                            // Buscar el item en el array y actualizar su estado
                            let item = items.find((item) => item.id_detalle == id);
                            if (item) {
                                item.check = isChecked;
                            }

                            console.log("Checkbox manualmente cambiado:", id, "Estado:", isChecked);

                            // Verificar si todos los checkboxes están marcados para actualizar "Aprobar todas"
                            let allChecked = $(".select-item").length === $(".select-item:checked").length;
                            $("#select-all").prop("checked", allChecked);
                        });

                        // Evento para marcar/desmarcar el checkbox al hacer clic en la fila
                        $(".click_fila").click(function (event) {
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
                            let item = items.find((item) => item.id_detalle == id);
                            if (item) {
                                item.check = newState;
                            }

                            console.log("Checkbox cambiado desde la fila:", id, "Nuevo estado:", newState);
                        });

                        // Evento para seleccionar/deseleccionar todos los checkboxes con "Aprobar todas"
                        $("#select-all").on("change", function () {
                            console.log(items);
                            let isChecked = $(this).prop("checked"); // Obtener estado del checkbox "Aprobar todas"

                            // Marcar o desmarcar todos los checkboxes individuales y disparar el evento "change"
                            $(".select-item").prop("checked", isChecked).trigger("change");

                            // Actualizar todos los elementos del array items
                            items.forEach((item) => (item.check = isChecked));

                            console.log("Todos los checkboxes fueron", isChecked ? "seleccionados" : "deseleccionados");
                        });

                        var $comentario = this.$content.find("#comentario");
                        var $charCount = this.$content.find("#charCount");
                        var $errorMessage = this.$content.find("#errorMessage");

                        $comentario.on("input", function () {
                            var remaining = 100 - $(this).val().length;
                            $charCount.text(remaining + " caracteres restantes");
                            if (remaining <= 0) {
                                $comentario.val($comentario.val().substring(0, 100)); // Limita a 100 caracteres
                            }
                        });
                    },
                });
            }
        },
    });
});
