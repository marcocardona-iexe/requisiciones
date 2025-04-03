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
        { 
          data: 'username',
          width: '700px' 
        },
        {
            data: null,
            render: function(data, type, row) {
                return '<button class="btn btn-primary"><i class="bx bx-show"></i> Ver</button>';
            }
        }
    ],
    columnDefs: [
        {
            targets: 0, // Aplica la regla a la primera columna (índice 0)
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).css('text-align', 'left');
            }
        }
    ]
});
