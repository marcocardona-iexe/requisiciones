<?php

namespace App\Controllers;

use App\Models\RequisicionesModel;



class RequisicionesController extends BaseController
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

        return view('requisiciones/lista', $data);
    }

    public function obtenerRequisiciones()
    {
        $request = service('request');
        $requisicionesModel = new RequisicionesModel();

        $search = $request->getPost('search')['value'] ?? '';
        $order_column_index = $request->getPost('order')[0]['column'] ?? 0;
        $order_dir = $request->getPost('order')[0]['dir'] ?? 'asc';
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;

        // Mapear índices a nombres de columnas
        $columns = [
            'id_usuario',
            'justificacion',
            'id_estatus',
            'comentario_estatus',
            'fecha_entregado',
            'fecha_entrega'
        ];
        $order_column = $columns[$order_column_index] ?? 'id';

        // Obtener datos paginados
        $data = $requisicionesModel->ObtenerRequisicionesDataTable($search, $order_column, $order_dir, $start, $length);

        // Contar registros
        $totalRecords = $requisicionesModel->totalRecordsDataTable();
        $totalFiltered = $requisicionesModel->totalFilteredRecordsDataTable($search);

        return $this->response->setJSON([
            'draw' => intval($request->getPost('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
}
