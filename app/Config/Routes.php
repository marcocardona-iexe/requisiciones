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
$routes->post('inventario/data-table', 'InventarioController::obtenerInventario');
$routes->post('inventario/guardar', 'InventarioController::guardar');
$routes->get('inventario/obtenerTipoInventario', 'InventarioController::obtenerTipoInventario');
$routes->get('inventario/obtenerCategoria/(:any)', 'InventarioController::obtenerCategoria/$1');
$routes->get('inventario/obtenerTodasCategorias', 'InventarioController::obtenerTodasCategorias');
$routes->post('inventario/buscarProducto', 'InventarioController::buscarProducto');
$routes->get('inventario/get_proveedores-inventario/(:num)', 'InventarioController::get_proveedores_inventario/$1');
$routes->post('inventario/obtener-inventario', 'InventarioController::obtener_inventario');


#Rutas para las requisiciones
$routes->get('requisiciones/lista', 'RequisicionesController::lista');
#Peticiones AJAX
$routes->post('requisiciones/data-table', 'RequisicionesController::obtenerRequisiciones');
$routes->post('requisiciones/obtener-detalle-requisicion/(:num)', 'RequisicionesInventarioDetalleController::obtener_detalle_requisicion/$1');
$routes->post('requisiciones/validar-parcialmente/(:num)', 'RequisicionesController::validar_parcialmente/$1');
$routes->post('requisiciones/rechazar/(:num)', 'RequisicionesController::rechazar/$1');
$routes->post('requisiciones/obtener-detalle-requisicion-parcial/(:num)', 'RequisicionesInventarioDetalleController::obtener_detalle_requisicion_parcial/$1');
$routes->post('requisiciones/validar-compra/(:num)', 'RequisicionesController::validar_compra/$1');
$routes->post('requisiciones/obtener-compra-requisicion/(:num)', 'RequisicionesController::obtener_compra_requisicion/$1');
$routes->post('requisiciones/realizar-compra/(:num)', 'RequisicionesController::realizar_compra/$1');
$routes->post('requisiciones/guardar', 'RequisicionesController::guardar');


#Rutas para las requisiciones
$routes->get('proveedores/lista', 'ProveedoresController::lista');
#Peticiones Ajax
$routes->get('proveedores/obtener-proveedores', 'ProveedoresController::obtener_proveedores');
$routes->post('proveedores/guardar', 'ProveedoresController::guardar');
#Raul




$routes->post('orden-de-compra/imprimir-previo', 'OrdenCompraController::imprimir_previo');
