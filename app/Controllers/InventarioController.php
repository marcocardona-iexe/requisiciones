<?php

namespace App\Controllers;

use App\Models\InventarioModel;
use App\Models\AreasModel;
use App\Models\CategoriasModel;
use App\Models\InventarioProveedoresModel;



class InventarioController extends BaseController
{



    public function lista()
    {
        $categoriasModel = new CategoriasModel();
        $areasModel = new AreasModel();
        $categorias = $categoriasModel->obtenerPorWhere(['status' => 1]);
        $areas = $areasModel->obtenerPorWhere(['status' => 1]);



        $data = [
            'categorias' => $categorias,
            'areas' => $areas,
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js'),
        ];

        return view('inventario/lista', $data);
    }

    /*
     * Método para obtener el inventario para el select2
     * @return JSON
     */
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




    public function get_inventario_table()
    {
        $request = service('request');
        $model = new InventarioModel();

        // Parámetros enviados por DataTables
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $length = $request->getPost('length');
        $search = $request->getPost('search')['value'];
        $order_column_index = $request->getPost('order')[0]['column'];
        $order_dir = $request->getPost('order')[0]['dir'];

        // Definir las columnas para ordenar correctamente
        $columns = ['inventario.id', 'inventario.nombre', 'caracteristicas', 'categorias.categoria', 'area.area', 'inventario_detalles.stock', 'inventario_detalles.stock_minimo'];
        $order_column = $columns[$order_column_index] ?? 'inventario.id';

        // Obtener filtros desde la petición
        $categoria = $request->getPost('categoria'); // ID de la categoría
        $area = $request->getPost('area'); // ID del área

        // Obtener datos de inventario con filtros
        $inventario = $model->getInventario($length, $start, $search, $order_column, $order_dir, $categoria, $area);
        $total_filtered = $model->getTotalInventario($search, $categoria, $area);
        $total_records = $model->getTotalInventario(); // Total sin filtros

        // Respuesta en formato JSON para DataTables
        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_filtered,
            'data' => $inventario
        ]);
    }



    public function obtenerTipoInventario()
    {

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
}
