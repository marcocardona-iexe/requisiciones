<?php

namespace App\Controllers;

use App\Models\InventarioModel;



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

    public function guardar()
    {
        $request = service('request');
        $data = $request->getPost();
    }
}
