$("#tbl_requisicones").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "data-table", // Asegura que la URL es correcta
        type: "POST",
        error: function (xhr, error, thrown) {
            console.error("Error en DataTables AJAX:", xhr.responseText);
        },
    },
    columns: [
        {
            data: "id",
        },
        {
            data: "created_at",
            className: "dt-left",
        },
        {
            data: null,
            className: "dt-left",
            render: function (data, type, row) {
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
                        labelstatus = `<span class="badge bg-info">Autorizada</span>`;
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
            data: "justificacion",
        },
        {
            data: "comentario_estatus",
        },
        {
            data: null,
            render: function (data, type, row) {
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
                        return `<button type="button" class="btn btn-success btn-sm realizar_compra" data-id='${data.id}'><i class='bx bx-cart-alt' ></i> Comprar</button>`;
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
