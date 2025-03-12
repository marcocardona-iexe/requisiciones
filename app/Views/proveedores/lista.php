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
            { proveedor: 'Prueba A', rfc: 'RFC12345', telefono: '123-456-7890' },
            { proveedor: 'Prueba B', rfc: 'RFC23456', telefono: '234-567-8901' },
            { proveedor: 'Prueba C', rfc: 'RFC34567', telefono: '345-678-9012' },
            { proveedor: 'Prueba D', rfc: 'RFC45678', telefono: '456-789-0123' },
            { proveedor: 'Prueba E', rfc: 'RFC56789', telefono: '567-890-1234' },
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

                .container-proveedor{
                    font-family: Arial, sans-serif;
                }

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

                .form-title {
                    font-size: 20px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                }

                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                    padding: 0px;
                    padding-top: 10px;
                }

                .help-text {
                    font-size: 12px;
                    color: #666;
                    margin-top: 4px;
                }

            </style>

            <div class="container container-proveedor">

                <div class="modal-header">
                    <h2 class="form-title">Registro de Proveedor</h2>
                </div>

                <div class="form-group">
                    <label for="proveedor" class="camposFormulario">Proveedor <span style="color: #d60b52;">*</span></label>
                    <input type="text" class="form-control" id="proveedor" placeholder="Escriba nombre del proveedor" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="rfc" class="camposFormulario">RFC <span style="color: #d60b52;">*</span></label>
                    <input type="text" class="form-control" id="rfc" placeholder="Ingrese el RFC" autocomplete="off" required>
                    <p class="help-text">Formato para personas físicas: AAAA######AAA / Para personas morales: AAA######AAA</p>
                </div>

                <div class="form-group">
                    <label for="telefono" class="camposFormulario">Teléfono <span style="color: #d60b52;">*</span></label>
                    <input type="text" class="form-control" id="telefono" placeholder="Ingrese numero telefonico" autocomplete="off" required>
                    <p class="help-text">Ingrese un numero valido sin espacios ni guiones</p>
                </div>

                <button class="btn btn-primary btn-block" id="enviarProveedor">Enviar</button>
            </div>

            `,
            buttons: false
        });


    });

    $(document).on('click', '#enviarProveedor', function () {

        let proveedor = $("#proveedor").val().trim();
        let rfc = $("#rfc").val().trim();
        let telefono = $("#telefono").val().trim();

        let data = {
            proveedor: proveedor,
            rfc: rfc,
            telefono: telefono
        };

        let regexProveedor = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexRFC = /^[A-Za-z0-9\s]+$/;
        let regexTelefono = /^[0-9]+$/;

        if (!regexProveedor.test(proveedor)) {
            alert("Nombre del proveedor es invalido ...");
            return false;
        }

        if (!regexRFC.test(rfc)) {
            alert("RFC inválido asegúrate de que tiene el formato correcto ...");
            return false;
        }

        if (!regexTelefono.test(telefono)) {
            alert("Escriba un teléfono inválido ...");
            return false;
        }

        $("#proveedor").val("");
        $("#rfc").val("");
        $("#telefono").val("");

        console.log(JSON.stringify(data));

    });

    </script>
</body>
</html>
