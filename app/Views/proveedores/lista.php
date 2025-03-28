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

                                    <div style="position: absolute; top: 40%; left: 44%; z-index: 999 !important; display: none;" id="loader">
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
        boxWidth: '850px',
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Proveedor <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveProveedor" placeholder="Ingrese nombre del proveedor" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Vende <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveVende" placeholder="Ingrese nombre de lo que vende" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">RFC <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveRFC" placeholder="Ingrese el RFC" autocomplete="off" required>
                            <p class="help-text">Formato para rfc AAAA######AAA /AAA######AAA</p>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Codigo postal <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveCodigoPostal" placeholder="Ingrese codigo postal" autocomplete="off" required>
                            <p class="help-text">Ingrese numero de codigo postal</p>
                        </div>
                   </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Pais <span style="color: #d60b52;">*</span></label>
                            <select class="form-control form-control-sm" id="AgregarFormProvePais" required>
                            <option value=""> selecciona un pais </option>
                            <option value="Aland Islands"> Aland Islands </option>
                            <option value="Afghanistan"> Afghanistan </option>
                            <option value="Albania"> Albania </option>
                            <option value="Algeria"> Algeria </option>
                            <option value="American Samoa"> American Samoa </option>
                            <option value="Andorra"> Andorra </option>
                            <option value="Angola"> Angola </option>
                            <option value="Anguilla"> Anguilla </option>
                            <option value="Antarctica"> Antarctica </option>
                            <option value="Antigua And Barbuda"> Antigua And Barbuda </option>
                            <option value="Argentina"> Argentina </option>
                            <option value="Armenia"> Armenia </option>
                            <option value="Aruba"> Aruba </option>
                            <option value="Australia"> Australia </option>
                            <option value="Austria"> Austria </option>
                            <option value="Azerbaijan"> Azerbaijan </option>
                            <option value="Bahrain"> Bahrain </option>
                            <option value="Bangladesh"> Bangladesh </option>
                            <option value="Barbados"> Barbados </option>
                            <option value="Belarus"> Belarus </option>
                            <option value="Belgium"> Belgium </option>
                            <option value="Belize"> Belize </option>
                            <option value="Benin"> Benin </option>
                            <option value="Bermuda"> Bermuda </option>
                            <option value="Bhutan"> Bhutan </option>
                            <option value="Bolivia"> Bolivia </option>
                            <option value="Bonaire, Sint Eustatius and Saba"> Bonaire, Sint Eustatius and Saba </option>
                            <option value="Bosnia and Herzegovina"> Bosnia and Herzegovina </option>
                            <option value="Botswana"> Botswana </option>
                            <option value="Bouvet Island"> Bouvet Island </option>
                            <option value="Brazil"> Brazil </option>
                            <option value="British Indian Ocean Territory"> British Indian Ocean Territory </option>
                            <option value="Brunei"> Brunei </option>
                            <option value="Bulgaria"> Bulgaria </option>
                            <option value="Burkina Faso"> Burkina Faso </option>
                            <option value="Burundi"> Burundi </option>
                            <option value="Cambodia"> Cambodia </option>
                            <option value="Cameroon"> Cameroon </option>
                            <option value="Canada"> Canada </option>
                            <option value="Cape Verde"> Cape Verde </option>
                            <option value="Cayman Islands"> Cayman Islands </option>
                            <option value="Central African Republic"> Central African Republic </option>
                            <option value="Chad"> Chad </option>
                            <option value="Chile"> Chile </option>
                            <option value="China"> China </option>
                            <option value="Christmas Island"> Christmas Island </option>
                            <option value="Cocos (Keeling) Islands"> Cocos (Keeling) Islands </option>
                            <option value="Colombia"> Colombia </option>
                            <option value="Comoros"> Comoros </option>
                            <option value="Congo"> Congo </option>
                            <option value="Cook Islands"> Cook Islands </option>
                            <option value="Costa Rica"> Costa Rica </option>
                            <option value="Cote D'Ivoire (Ivory Coast)"> Cote D'Ivoire (Ivory Coast) </option>
                            <option value="Croatia"> Croatia </option>
                            <option value="Cuba"> Cuba </option>
                            <option value="Curaçao"> Curaçao </option>
                            <option value="Cyprus"> Cyprus </option>
                            <option value="Czech Republic"> Czech Republic </option>
                            <option value="Democratic Republic of the Congo"> Democratic Republic of the Congo </option>
                            <option value="Denmark"> Denmark </option>
                            <option value="Djibouti"> Djibouti </option>
                            <option value="Dominica"> Dominica </option>
                            <option value="Dominican Republic"> Dominican Republic </option>
                            <option value="East Timor"> East Timor </option>
                            <option value="Ecuador"> Ecuador </option>
                            <option value="Egypt"> Egypt </option>
                            <option value="El Salvador"> El Salvador </option>
                            <option value="Equatorial Guinea"> Equatorial Guinea </option>
                            <option value="Eritrea"> Eritrea </option>
                            <option value="Estonia"> Estonia </option>
                            <option value="Ethiopia"> Ethiopia </option>
                            <option value="Falkland Islands"> Falkland Islands </option>
                            <option value="Faroe Islands"> Faroe Islands </option>
                            <option value="Fiji Islands"> Fiji Islands </option>
                            <option value="Finland"> Finland </option>
                            <option value="France"> France </option>
                            <option value="French Guiana"> French Guiana </option>
                            <option value="French Polynesia"> French Polynesia </option>
                            <option value="French Southern Territories"> French Southern Territories </option>
                            <option value="Gabon"> Gabon </option>
                            <option value="Gambia The"> Gambia The </option>
                            <option value="Georgia"> Georgia </option>
                            <option value="Germany"> Germany </option>
                            <option value="Ghana"> Ghana </option>
                            <option value="Gibraltar"> Gibraltar </option>
                            <option value="Greece"> Greece </option>
                            <option value="Greenland"> Greenland </option>
                            <option value="Grenada"> Grenada </option>
                            <option value="Guadeloupe"> Guadeloupe </option>
                            <option value="Guam"> Guam </option>
                            <option value="Guatemala"> Guatemala </option>
                            <option value="Guernsey and Alderney"> Guernsey and Alderney </option>
                            <option value="Guinea"> Guinea </option>
                            <option value="Guinea-Bissau"> Guinea-Bissau </option>
                            <option value="Guyana"> Guyana </option>
                            <option value="Haiti"> Haiti </option>
                            <option value="Heard Island and McDonald Islands"> Heard Island and McDonald Islands </option>
                            <option value="Honduras"> Honduras </option>
                            <option value="Hong Kong S.A.R."> Hong Kong S.A.R. </option>
                            <option value="Hungary"> Hungary </option>
                            <option value="Iceland"> Iceland </option>
                            <option value="India"> India </option>
                            <option value="Indonesia"> Indonesia </option>
                            <option value="Iran"> Iran </option>
                            <option value="Iraq"> Iraq </option>
                            <option value="Ireland"> Ireland </option>
                            <option value="Israel"> Israel </option>
                            <option value="Italy"> Italy </option>
                            <option value="Jamaica"> Jamaica </option>
                            <option value="Japan"> Japan </option>
                            <option value="Jersey"> Jersey </option>
                            <option value="Jordan"> Jordan </option>
                            <option value="Kazakhstan"> Kazakhstan </option>
                            <option value="Kenya"> Kenya </option>
                            <option value="Kiribati"> Kiribati </option>
                            <option value="Kosovo"> Kosovo </option>
                            <option value="Kuwait"> Kuwait </option>
                            <option value="Kyrgyzstan"> Kyrgyzstan </option>
                            <option value="Laos"> Laos </option>
                            <option value="Latvia"> Latvia </option>
                            <option value="Lebanon"> Lebanon </option>
                            <option value="Lesotho"> Lesotho </option>
                            <option value="Liberia"> Liberia </option>
                            <option value="Libya"> Libya </option>
                            <option value="Liechtenstein"> Liechtenstein </option>
                            <option value="Lithuania"> Lithuania </option>
                            <option value="Luxembourg"> Luxembourg </option>
                            <option value="Macau S.A.R."> Macau S.A.R. </option>
                            <option value="Macedonia"> Macedonia </option>
                            <option value="Madagascar"> Madagascar </option>
                            <option value="Malawi"> Malawi </option>
                            <option value="Malaysia"> Malaysia </option>
                            <option value="Maldives"> Maldives </option>
                            <option value="Mali"> Mali </option>
                            <option value="Malta"> Malta </option>
                            <option value="Man (Isle of)"> Man (Isle of) </option>
                            <option value="Marshall Islands"> Marshall Islands </option>
                            <option value="Martinique"> Martinique </option>
                            <option value="Mauritania"> Mauritania </option>
                            <option value="Mauritius"> Mauritius </option>
                            <option value="Mayotte"> Mayotte </option>
                            <option value="México"> México </option>
                            <option value="Micronesia"> Micronesia </option>
                            <option value="Moldova"> Moldova </option>
                            <option value="Monaco"> Monaco </option>
                            <option value="Mongolia"> Mongolia </option>
                            <option value="Montenegro"> Montenegro </option>
                            <option value="Montserrat"> Montserrat </option>
                            <option value="Morocco"> Morocco </option>
                            <option value="Mozambique"> Mozambique </option>
                            <option value="Myanmar"> Myanmar </option>
                            <option value="Namibia"> Namibia </option>
                            <option value="Nauru"> Nauru </option>
                            <option value="Nepal"> Nepal </option>
                            <option value="Netherlands"> Netherlands </option>
                            <option value="New Caledonia"> New Caledonia </option>
                            <option value="New Zealand"> New Zealand </option>
                            <option value="Nicaragua"> Nicaragua </option>
                            <option value="Niger"> Niger </option>
                            <option value="Nigeria"> Nigeria </option>
                            <option value="Niue"> Niue </option>
                            <option value="Norfolk Island"> Norfolk Island </option>
                            <option value="North Korea"> North Korea </option>
                            <option value="Northern Mariana Islands"> Northern Mariana Islands </option>
                            <option value="Norway"> Norway </option>
                            <option value="Oman"> Oman </option>
                            <option value="Pakistan"> Pakistan </option>
                            <option value="Palau"> Palau </option>
                            <option value="Palestinian Territory Occupied"> Palestinian Territory Occupied </option>
                            <option value="Panama"> Panama </option>
                            <option value="Papua New Guinea"> Papua New Guinea </option>
                            <option value="Paraguay"> Paraguay </option>
                            <option value="Peru"> Peru </option>
                            <option value="Philippines"> Philippines </option>
                            <option value="Pitcairn Island"> Pitcairn Island </option>
                            <option value="Poland"> Poland </option>
                            <option value="Portugal"> Portugal </option>
                            <option value="Puerto Rico"> Puerto Rico </option>
                            <option value="Qatar"> Qatar </option>
                            <option value="Reunion"> Reunion </option>
                            <option value="Romania"> Romania </option>
                            <option value="Russia"> Russia </option>
                            <option value="Rwanda"> Rwanda </option>
                            <option value="Saint Helena"> Saint Helena </option>
                            <option value="Saint Kitts And Nevis"> Saint Kitts And Nevis </option>
                            <option value="Saint Lucia"> Saint Lucia </option>
                            <option value="Saint Pierre and Miquelon"> Saint Pierre and Miquelon </option>
                            <option value="Saint Vincent And The Grenadines"> Saint Vincent And The Grenadines </option>
                            <option value="Saint-Barthelemy"> Saint-Barthelemy </option>
                            <option value="Saint-Martin (French part)"> Saint-Martin (French part) </option>
                            <option value="Samoa"> Samoa </option>
                            <option value="San Marino"> San Marino </option>
                            <option value="Sao Tome and Principe"> Sao Tome and Principe </option>
                            <option value="Saudi Arabia"> Saudi Arabia </option>
                            <option value="Senegal"> Senegal </option>
                            <option value="Serbia"> Serbia </option>
                            <option value="Seychelles"> Seychelles </option>
                            <option value="Sierra Leone"> Sierra Leone </option>
                            <option value="Singapore"> Singapore </option>
                            <option value="Sint Maarten (Dutch part)"> Sint Maarten (Dutch part) </option>
                            <option value="Slovakia"> Slovakia </option>
                            <option value="Slovenia"> Slovenia </option>
                            <option value="Solomon Islands"> Solomon Islands </option>
                            <option value="Somalia"> Somalia </option>
                            <option value="South Africa"> South Africa </option>
                            <option value="South Georgia"> South Georgia </option>
                            <option value="South Korea"> South Korea </option>
                            <option value="South Sudan"> South Sudan </option>
                            <option value="Spain"> Spain </option>
                            <option value="Sri Lanka"> Sri Lanka </option>
                            <option value="Sudan"> Sudan </option>
                            <option value="Suriname"> Suriname </option>
                            <option value="Svalbard And Jan Mayen Islands"> Svalbard And Jan Mayen Islands </option>
                            <option value="Swaziland"> Swaziland </option>
                            <option value="Sweden"> Sweden </option>
                            <option value="Switzerland"> Switzerland </option>
                            <option value="Syria"> Syria </option>
                            <option value="Taiwan"> Taiwan </option>
                            <option value="Tajikistan"> Tajikistan </option>
                            <option value="Tanzania"> Tanzania </option>
                            <option value="Thailand"> Thailand </option>
                            <option value="The Bahamas"> The Bahamas </option>
                            <option value="Togo"> Togo </option>
                            <option value="Tokelau"> Tokelau </option>
                            <option value="Tonga"> Tonga </option>
                            <option value="Trinidad And Tobago"> Trinidad And Tobago </option>
                            <option value="Tunisia"> Tunisia </option>
                            <option value="Turkey"> Turkey </option>
                            <option value="Turkmenistan"> Turkmenistan </option>
                            <option value="Turks And Caicos Islands"> Turks And Caicos Islands </option>
                            <option value="Tuvalu"> Tuvalu </option>
                            <option value="Uganda"> Uganda </option>
                            <option value="Ukraine"> Ukraine </option>
                            <option value="United Arab Emirates"> United Arab Emirates </option>
                            <option value="United Kingdom"> United Kingdom </option>
                            <option value="United States"> United States </option>
                            <option value="United States Minor Outlying Islands"> United States Minor Outlying Islands </option>
                            <option value="Uruguay"> Uruguay </option>
                            <option value="Uzbekistan"> Uzbekistan </option>
                            <option value="Vanuatu"> Vanuatu </option>
                            <option value="Vatican City State (Holy See)"> Vatican City State (Holy See) </option>
                            <option value="Venezuela"> Venezuela </option>
                            <option value="Vietnam"> Vietnam </option>
                            <option value="Virgin Islands (British)"> Virgin Islands (British) </option>
                            <option value="Virgin Islands (US)"> Virgin Islands (US) </option>
                            <option value="Wallis And Futuna Islands"> Wallis And Futuna Islands </option>
                            <option value="Western Sahara"> Western Sahara </option>
                            <option value="Yemen"> Yemen </option>
                            <option value="Zambia"> Zambia </option>
                            <option value="Zimbabwe"> Zimbabwe </option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Teléfono <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveTelefono" placeholder="Ingrese numero telefonico" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Correo <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveCorreo" placeholder="Ingrese numero telefonico de contacto" autocomplete="off" required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveContacto" placeholder="Ingrese correo contacto" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Telefono contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveTelefonoContacto" placeholder="Ingrese numero telefonico de contacto" autocomplete="off" required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Correo contacto <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveCorreoContacto" placeholder="Ingrese correo contacto" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc" class="camposFormulario">Cuenta <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveCuenta" placeholder="Ingrese numero de cuenta" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Banco <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveBanco" placeholder="Ingrese banco" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefono" class="camposFormulario">Clave <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveClave" placeholder="Ingrese numero de clave" autocomplete="off" required>
                        </div>
                    </div>
                    
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo" class="camposFormulario">Codigo <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveCodigo" placeholder="Ingrese codigo" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="direccion" class="camposFormulario">Direccion <span style="color: #d60b52;">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="AgregarFormProveDireccion" placeholder="Ingrese direccion" autocomplete="off" required>
                        </div>
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
        let codigo = $("#AgregarFormProveCodigo").val().trim();
        let direccion = $("#AgregarFormProveDireccion").val().trim();

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
            clabe: clave,
            codigo: codigo,
            direccion: direccion
        };

        let regexProveedor = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexVende = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexRFC = /^[A-Za-z0-9\s]+$/;
        let regexCodigoPostal = /^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexTelefono = /^[0-9]+$/;
        let regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let regexContacto = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexTelefonoContacto = /^[0-9]+$/;
        let regexCorreoContacto = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        let regexCuenta = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexBanco = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexClave = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexCodigo = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;
        let regexDireccion = /^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/;

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

        if (pais == "") {
            alert("EL pais esta vacio ...");
            return false;
        }

        if (!regexTelefono.test(telefono)) {
            alert("Telefono es invalido ...");
            return false;
        }

        if (!regexCorreo.test(correo)) {
            alert("Correo es invalido ...");
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

        if (!regexCodigo.test(codigo)) {
            alert("Codigo invalido ...");
            return false;
        }

        if (!regexDireccion.test(direccion)) {
            alert("Direccion invalida ...");
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
        $("#AgregarFormProveCodigo").val();
        $("#AgregarFormProveDireccion").val();

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
