<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->get('template', 'TemplateController::lista');


#Rutas pra inventario
$routes->get('inventario/lista', 'InventarioController::lista');
#Peticiones AJAX
$routes->post('inventario/data-table', 'InventarioController::obtenerInventario');
$routes->post('inventario/guardar', 'InventarioController::guardar');


#Rutas para las requisiciones
$routes->get('requisiciones/lista', 'RequisicionesController::lista');
#Peticiones AJAX
$routes->post('requisiciones/data-table', 'RequisicionesController::obtenerRequisiciones');
$routes->post('requisiciones/obtener-detalle-requisicion/(:num)', 'RequisicionesInventarioDetalleController::obtener_detalle_requisicion/$1');


