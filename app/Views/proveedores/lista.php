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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Proveedores /</span> Lista</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">

                            <div class="container">
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregar_proveedor"><i class='bx bx-plus-circle'></i>Agregar proveedor</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm" id="tabla_proveedores">
                                            <thead>
                                                <tr>
                                                    <th>Proveedor</th>
                                                    <th>RFC</th>
                                                    <th>Telefono</th>
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

        const testData = [
            { proveedor: 'Proveedor A', rfc: 'RFC12345', telefono: '123-456-7890' },
            { proveedor: 'Proveedor B', rfc: 'RFC23456', telefono: '234-567-8901' },
            { proveedor: 'Proveedor C', rfc: 'RFC34567', telefono: '345-678-9012' },
            { proveedor: 'Proveedor D', rfc: 'RFC45678', telefono: '456-789-0123' },
            { proveedor: 'Proveedor E', rfc: 'RFC56789', telefono: '567-890-1234' },
        ];

        $('#tabla_proveedores').DataTable({
            data: testData,
            columns: [
                { data: 'proveedor' },
                { data: 'rfc' },
                { data: 'telefono' },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <button type="button" class="btn btn-outline-dark btn-sm" id="editar">
                            <i class="bx bx-list-ul"></i> Editar
                        </button>
                        `;
                    }
                }
            ],
            "columnDefs": [
                { "className": "text-center", "targets": 3 }
            ]
        });

    });

    $(document).on('click', '#agregar_proveedor', function () {

        $.confirm({
        title: false,
        boxWidth: '600px',
        useBootstrap: false,
        content: `

            <style>

                .form-group {
                    margin-bottom: 1.5rem;
                }

                .camposFormulario {
                    font-size: 1.1rem;
                    color: #555;
                }

                .form-control {
                    border-radius: 0.375rem;
                    padding: 10px;
                    font-size: 1rem;
                }

                .form-control:focus {
                    border-color: #007bff;
                    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                }

                .btn-block {
                    width: 100%;
                }

            </style>

            <div class="container">
            
                <div class="form-group">
                    <label for="proveedor" class="camposFormulario">Proveedor</label>
                    <input type="text" class="form-control" id="proveedor" placeholder="Escriba nombre del proveedor" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="rfc" class="camposFormulario">RFC</label>
                    <input type="text" class="form-control" id="rfc" placeholder="Ingrese el RFC" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="telefono" class="camposFormulario">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" placeholder="Ingrese numero telefonico" autocomplete="off" required>
                </div>

                <button class="btn btn-primary btn-block" id="enviarProveedor">Enviar</button>
            </div>

            `,
            buttons: false
        });


    });

    $(document).on('click', '#enviarProveedor', function () {

        let data = {
            proveedor: $("#proveedor").val(),
            rfc: $("#rfc").val(),
            telefono: $("#telefono").val()
        };

        console.log(JSON.stringify(data));

    });

    </script>
</body>
</html>
