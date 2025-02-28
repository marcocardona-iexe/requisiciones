<?php

namespace App\Controllers;

use App\Models\RequisicionesInventarioDetalleModel;



class RequisicionesInventarioDetalleController extends BaseController
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

        return view('template/lista', $data);
    }

    public function obtener_detalle_requisicion($idRequisicion)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();

        // Validar que el parámetro sea un número válido
        if (!is_numeric($idRequisicion)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID de requisición inválido.'
            ])->setStatusCode(400);
        }

        // Obtener los detalles desde el modelo
        $detalles = $requisicionesInventarioDetalleModel->obtenerDetalleRequisicion($idRequisicion);
        var_dump($detalles);
        die;

        if (empty($detalles)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición.'
            ])->setStatusCode(404);
        }

        // Formatear la respuesta
        $responseDetalles = array_map(function ($d) {
            return [
                'id_detalle' => $d['id'],
                'cantidad'   => $d['cantidad'],
                'stock'      => $d['stock_individual'],
                'categoria'  => $d['nombre'],
                'detalle'    => $d['detalles']
            ];
        }, $detalles);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $responseDetalles
        ])->setStatusCode(200);
    }
}
