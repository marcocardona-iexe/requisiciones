<?php

namespace App\Controllers;

use App\Models\InventarioModel;
use App\Models\CategoriasModel;
use App\Models\InventarioProveedoresModel;



class InventarioController extends BaseController
{



    public function lista()
    {

        $data = [
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js'),
        ];

        return view('inventario/lista', $data);
    }

    public function obtenerInventario()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inventarioModel = new InventarioModel();

        $request = service('request');

        $search = $request->getPost('search')['value'] ?? '';
        $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;

        // Columnas que se pueden ordenar en el DataTable
        $columnasOrdenables = ['d.id_variante', 'i.nombre', 'categorias.categoria', 'stock_total'];
        $orderColumn = $columnasOrdenables[$orderColumnIndex] ?? 'i.nombre';

        // Obtener datos con filtros y orden
        $datos = $inventarioModel->obtenerInventarioDetallesDataTable($search, $orderColumn, $orderDir, $start, $length);

        $totalFiltrado = $inventarioModel->contarFilasFiltradasDataTable($search);
        $totalRegistros = $inventarioModel->contarFilasTotalesDataTable();

        return $this->response->setJSON([
            'draw' => intval($request->getPost('draw')),
            'recordsTotal' => $totalRegistros,
            'recordsFiltered' => $totalFiltrado,
            'data' => $datos,
        ]);
    }

    public function obtenerTipoInventario()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $inventarioModel = new InventarioModel();
        $articulos = $inventarioModel->obtenerTodos();

        if (!empty($articulos)) {

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Datos obtenidos exitosamente.',
                'data'    => $articulos
            ])->setStatusCode(200);
        } else {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición',
                'data'    => $articulos
            ])->setStatusCode(404);
        }
    }

    public function obtenerCategoria($idInventario)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $categoriasModel = new CategoriasModel();
        $categoria = $categoriasModel->obtenerCategoria($idInventario);

        if (!empty($categoria)) {

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Datos obtenidos exitosamente.',
                'data'    => $categoria
            ])->setStatusCode(200);
        } else {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición',
                'data'    => $categoria
            ])->setStatusCode(404);
        }
    }

    public function obtenerTodasCategorias()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $categoriasModel = new CategoriasModel();
        $categoria = $categoriasModel->obtenerTodasCategorias();

        if (!empty($categoria)) {

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Datos obtenidos exitosamente.',
                'data'    => $categoria
            ])->setStatusCode(200);
        } else {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición',
                'data'    => $categoria
            ])->setStatusCode(404);
        }
    }

    public function buscarProducto()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $producto = $_POST['producto'];

        $inventarioModel = new InventarioModel();
        $producto = $inventarioModel->buscarProducto($producto);

        if ($producto == 0) {

            return $this->response->setJSON([
                'status'  => 'success'
            ]);
        } else {

            return $this->response->setJSON([
                'status'  => 'error'
            ]);
        }
    }

    public function guardar()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $json = $this->request->getJSON();
        if ($json) {
            echo true;
        } else {
            echo false;
        }
    }

    public function get_proveedores_inventario($id_inventario)
    {
        $inventarioProveedoresModel = new InventarioProveedoresModel();
        $dataProveedores = $inventarioProveedoresModel->getProveedoresConInventario($id_inventario);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $dataProveedores,
        ])->setStatusCode(200);
    }

    public function obtener_inventario()
    {
        // Obtenemos el término de búsqueda enviado por Select2 usando POST
        $q = $this->request->getPost('q');  // 'q' es el parámetro que envía Select2 con la búsqueda

        // Cargar el modelo
        $inventarioModel = new InventarioModel();

        // Realizamos la consulta al modelo para obtener los resultados
        $resultados = $inventarioModel->buscar_inventario($q);

        // Preparamos los resultados para devolver en formato JSON
        $data = [];
        foreach ($resultados as $row) {
            $data[] = [
                'id' => $row->id,    // ID de la opción
                'text' => $row->caracteristicas  // El texto que aparecerá en el select2
            ];
        }

        // Devolvemos los resultados como JSON
        return $this->response->setJSON([
            'items' => $data,
            'total_count' => count($data)  // Si necesitas mostrar el total de resultados
        ]);
    }
}
