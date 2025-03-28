$(document).on("click", ".cancelar_solicitud", function () {
    let id_requisicion = $(this).attr("data-id");
    $.confirm({
        type: "red",
        closeIcon: true,
        animationBounce: 1.5, // default is 1.2 whereas 1 is no bounce.
        title: "Cancelar solicitud",
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
                text: "Rechazar confirmación",
                action: function () {
                    var motivo = $("#motivo").val().trim();
                    if (motivo.length === 0) {
                        $("#errorMessage").show(); // Mostrar mensaje de error
                        return false; // Evitar que se cierre el modal
                    } else {
                        $("#errorMessage").hide(); // Ocultar mensaje de error

                        $.confirm({
                            title: "Confirmación",
                            content: "Estás a punto de rechazar la sollcitud, ¿Deseas continuar?",
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
                                        // Petición AJAX
                                        $.ajax({
                                            url: "rechazar/" + id_requisicion,

                                            method: "POST",
                                            data: {
                                                motivo: motivo,
                                            },
                                            success: function (response) {
                                                loadingDialog.close(); // Cerrar el loading

                                                if (response.status) {
                                                    // Mostrar mensaje de éxito
                                                    $.alert({
                                                        title: "Respuesta",
                                                        content: "Requisicion rechazada correctamente",
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
                                                $.alert("Hubo un error al procesar la solicitud.");
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
                    }
                },
            },
            Cerrar: function () {
                this.close(); // Cerrar el modal si se cancela
            },
        },
        onContentReady: function () {
            var $motivo = this.$content.find("#motivo");
            var $charCount = this.$content.find("#charCount");
            var $errorMessage = this.$content.find("#errorMessage");

            $motivo.on("input", function () {
                var remaining = 100 - $(this).val().length;
                $charCount.text(remaining + " caracteres restantes");
                if (remaining <= 0) {
                    $motivo.val($motivo.val().substring(0, 100)); // Limita a 100 caracteres
                }
            });
        },
    });
});
