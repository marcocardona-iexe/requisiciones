<!DOCTYPE html>

<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?= base_url('public/assets/') ?>"
    data-template="vertical-menu-template-free">

<?= $head; ?>
<style>
tbody {
    font-size: 12px;
}   

</style>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inventario /</span> Lista</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div class="container">
                                <div class="row align-items-center mt-3">
                                    <div class="col-md-3">
                                        <label for="terminal" class="form-label">Area</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-map"></i></span>
                                            <select class="form-select" id="filtroArea">
                                                <option value="0">Seleccione un area</option>
                                                <?php foreach ($areas as $area) : ?>
                                                    <option value="<?= $area->id; ?>"><?= $area->area; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="terminal" class="form-label">Categorias</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-map"></i></span>
                                            <select class="form-select" id="filtroCategoria">
                                                <option value="0">Seleccione una categoria</option>
                                                <?php foreach ($categorias as $categoria) : ?>
                                                    <option value="<?= $categoria->id; ?>"><?= $categoria->categoria; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Botón de búsqueda -->
                                    <div class=" col-auto mt-4">
                                        <button class="btn btn-info btn-sm" id="btnFiltrar">
                                            <i class='bx bx-search-alt'></i> Buscar
                                        </button>
                                    </div>
                                    <div class=" col-auto mt-4">
                                        <button class="btn btn-info btn-sm" id="reiniciar">
                                            <i class='bx bx-select-multiple'></i> Ver todo
                                        </button>
                                    </div>
                                </div>
                                <div class="row justify-content-end align-items-center mt-3">
                                    <div class="col-auto">
                                        <button class="btn btn-info btn-sm btn-modal" id="agregarProducto"><i class='bx bx-plus-circle'></i>Agregar productos</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-sm table-striped table-hover table-bordered" id="tablaInventario">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Características</th>
                                                    <th>Categoría</th>
                                                    <th>Área</th>
                                                    <th>Stock</th>
                                                    <th>Stock Mínimo</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
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
</body>

</html>
