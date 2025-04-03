let base_url = window.env.API_URL;

$('#tabla_usuarios').DataTable({
    ajax: {
        url: base_url + 'usuarios/obtener-usuarios',
        type: 'POST',
        dataSrc: 'data'  // Se asegura de que los datos estén en la clave 'data'
    },
    columns: [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'username' },
        {
            data: null,
            render: function(data, type, row) {
                return '<button class="btn btn-primary">Ver</button>';
            }
        }
    ]
});
