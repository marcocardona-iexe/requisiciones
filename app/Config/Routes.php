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



$routes->post('inventario/guardar', 'InventarioController::guardar');
$routes->get('inventario/obtenerTipoInventario', 'InventarioController::obtenerTipoInventario');
$routes->get('inventario/obtenerCategoria/(:any)', 'InventarioController::obtenerCategoria/$1');
$routes->get('inventario/obtenerTodasCategorias', 'InventarioController::obtenerTodasCategorias');
$routes->post('inventario/buscarProducto', 'InventarioController::buscarProducto');
$routes->get('inventario/get_proveedores-inventario/(:num)', 'InventarioController::get_proveedores_inventario/$1');








#Rutas para las requisiciones
$routes->get('proveedores/lista', 'ProveedoresController::lista');
#Peticiones Ajax
$routes->get('proveedores/obtener-proveedores', 'ProveedoresController::obtener_proveedores');
$routes->post('proveedores/guardar', 'ProveedoresController::guardar');
#Raul




$routes->post('orden-de-compra/imprimir-previo', 'OrdenCompraController::imprimir_previo');


#Rutas para el consumo de iexe one
$routes->post('inventario/obtener-inventario', 'InventarioController::obtener_inventario');

