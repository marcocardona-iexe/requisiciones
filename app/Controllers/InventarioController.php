<?php

namespace App\Controllers;

use App\Models\InventarioModel;
use App\Models\CategoriasModel;



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

        }else{

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

        }else{

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición',
                'data'    => $categoria
            ])->setStatusCode(404);
            
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
}
