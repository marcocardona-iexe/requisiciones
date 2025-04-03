$(document).on("click", ".ver_compra_realizada", function () {
    let id_requisicion = $(this).attr("data-id");
    let base_url = window.env.API_URL;

    $.ajax({
        url: base_url + "requisiciones/get-compra/" + id_requisicion,
        dataType: "json",
        method: "POST",
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                let filas = "";
                let filas_ordenes = "";
                console.log("Ordenes de compra: ", response.data.ordenes);
                $.each(response.data.ordenes, function (i, o) {
                    console.log(o);
                    let elemento =
                        o.validado == 1
                            ? `<i class='bx bx-check-circle'></i>`
                            : `<input type="file" class="form-control form-control-sm input_xml" id="input_xml_${o.id}" style="width: auto;"><button class="btn btn-sm btn-info validar_xml" data-id=${o.id}><i class='bx bx-message-square-check'></i> Validar</button>`;
                    filas_ordenes += `
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="bx bxs-file-pdf orden_compra" data-id="${o.id}" style="cursor: pointer;"></i>${o.codigo} 
                               ${elemento}
                            </div>
                        </td>
                    </tr>`;
                });
                $.each(response.data.productos, function (i, p) {
                    filas += `
                    <tr>
                        <td>Nombre producto</td>
                        <td>Detalle del producto</td>
                        <td>Proveedor</td>
                        <td>Fecha de entrega</td>
                        <td>Cantidad</td>
                        <td>
                            <div class="input-group input-group-sm has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                <input type="text" class="form-control moneda" value=0.00 readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                <input type="text" class="form-control moneda" value=0.00 readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">$</span>
                                <input type="text" class="form-control moneda" value=0.00 readonly>
                            </div>
                        </td>
                    </tr>`;
                });

                $.confirm({
                    title: "Venta realizada",
                    columnClass: "col-md-12",
                    content: `
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="mensajeError"></div>
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
                            <div class="col-md-6 offset-md-3">
                                <table class="table  table-striped table-hover table-sm ">
                                    <thead>
                                        <tr>
                                            <th>Orden de compra</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ordenes_compra">
                                    ${filas_ordenes}
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3">
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
                            text: "Finalizar proceso",
                            btnClass: "btn-info",
                            action: function () {
                                console.log(items_venta);
                                // Limpiar mensajes anteriores
                                $("#mensajeError").html("");

                                // Obtener la fecha de compra
                                let fechaCompra = $("#fechaCompra").val();

                                // Verificar si la fecha es válida (no vacía)
                                if (!fechaCompra) {
                                    $("#mensajeError").html(`
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <strong>Error:</strong> Debes seleccionar una fecha de compra válida.
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                `);
                                    return false;
                                }

                                $.confirm({
                                    title: "Confirmación",
                                    content: "Estás a punto de realizar la venta. ¿Deseas continuar?",
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

                                                $.ajax({
                                                    url: "realizar-compra/" + id_requisicion,
                                                    method: "POST",
                                                    data: {
                                                        items: items_venta,
                                                        ordenes: ordenes_compra,
                                                        venta: venta,
                                                    },
                                                    dataType: "json",
                                                    success: function (response) {
                                                        loadingDialog.close(); // Cerrar el loading

                                                        if (response.status) {
                                                            // Mostrar mensaje de éxito
                                                            $.alert({
                                                                title: "Respuesta",
                                                                content: "Venta realizada correctamente.",
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
                        $(".validar_xml").on("click", function () {
                            let id_orden = $(this).attr("data-id");
                            let input_xml = $("#input_xml_" + id_orden)[0].files[0]; // Obtener el archivo

                            if (!input_xml) {
                                $.alert("❌ Selecciona un archivo XML.");
                                return;
                            }

                            if (
                                input_xml.type !== "text/xml" &&
                                input_xml.name.split(".").pop().toLowerCase() !== "xml"
                            ) {
                                $.alert("❌ El archivo debe ser un XML.");
                                return;
                            }

                            console.log("ID Orden: ", id_orden);
                            console.log("Archivo XML: ", input_xml.name);

                            let formData = new FormData();
                            formData.append("id_orden", id_orden);
                            formData.append("xml", input_xml); // Agregar archivo XML

                            $.ajax({
                                url: base_url + "orden-de-compra/validar-xml",
                                method: "POST",
                                data: formData,
                                processData: false, // Evita que jQuery transforme los datos
                                contentType: false, // Importante para subir archivos
                                dataType: "json",
                                success: function (response) {
                                    if (response.status === "success") {
                                        $.alert("✅ XML validado correctamente.");
                                    } else {
                                        $.alert("❌ " + response.mensaje);
                                    }
                                },
                                error: function () {
                                    $.alert("❌ Hubo un error en la validación del XML.");
                                },
                            });
                        });
                    },
                });
            }
        },
    });
});
