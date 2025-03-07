<?php

namespace App\Controllers;

use App\Models\RequisicionesInventarioDetalleModel;
use App\Models\RequisicionesModel;



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
        $detalles = $requisicionesInventarioDetalleModel->obtenerDetallesRequisicion($idRequisicion);


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

    public function obtener_detalle_requisicion_parcial($idRequisicion)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();

        // Validar que el parámetro sea un número válido
        if (!is_numeric($idRequisicion)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID de requisición inválido.'
            ])->setStatusCode(400);
        }
 

        // Obtener los detalles desde el modelo
        $dataRequisicion = $requisicionesModel->obtenerPorId($idRequisicion);


        if (empty($dataRequisicion)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontro la requisición'
            ])->setStatusCode(404);
        }


        // Obtener los detalles desde el modelo
        $detalles = $requisicionesInventarioDetalleModel->obtenerDetallesRequisicion($idRequisicion);


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
                'detalle'    => $d['detalles'],
                'validado'   => $d['validado'],
            ];
        }, $detalles);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $responseDetalles,
            'requisicion' => $dataRequisicion
        ])->setStatusCode(200);
    }
}
