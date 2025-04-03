$(document).on("click", ".realizar_compra", function () {
    let id_requisicion = $(this).attr("data-id");
    $.ajax({
        url: "obtener-compra-requisicion/" + id_requisicion,
        dataType: "json",
        method: "POST",
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                let filas = "";
                let options = "";
                let venta = {
                    fecha_compra: "-------",
                    iva_aplicado: false,
                    subtotal: 0,
                    descuento_total: 0,
                    iva: 0,
                    total: 0,
                };

                let items_venta = []; // Array para almacenar los objetos
                let ordenes_compra = {}; // Asegurar que la variable existe

                $.each(response.data, function (i, p) {
                    options = "<option value=0>Seleccione un proveedor</option>";
                    $.each(p.proveedor, function (j, pp) {
                        options += `<option value=${pp.id_proveedor} data-precio=${pp.precio}>${pp.proveedor}</option>`;
                    });
                    items_venta.push({
                        id_detalle: p.id_detalle,
                        id_provedor: null,
                        fecha_entrega: null,
                        cantidad: p.cantidad,
                        pu: 0,
                        du: 0,
                        total: 0,
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
                        <td><input type="date" class="form-control form-control-sm fecha_entrega" value="" id="fecha_entrega_${p.id_detalle}" data-id=${p.id_detalle} readonly></td>
                        <td id="cantidad_${p.id_detalle}" data-cantidad=${p.cantidad}>${p.cantidad}</td>
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
                                <div id="mensajeError"></div>
                            </div>
                            
                            
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

                                // Verificar que todos los selects de proveedores tengan un valor diferente de 0
                                let selectsInvalidos = $(".select_prove").filter(function () {
                                    return $(this).val() === "0"; // Filtrar los selects con valor "0"
                                });

                                if (selectsInvalidos.length > 0) {
                                    $("#mensajeError").html(`
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <strong>Error:</strong> Todos los proveedores deben tener un valor válido.
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                `);
                                    return false;
                                }

                                // Verificar que todas las fechas de entrega sean válidas
                                let fechasEntregaInvalidas = $(".fecha_entrega").filter(function () {
                                    return !$(this).val(); // Si el campo está vacío, es inválido
                                });

                                if (fechasEntregaInvalidas.length > 0) {
                                    $("#mensajeError").html(`
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <strong>Error:</strong> Todas las <b>fechas de entrega</b> deben estar seleccionadas.
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

                                                // Simulación del AJAX (Reemplaza con tu URL real)
                                                console.log("Confirmamos la venta");
                                                console.log(items_venta);
                                                console.log(ordenes_compra);
                                                console.log(venta);
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
                        let verificar_iva = (subtotal, descuento_total) => {
                            var iva = 0;
                            if ($("#masIva").is(":checked")) {
                                total = subtotal * 0.16 + subtotal;
                                iva = subtotal * 0.16;
                                $("#iva").val(iva);
                            } else {
                                total = subtotal;
                            }

                            $("#total_pagar").val(total);

                            venta.subtotal = subtotal;
                            venta.descuento_total = descuento_total;
                            venta.total = total;
                            venta.iva = iva;

                            return;
                        };

                        let calculos = (item, precio_u, descuento) => {
                            console.log("Calculando para el item:");
                            console.log(item);

                            let cantidad = parseFloat(item.cantidad).toFixed(2);
                            let total_producto = cantidad * precio_u - cantidad * descuento;

                            // Actualizar los valores en el DOM
                            $(`#total_${item.id_detalle}`).val(total_producto);

                            // Actualizar los valores en el objeto item

                            item.total = total_producto; // Total del producto

                            let subtotal = 0;
                            let descuento_total = 0;

                            $(".total_producto").each(function (i, tp) {
                                subtotal += parseFloat($(this).val()) || 0; // Asegura que se sume como número
                            });

                            $(".descuento_unitario").each(function (i, tp) {
                                descuento_total += parseFloat($(this).val()) || 0; // Asegura que se sume como número
                            });

                            // Actualizar los valores en el DOM
                            $("#subtotal").val(subtotal.toFixed(2)); // Mostrar el subtotal con dos decimales
                            $("#descuento_total").val(descuento_total.toFixed(2)); // Mostrar el descuento total con dos decimales

                            // Llamar a la función verificar_iva
                            verificar_iva(subtotal, descuento_total);

                            return;
                        };

                        $(".moneda").on("input", function () {
                            let input = $(this)[0]; // Obtiene el input nativo
                            let start = input.selectionStart; // Guarda la posición del cursor

                            // Elimina caracteres no numéricos excepto el punto decimal
                            let value = $(this)
                            .val()
                            .replace(/[^0-9.]/g, "");

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

                        $(".moneda").on("blur", function () {
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

                        $(".select_prove").on("change", function () {
                            let id_detalle = $(this).attr("data-id");
                            let id_proveedor = $(this).val();
                            let precio = $(this).find("option:selected").data("precio");
                            let nombreProveedor = $(this).find("option:selected").text();
                            let descuento = parseFloat($(`#du_${id_detalle}`).val()).toFixed(2);
                            let precio_u = parseFloat(precio).toFixed(2);
                            console.log(items_venta);
                            if (precio != 0) {
                                $(`#pu_${id_detalle}`).val(precio).removeAttr("readonly");
                                $(`#du_${id_detalle}`).removeAttr("readonly");
                                $(`#fecha_entrega_${id_detalle}`).removeAttr("readonly");

                                const item = items_venta.find((item) => item.id_detalle === id_detalle);
                                if (item) {
                                    item.id_provedor = id_proveedor; // Total del producto
                                    item.fecha_entrega = $(`#fecha_entrega_${id_detalle}`).val();
                                    item.pu = precio_u; // Precio unitario
                                    item.du = descuento; // Descuento unitario
                                    calculos(item, precio_u, descuento);
                                }
                            } else {
                                $(`#pu_${id_detalle}`).val(valor_unitario).attr("readonly", true);
                                $(`#du_${id_detalle}`).val(valor_unitario).attr("readonly", true);
                            }
                            console.log(items_venta);
                            if (id_proveedor !== "0") {
                                let total = parseFloat($(`#total_${id_detalle}`).val()).toFixed(2);
                                let cantidad = $("#cantidad_" + id_detalle).attr("data-cantidad");
                                agregarOrdenCompra(
                                    id_proveedor,
                                    nombreProveedor,
                                    id_detalle,
                                    cantidad,
                                    precio,
                                    descuento,
                                    total,
                                    (fecha_entrega = $(`#fecha_entrega_${id_detalle}`).val())
                                );
                            } else {
                                console.log("Eliminando producto de la orden de compra");
                                eliminarProductoDeOrden(id_detalle); // Elimina si el proveedor es "0"
                                $(`#pu_${id_detalle}`).val("0.00").attr("readonly", true);
                                $(`#du_${id_detalle}`).val("0.00").attr("readonly", true);
                                $(`#total_${id_detalle}`).val("0.00").attr("readonly", true);
                                $(`#fecha_entrega_${id_detalle}`).attr("readonly", true);
                            }
                            console.log(ordenes_compra);
                        });

                        // Función para agregar productos a la orden de compra
                        let agregarOrdenCompra = (
                            idProveedor,
                            nombreProveedor,
                            idProducto,
                            cantidad,
                            precio,
                            descuento,
                            total,
                            fecha_entrega
                        ) => {
                            // Verificar si el idProducto ya existe en otro proveedor y eliminarlo
                            Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                let index = proveedor.productos.findIndex((p) => p.idProducto === idProducto);
                                if (index !== -1) {
                                    delete ordenes_compra[id]; // Eliminar el proveedor que contenía el producto
                                }
                            });

                            // Si el proveedor no existe, lo creamos
                            // Verificar el estado del checkbox
                            let iva = $("#masIva").is(":checked") ? 1 : 0;

                            if (!ordenes_compra[idProveedor]) {
                                iva = 0;
                                if ($("#masIva").is(":checked")) {
                                    iva = 1;
                                }

                                ordenes_compra[idProveedor] = {
                                    nombre: nombreProveedor,
                                    iva: iva,
                                    productos: [],
                                };
                            }

                            // Agregar el nuevo producto al proveedor
                            ordenes_compra[idProveedor].productos.push({
                                idProducto: idProducto,
                                cantidad: cantidad,
                                precio: precio,
                                descuento: descuento,
                                total: total,
                                fecha_entrega: fecha_entrega,
                            });

                            actualizarTablaOrdenes();
                        };

                        // Función para eliminar un producto de la orden si el proveedor es "0"
                        let eliminarProductoDeOrden = (idProducto) => {
                            let proveedorAEliminar = null;

                            // Buscar en qué proveedor está el producto y eliminarlo
                            Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                let index = proveedor.productos.findIndex((p) => p.idProducto === idProducto);
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

                        $(document).on("change", "#masIva", function () {
                            let nuevoIva = $(this).is(":checked") ? 1 : 0;
                            console.log("IVA cambiado a:", nuevoIva);

                            // Actualizar el atributo iva en todas las órdenes de compra
                            Object.keys(ordenes_compra).forEach((idProveedor) => {
                                ordenes_compra[idProveedor].iva = nuevoIva;
                            });
                            console.log(ordenes_compra);

                            actualizarTablaOrdenes(); // Refrescar la tabla con los nuevos valores
                        });

                        // Función para actualizar la tabla de órdenes de compra
                        let actualizarTablaOrdenes = () => {
                            $("#ordenes_compra").empty();

                            let filas = "";
                            Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                filas += `<tr  style="cursor:pointer;"><td>${proveedor.nombre} <i class='bx bxs-file-pdf orden_compra' data-id="${id}"></i></td></tr>`;
                            });

                            $("#ordenes_compra").append(filas);
                        };

                        $(document)
                        .off("click", ".orden_compra")
                        .on("click", ".orden_compra", function () {
                            console.log("asdasdsa");
                            let idProveedor = $(this).attr("data-id");
                            console.log(idProveedor);
                            console.log(ordenes_compra);

                            $.ajax({
                                url: "http://127.0.0.1/requisiciones/orden-de-compra/imprimir-previo",
                                type: "POST",
                                data: {
                                    proveedor: ordenes_compra[idProveedor],
                                    id_proveedor: idProveedor,
                                },
                                xhrFields: {
                                    responseType: "blob", // ⚠️ Esto evita que jQuery intente interpretar el PDF como JSON
                                },
                                success: function (response) {
                                    var blob = new Blob([response], {
                                        type: "application/pdf",
                                    });
                                    var url = URL.createObjectURL(blob);
                                    window.open(url, "_blank"); // Abre el PDF en una nueva pestaña
                                },
                                error: function (xhr, status, error) {
                                    console.error("Error al generar el PDF:", error);
                                },
                            });
                        });

                        $(".precio_unitario").on("blur", function () {
                            let pu = $(this).val();
                            let id_detalle = $(this).attr("data-id");
                            let precio_u = parseFloat(pu).toFixed(2);
                            let descuento = parseFloat($(`#du_${id_detalle}`).val()).toFixed(2);
                            const item = items_venta.find((item) => item.id_detalle === id_detalle);
                            if (item) {
                                item.pu = precio_u; // Precio unitario
                                item.du = descuento; // Descuento unitario
                                calculos(item, precio_u, descuento);
                                console.log(ordenes_compra);

                                // Buscar en qué proveedor está el producto y eliminarlo
                                // Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                //     let index = proveedor.productos.findIndex((p) => p.idProducto === id_detalle);
                                //     if (index !== -1) {
                                //         let total_producto = item.cantidad * precio_u - item.cantidad * descuento;
                                //         console.log(total_producto);
                                //         console.log("*************");
                                //         proveedor.productos[index].total = total_producto;
                                //         console.log(proveedor.productos[index]);
                                //         proveedor.productos[index].precio = precio_u; // Reemplaza NUEVO_PRECIO con el valor deseado
                                //         console.log(`Precio actualizado para idProducto ${id_detalle}`);
                                //         console.log(proveedor.productos[index]); // Muestra el producto actualizado
                                //     }
                                // });
                                // console.log(ordenes_compra);

                                let id_proveedor = $("#prove_" + id_detalle).val();
                                ordenes_compra[id_proveedor].productos.forEach((producto) => {
                                    if (producto.idProducto === id_detalle) {
                                        producto.total = parseFloat($(`#total_${id_detalle}`).val()).toFixed(2);

                                        producto.precio = precio_u;
                                    }
                                });
                                console.log("-----------");
                                console.log(ordenes_compra);
                            }
                        });

                        $(".descuento_unitario").on("blur", function () {
                            let du = $(this).val();
                            let id_detalle = $(this).attr("data-id");
                            let precio_u = parseFloat($(`#pu_${id_detalle}`).val()).toFixed(2);
                            let descuento = parseFloat(du).toFixed(2);
                            const item = items_venta.find((item) => item.id_detalle === id_detalle);
                            if (item) {
                                item.pu = precio_u; // Precio unitario
                                item.du = descuento; // Descuento unitario
                                calculos(item, precio_u, descuento);
                                console.log("**********");
                                console.log(ordenes_compra);
                                // Buscar en qué proveedor está el producto y eliminarlo
                                // Object.entries(ordenes_compra).forEach(([id, proveedor]) => {
                                //     let index = proveedor.productos.findIndex((p) => p.idProducto === id_detalle);
                                //     if (index !== -1) {
                                //         let total_producto = item.cantidad * precio_u - item.cantidad * descuento;
                                //         proveedor.productos[index].total = total_producto;
                                //         proveedor.productos[index].descuento = descuento; // Reemplaza NUEVO_PRECIO con el valor deseado
                                //         console.log(`Precio actualizado para idProducto ${id_detalle}`);
                                //         console.log(proveedor.productos[index]); // Muestra el producto actualizado
                                //     }
                                // });

                                let id_proveedor = $("#prove_" + id_detalle).val();
                                ordenes_compra[id_proveedor].productos.forEach((producto) => {
                                    if (producto.idProducto === id_detalle) {
                                        producto.total = parseFloat($(`#total_${id_detalle}`).val()).toFixed(2);
                                        producto.descuento = descuento;
                                    }
                                });
                                console.log("-----------");
                                console.log(ordenes_compra);
                            }
                        });

                        $(".fecha_entrega").on("blur", function () {
                            let id_detalle = $(this).attr("data-id");
                            let fecha_entrega = $(this).val();
                            const item = items_venta.find((item) => item.id_detalle === id_detalle);
                            if (item) {
                                item.fecha_entrega = fecha_entrega; // Fecha de entrega
                                let id_proveedor = $("#prove_" + id_detalle).val();
                                ordenes_compra[id_proveedor].productos.forEach((producto) => {
                                    if (producto.idProducto === id_detalle) {
                                        producto.fecha_entrega = fecha_entrega;
                                    }
                                });

                                Object.keys(ordenes_compra).forEach((id_proveedor) => {
                                    ordenes_compra[id_proveedor].fecha = fecha_entrega;
                                });
                                console.log(ordenes_compra);

                                actualizarTablaOrdenes(); // Refrescar la tabla con los nuevos valores
                            }
                        });

                        $("#masIva").change(function () {
                            let subtotal = parseFloat($("#subtotal").val()) || 0; // Asegura que sea número
                            let iva = $(this).is(":checked") ? subtotal * 0.16 : 0; // Calcula el IVA si está marcado
                            let totalPagar = subtotal + iva; // Suma subtotal + IVA
                            $("#iva").val(iva.toFixed(2)); // Mostrar solo el IVA
                            $("#total_pagar").val(totalPagar.toFixed(2)); // Mostrar total a pagar
                            venta.iva_aplicado = $(this).is(":checked") ? true : false;
                        });

                        $("#fechaCompra").on("blur", function () {
                            venta.fecha_compra = $(this).val();
                        });
                    },
                });
            }
        },
    });
});
