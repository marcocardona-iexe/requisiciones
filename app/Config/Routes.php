<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('login/validar', 'Login::validar');

$routes->get('template', 'TemplateController::lista');


#Rutas pra inventario
$routes->get('inventario/lista', 'InventarioController::lista');
#Peticiones AJAX
$routes->post('inventario/get-inventario-table', 'InventarioController::get_inventario_table');
$routes->get('inventario/obtenerTodasCategorias', 'InventarioController::obtenerTodasCategorias');

$routes->group('usuarios', function ($routes) {
    $routes->get('lista', 'UsuariosController::lista');
    #Peticiones AJAX
    $routes->post('obtener-usuarios', 'UsuariosController::obtenerUsuarios');
});

#Rutas para las requisiciones


$routes->group('requisiciones', function ($routes) {

    $routes->get('lista', 'RequisicionesController::lista');
    #Peticiones AJAX
    $routes->post('data-table', 'RequisicionesController::obtenerRequisiciones');
    $routes->post('obtener-detalle-requisicion/(:num)', 'RequisicionesController::obtener_detalle_requisicion/$1');
    $routes->post('obtener-detalle-requisicion-parcial/(:num)', 'RequisicionesController::obtener_detalle_requisicion_parcial/$1');
    $routes->post('validar-parcialmente/(:num)', 'RequisicionesController::validar_parcialmente/$1');
    $routes->post('rechazar/(:num)', 'RequisicionesController::rechazar/$1');
    $routes->post('validar-compra/(:num)', 'RequisicionesController::validar_compra/$1');
    $routes->post('obtener-compra-requisicion/(:num)', 'RequisicionesController::obtener_compra_requisicion/$1');
    $routes->post('realizar-compra/(:num)', 'RequisicionesController::realizar_compra/$1');
    $routes->post('get-compra/(:num)', 'RequisicionesController::get_compra/$1');
    $routes->post('guardar', 'RequisicionesController::guardar');
});

#Rutas para las Areas
$routes->group('areas', function ($routes) {
    $routes->get('lista', 'AreasController::lista');
    #Peticiones AJAX
    $routes->post('obtener', 'AreasController::obtener');
});


#Rutas para las unidades

$routes->group('unidades', function ($routes) {
    $routes->get('lista', 'UnidadesController::lista');
    #Peticiones AJAX
    $routes->post('obtener', 'UnidadesController::obtener');
});


$routes->group('categorias', function ($routes) {
    $routes->get('lista', 'CategoriasController::lista');
    #Peticiones AJAX
    $routes->post('obtener', 'CategoriasController::obtener');
});


# Agrupación de rutas para inventario
$routes->group('inventario', function ($routes) {
    $routes->post('guardar', 'InventarioController::guardar');
    $routes->get('obtenerTipoInventario', 'InventarioController::obtenerTipoInventario');
    $routes->get('obtenerCategoria/(:any)', 'InventarioController::obtenerCategoria/$1');
    $routes->post('buscarProducto', 'InventarioController::buscarProducto');

    #Peticiones AJAX
    $routes->post('asignar-proveedor', 'InventarioController::asignar_proveedor');
    $routes->get('get_proveedores-inventario/(:num)', 'InventarioController::get_proveedores_inventario/$1');
});






#Rutas para las requisiciones
$routes->get('proveedores/lista', 'ProveedoresController::lista');
#Peticiones Ajax
$routes->get('proveedores/obtener-proveedores', 'ProveedoresController::obtener_proveedores');
$routes->post('proveedores/guardar', 'ProveedoresController::guardar');
#Raul



$routes->group('orden-de-compra', function ($routes) {
    $routes->post('imprimir-previo', 'OrdenCompraController::imprimir_previo');
    $routes->post('validar-xml', 'OrdenCompraController::validar');
});


#Rutas para el consumo de iexe one
$routes->post('inventario/obtener-inventario', 'InventarioController::obtener_inventario');
