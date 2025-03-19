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

                                    <div style="position: absolute; top: 40%; left: 44%; transform: translate(-50%, -50%) z-index: 999; display: none;" id="loader">
                                        <img src="http://127.0.0.1/requisiciones/public/assets/img/loader.gif" width="80" height="80">
                                    </div>

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

    let ventanaProveedor = "";

    $(document).ready(function() {

        $('#tabla_proveedores').DataTable({
            "ajax": {
                "url": "http://127.0.0.1/requisiciones/proveedores/obtenerProveedores",
                "type": "GET",
                "dataSrc": ""
            },
            "columns": [
                { "data": "proveedor" },
                { "data": "rfc" },
                { "data": "telefono" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <button type="button" class="btn btn-outline-dark btn-sm" id="editar">
                                <i class="bx bx-list-ul"></i> Editar
                            </button>
                        `;
                    }
                }
            ],
            "columnDefs": [
                { 
                    "targets": 0,
                    "width": "20%"
                },
                { 
                    "targets": 1,
                    "width": "20%"
                },
                { 
                    "targets": 2,
                    "width": "25%"
                },
                { 
                    "targets": 3,
                    "width": "10%",
                    "className": "text-center"
                }
            ]
        });

    });

    $(document).on('click', '#agregar_proveedor', function () {

        ventanaProveedor = $.confirm({
        title: false,
        boxWidth: '700px',
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Proveedor <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveProveedor" placeholder="Ingrese nombre del proveedor" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Vende <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveVende" placeholder="Ingrese nombre de lo que vende" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">RFC <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveRFC" placeholder="Ingrese el RFC" autocomplete="off" required>
                            <p class="help-text">Formato para personas físicas: AAAA######AAA / Para personas morales: AAA######AAA</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Codigo postal <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveCodigoPostal" placeholder="Ingrese codigo postal" autocomplete="off" required>
                            <p class="help-text">Ingrese numero de codigo postal</p>
                        </div>
                   </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Pais <span style="color: #d60b52;">*</span></label>
                            <select class="form-control" id="AgregarFormProvePais" required>
                            <option> selecciona un pais </option>
                            <option> Aland Islands </option>
                            <option> Afghanistan </option>
                            <option> Albania </option>
                            <option> Algeria </option>
                            <option> American Samoa </option>
                            <option> Andorra </option>
                            <option> Angola </option>
                            <option> Anguilla </option>
                            <option> Antarctica </option>
                            <option> Antigua And Barbuda </option>
                            <option> Argentina </option>
                            <option> Armenia </option>
                            <option> Aruba </option>
                            <option> Australia </option>
                            <option> Austria </option>
                            <option> Azerbaijan </option>
                            <option> Bahrain </option>
                            <option> Bangladesh </option>
                            <option> Barbados </option>
                            <option> Belarus </option>
                            <option> Belgium </option>
                            <option> Belize </option>
                            <option> Benin </option>
                            <option> Bermuda </option>
                            <option> Bhutan </option>
                            <option> Bolivia </option>
                            <option> Bonaire, Sint Eustatius and Saba </option>
                            <option> Bosnia and Herzegovina </option>
                            <option> Botswana </option>
                            <option> Bouvet Island </option>
                            <option> Brazil </option>
                            <option> British Indian Ocean Territory </option>
                            <option> Brunei </option>
                            <option> Bulgaria </option>
                            <option> Burkina Faso </option>
                            <option> Burundi </option>
                            <option> Cambodia </option>
                            <option> Cameroon </option>
                            <option> Canada </option>
                            <option> Cape Verde </option>
                            <option> Cayman Islands </option>
                            <option> Central African Republic </option>
                            <option> Chad </option>
                            <option> Chile </option>
                            <option> China </option>
                            <option> Christmas Island </option>
                            <option> Cocos (Keeling) Islands </option>
                            <option> Colombia </option>
                            <option> Comoros </option>
                            <option> Congo </option>
                            <option> Cook Islands </option>
                            <option> Costa Rica </option>
                            <option> Cote D'Ivoire (Ivory Coast) </option>
                            <option> Croatia </option>
                            <option> Cuba </option>
                            <option> Curaçao </option>
                            <option> Cyprus </option>
                            <option> Czech Republic </option>
                            <option> Democratic Republic of the Congo </option>
                            <option> Denmark </option>
                            <option> Djibouti </option>
                            <option> Dominica </option>
                            <option> Dominican Republic </option>
                            <option> East Timor </option>
                            <option> Ecuador </option>
                            <option> Egypt </option>
                            <option> El Salvador </option>
                            <option> Equatorial Guinea </option>
                            <option> Eritrea </option>
                            <option> Estonia </option>
                            <option> Ethiopia </option>
                            <option> Falkland Islands </option>
                            <option> Faroe Islands </option>
                            <option> Fiji Islands </option>
                            <option> Finland </option>
                            <option> France </option>
                            <option> French Guiana </option>
                            <option> French Polynesia </option>
                            <option> French Southern Territories </option>
                            <option> Gabon </option>
                            <option> Gambia The </option>
                            <option> Georgia </option>
                            <option> Germany </option>
                            <option> Ghana </option>
                            <option> Gibraltar </option>
                            <option> Greece </option>
                            <option> Greenland </option>
                            <option> Grenada </option>
                            <option> Guadeloupe </option>
                            <option> Guam </option>
                            <option> Guatemala </option>
                            <option> Guernsey and Alderney </option>
                            <option> Guinea </option>
                            <option> Guinea-Bissau </option>
                            <option> Guyana </option>
                            <option> Haiti </option>
                            <option> Heard Island and McDonald Islands </option>
                            <option> Honduras </option>
                            <option> Hong Kong S.A.R. </option>
                            <option> Hungary </option>
                            <option> Iceland </option>
                            <option> India </option>
                            <option> Indonesia </option>
                            <option> Iran </option>
                            <option> Iraq </option>
                            <option> Ireland </option>
                            <option> Israel </option>
                            <option> Italy </option>
                            <option> Jamaica </option>
                            <option> Japan </option>
                            <option> Jersey </option>
                            <option> Jordan </option>
                            <option> Kazakhstan </option>
                            <option> Kenya </option>
                            <option> Kiribati </option>
                            <option> Kosovo </option>
                            <option> Kuwait </option>
                            <option> Kyrgyzstan </option>
                            <option> Laos </option>
                            <option> Latvia </option>
                            <option> Lebanon </option>
                            <option> Lesotho </option>
                            <option> Liberia </option>
                            <option> Libya </option>
                            <option> Liechtenstein </option>
                            <option> Lithuania </option>
                            <option> Luxembourg </option>
                            <option> Macau S.A.R. </option>
                            <option> Macedonia </option>
                            <option> Madagascar </option>
                            <option> Malawi </option>
                            <option> Malaysia </option>
                            <option> Maldives </option>
                            <option> Mali </option>
                            <option> Malta </option>
                            <option> Man (Isle of) </option>
                            <option> Marshall Islands </option>
                            <option> Martinique </option>
                            <option> Mauritania </option>
                            <option> Mauritius </option>
                            <option> Mayotte </option>
                            <option> México </option>
                            <option> Micronesia </option>
                            <option> Moldova </option>
                            <option> Monaco </option>
                            <option> Mongolia </option>
                            <option> Montenegro </option>
                            <option> Montserrat </option>
                            <option> Morocco </option>
                            <option> Mozambique </option>
                            <option> Myanmar </option>
                            <option> Namibia </option>
                            <option> Nauru </option>
                            <option> Nepal </option>
                            <option> Netherlands </option>
                            <option> New Caledonia </option>
                            <option> New Zealand </option>
                            <option> Nicaragua </option>
                            <option> Niger </option>
                            <option> Nigeria </option>
                            <option> Niue </option>
                            <option> Norfolk Island </option>
                            <option> North Korea </option>
                            <option> Northern Mariana Islands </option>
                            <option> Norway </option>
                            <option> Oman </option>
                            <option> Pakistan </option>
                            <option> Palau </option>
                            <option> Palestinian Territory Occupied </option>
                            <option> Panama </option>
                            <option> Papua New Guinea </option>
                            <option> Paraguay </option>
                            <option> Peru </option>
                            <option> Philippines </option>
                            <option> Pitcairn Island </option>
                            <option> Poland </option>
                            <option> Portugal </option>
                            <option> Puerto Rico </option>
                            <option> Qatar </option>
                            <option> Reunion </option>
                            <option> Romania </option>
                            <option> Russia </option>
                            <option> Rwanda </option>
                            <option> Saint Helena </option>
                            <option> Saint Kitts And Nevis </option>
                            <option> Saint Lucia </option>
                            <option> Saint Pierre and Miquelon </option>
                            <option> Saint Vincent And The Grenadines </option>
                            <option> Saint-Barthelemy </option>
                            <option> Saint-Martin (French part) </option>
                            <option> Samoa </option>
                            <option> San Marino </option>
                            <option> Sao Tome and Principe </option>
                            <option> Saudi Arabia </option>
                            <option> Senegal </option>
                            <option> Serbia </option>
                            <option> Seychelles </option>
                            <option> Sierra Leone </option>
                            <option> Singapore </option>
                            <option> Sint Maarten (Dutch part) </option>
                            <option> Slovakia </option>
                            <option> Slovenia </option>
                            <option> Solomon Islands </option>
                            <option> Somalia </option>
                            <option> South Africa </option>
                            <option> South Georgia </option>
                            <option> South Korea </option>
                            <option> South Sudan </option>
                            <option> Spain </option>
                            <option> Sri Lanka </option>
                            <option> Sudan </option>
                            <option> Suriname </option>
                            <option> Svalbard And Jan Mayen Islands </option>
                            <option> Swaziland </option>
                            <option> Sweden </option>
                            <option> Switzerland </option>
                            <option> Syria </option>
                            <option> Taiwan </option>
                            <option> Tajikistan </option>
                            <option> Tanzania </option>
                            <option> Thailand </option>
                            <option> The Bahamas </option>
                            <option> Togo </option>
                            <option> Tokelau </option>
                            <option> Tonga </option>
                            <option> Trinidad And Tobago </option>
                            <option> Tunisia </option>
                            <option> Turkey </option>
                            <option> Turkmenistan </option>
                            <option> Turks And Caicos Islands </option>
                            <option> Tuvalu </option>
                            <option> Uganda </option>
                            <option> Ukraine </option>
                            <option> United Arab Emirates </option>
                            <option> United Kingdom </option>
                            <option> United States </option>
                            <option> United States Minor Outlying Islands </option>
                            <option> Uruguay </option>
                            <option> Uzbekistan </option>
                            <option> Vanuatu </option>
                            <option> Vatican City State (Holy See) </option>
                            <option> Venezuela </option>
                            <option> Vietnam </option>
                            <option> Virgin Islands (British) </option>
                            <option> Virgin Islands (US) </option>
                            <option> Wallis And Futuna Islands </option>
                            <option> Western Sahara </option>
                            <option> Yemen </option>
                            <option> Zambia </option>
                            <option> Zimbabwe </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Teléfono <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveTelefono" placeholder="Ingrese numero telefonico" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Correo <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveCorreo" placeholder="Ingrese numero telefonico de contacto" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveContacto" placeholder="Ingrese correo contacto" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Telefono contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveTelefonoContacto" placeholder="Ingrese numero telefonico de contacto" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Correo contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveCorreoContacto" placeholder="Ingrese correo contacto" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Cuenta <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveCuenta" placeholder="Ingrese numero de cuenta" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Banco <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control" id="AgregarFormProveBanco" placeholder="Ingrese banco" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telefono" class="camposFormulario">Clave <span style="color: #d60b52;">*</span></label>
                        <input type="text" class="form-control" id="AgregarFormProveClave" placeholder="Ingrese numero de clave" autocomplete="off" required>
                    </div>
                </div>

                <button class="btn btn-primary btn-block" id="enviarProveedor">Enviar</button>
            </div>

            `,
            buttons: false

        });


    });

    $(document).on('click', '#enviarProveedor', function () {

        let proveedor = $("#AgregarFormProveProveedor").val().trim();
        let vende = $("#AgregarFormProveVende").val().trim();
        let rfc = $("#AgregarFormProveRFC").val().trim();
        let codigoPostal = $("#AgregarFormProveCodigoPostal").val().trim();
        let pais = $("#AgregarFormProvePais").val().trim();
        let telefono = $("#AgregarFormProveTelefono").val().trim();
        let correo = $("#AgregarFormProveCorreo").val().trim();
        let contacto = $("#AgregarFormProveContacto").val().trim();
        let telefonoContacto = $("#AgregarFormProveTelefonoContacto").val().trim();
        let correoContacto = $("#AgregarFormProveCorreoContacto").val().trim();
        let cuenta = $("#AgregarFormProveCuenta").val().trim();
        let banco = $("#AgregarFormProveBanco").val().trim();
        let clave = $("#AgregarFormProveClave").val().trim();

        let data = {
            proveedor: proveedor,
            vende: vende,
            rfc: rfc,
            codigo_postal: codigoPostal,
            pais: pais,
            telefono: telefono,
            correo: correo,
            contacto: contacto,
            telefono_contacto: telefonoContacto,
            correo_contacto: correoContacto,
            cuenta : cuenta,
            banco: banco,
            clabe: clave
        };

        let regexProveedor = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexVende = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexRFC = /^[A-Za-z0-9\s]+$/;
        let regexCodigoPostal = /^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexPais = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexTelefono = /^[0-9]+$/;
        let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let regexContacto = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexTelefonoContacto = /^[0-9]+$/;
        let regexCorreoContacto = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let regexCuenta = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexBanco = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexClave = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;

        if (!regexProveedor.test(proveedor)) {
            alert("Nombre del proveedor es invalido ...");
            return false;
        }

        if (!regexVende.test(vende)) {
            alert("Vende es ivalido ...");
            return false;
        }

        if (!regexRFC.test(rfc)) {
            alert("RFC inválido asegúrate de que tiene el formato correcto ...");
            return false;
        }

        if (!regexCodigoPostal.test(codigoPostal)) {
            alert("EL codigo postal es invalido ...");
            return false;
        }

        if (!regexPais.test(pais)) {
            alert("EL codigo postal es invalido ...");
            return false;
        }

        if (!regexCorreo.test(correo)) {
            alert("Correo es invalido ...");
            return false;
        }

        if (!regexTelefono.test(telefono)) {
            alert("Telefono es invalido ...");
            return false;
        }

        if (!regexContacto.test(contacto)) {
            alert("Contacto es inválido ...");
            return false;
        }

        if (!regexTelefonoContacto.test(telefonoContacto)) {
            alert("Escriba un teléfono de contacto es invalido ...");
            return false;
        }

        if (!regexCorreoContacto.test(correoContacto)) {
            alert("EL correo es invalido ...");
            return false;
        }

        if (!regexCuenta.test(cuenta)) {
            alert("Cuenta invalida ...");
            return false;
        }

        if (!regexBanco.test(banco)) {
            alert("Banco invalido ...");
            return false;
        }

        if (!regexClave.test(clave)) {
            alert("Clave invalida ...");
            return false;
        }
        
        $("#AgregarFormProveProveedor").val("");
        $("#AgregarFormProveVende").val("");
        $("#AgregarFormProveRFC").val("");
        $("#AgregarFormProveCodigoPostal").val("");
        $("#AgregarFormProvePais").val("");
        $("#AgregarFormProveTelefono").val("");
        $("#AgregarFormProveCorreo").val("");
        $("#AgregarFormProveContacto").val("");
        $("#AgregarFormProveTelefonoContacto").val("");
        $("#AgregarFormProveCorreoContacto").val("");
        $("#AgregarFormProveCuenta").val("");
        $("#AgregarFormProveBanco").val("");
        $("#AgregarFormProveClave").val("");

        $("#loader").show();
       
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/requisiciones/proveedores/guardar",
            data: JSON.stringify(data),
            success: function(response){

                ventanaProveedor.close();
                setTimeout(function() {
                    $('#tabla_proveedores').DataTable().ajax.reload();
                    $("#loader").hide();  // Ocultar el loader
                }, 2000);

            }
        });

    });

    </script>
</body>
</html>
