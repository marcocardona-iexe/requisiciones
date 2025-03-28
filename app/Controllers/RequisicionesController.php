<?php

namespace App\Controllers;

use App\Models\RequisicionesModel;
use App\Models\RequisicionesInventarioDetalleModel;
use App\Models\ProvedoresModel;
use App\Models\InventarioProveedoresModel;
use App\Models\VentasModel;
use App\Models\OrdenCompraModel;
use App\Models\ProveedoresModel;

class RequisicionesController extends BaseController
{



    public function lista()
    {

        $datajs = [
            'scripts' => [
                'public/assets/system/js/requisiciones/requisiciones.js',
                'public/assets/system/js/requisiciones/cancelaciones.js',
                'public/assets/system/js/requisiciones/realizar_compra.js',
                'public/assets/system/js/requisiciones/ver_solicitud.js'

            ]
        ];
        $data = [
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js', $datajs),
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


    public function validar_parcialmente($id)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();

        try {
            $data = $this->request->getPost();

            // Validar que los datos requeridos estén presentes
            if (!isset($data["items"]) || !is_array($data["items"])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'El campo "items" es obligatorio y debe ser un array válido.'
                ])->setStatusCode(400);
            }

            if (!isset($data['comentario']) || empty(trim($data['comentario']))) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'El comentario es obligatorio.'
                ])->setStatusCode(400);
            }

            foreach ($data["items"] as $i) {
                // Validar que el objeto contenga los campos necesarios
                if (!isset($i["id_detalle"]) || !isset($i["check"])) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Cada item debe contener "id_detalle" y "check".'
                    ])->setStatusCode(400);
                }

                // Convertir "check" a booleano correctamente
                $validado = filter_var($i["check"], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

                // Actualizar base de datos
                $actualizado = $requisicionesInventarioDetalleModel->editarPorWhere(
                    ["id" => $i['id_detalle']],
                    ["validado" => $validado]
                );

                // Verificar si la actualización falló
                if (!$actualizado) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Error al actualizar el detalle de la requisición.'
                    ])->setStatusCode(500);
                }
            }

            // Actualizar comentario
            $actualizadoComentario = $requisicionesModel->editarPorWhere(
                ["id" => $id],
                [
                    "comentario_estatus" => $data['comentario'],
                    "id_estatus" => 2
                ]
            );

            if (!$actualizadoComentario) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar el comentario de la requisición.'
                ])->setStatusCode(500);
            }

            // Respuesta exitosa
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Datos guardados exitosamente'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function rechazar($id)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();

        // Obtener datos de la petición
        $data = $this->request->getPost();

        // Validar que el comentario esté presente y no vacío
        if (!isset($data['motivo']) || empty(trim($data['motivo']))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'El motivo del rechazo es obligatorio.'
            ])->setStatusCode(400);
        }

        try {
            // Actualizar la requisición con el comentario y el estatus de rechazo (5)
            $actualizadoRequisicion = $requisicionesModel->editarPorWhere(
                ["id" => $id],
                [
                    "comentario_estatus" => trim($data['motivo']),
                    "id_estatus" => 5
                ]
            );

            // Validar si la actualización de la requisición falló
            if (!$actualizadoRequisicion) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar la requisición.'
                ])->setStatusCode(500);
            }

            // Actualizar todos los registros relacionados en requisicionesInventarioDetalle
            $actualizado = $requisicionesInventarioDetalleModel->editarPorWhere(
                ["id_requisicion" => $id],
                ["validado" => 0]
            );

            // Validar si la actualización de los detalles falló
            if (!$actualizado) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar los detalles de la requisición.'
                ])->setStatusCode(500);
            }

            // Si todo es exitoso, retornar éxito
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Requisición rechazada correctamente.'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            // Manejar cualquier excepción inesperada
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }


    public function validar_compra($id)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();

        try {
            $data = $this->request->getPost();

            // Validar que los datos requeridos estén presentes
            if (!isset($data["items"]) || !is_array($data["items"])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'El campo "items" es obligatorio y debe ser un array válido.'
                ])->setStatusCode(400);
            }

            if (!isset($data['comentario']) || empty(trim($data['comentario']))) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'El comentario es obligatorio.'
                ])->setStatusCode(400);
            }

            foreach ($data["items"] as $i) {
                // Validar que el objeto contenga los campos necesarios
                if (!isset($i["id_detalle"]) || !isset($i["check"])) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Cada item debe contener "id_detalle" y "check".'
                    ])->setStatusCode(400);
                }

                // Convertir "check" a booleano correctamente
                $validado = filter_var($i["check"], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

                // Actualizar base de datos
                $actualizado = $requisicionesInventarioDetalleModel->editarPorWhere(
                    ["id" => $i['id_detalle']],
                    ["validado" => $validado]
                );

                // Verificar si la actualización falló
                if (!$actualizado) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Error al actualizar el detalle de la requisición.'
                    ])->setStatusCode(500);
                }
            }

            // Actualizar comentario
            $actualizadoComentario = $requisicionesModel->editarPorWhere(
                ["id" => $id],
                [
                    "comentario_estatus" => $data['comentario'],
                    "id_estatus" => 3
                ]
            );

            if (!$actualizadoComentario) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar el comentario de la requisición.'
                ])->setStatusCode(500);
            }

            // Respuesta exitosa
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Datos guardados exitosamente'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }


    public function obtener_compra_requisicion($idRequisicion)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();
        $inventarioProveedoresModel = new InventarioProveedoresModel();



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
        $detalles = $requisicionesInventarioDetalleModel->obtenerDetallesRequisicionCompra($idRequisicion);


        if (empty($detalles)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se encontraron detalles para esta requisición.'
            ])->setStatusCode(404);
        }


        // Formatear la respuesta
        $responseDetalles = array_map(function ($d) {
            return (object) [
                'id_detalle' => $d['id'],
                'cantidad'   => $d['cantidad'],
                'stock'      => $d['stock_individual'],
                'categoria'  => $d['nombre'],
                'detalle'    => $d['detalles'],
                'validado'   => $d['validado'],
                'id_variante'   => $d['id_variante'],

            ];
        }, $detalles);

        foreach ($responseDetalles as $d) {
            $d->proveedor = $inventarioProveedoresModel->obtenerProveedoresPorInventario($d->id_variante);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $responseDetalles,
            'requisicion' => $dataRequisicion
        ])->setStatusCode(200);
    }



    public function realizar_compra($id)
    {
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();
        $ventasModel = new VentasModel();
        $proveedorModel = new ProveedoresModel();
        $ordenCompraModel = new OrdenCompraModel();


        $data = $this->request->getPost();
        try {
            // Obtener el último ID de venta desde el modelo
            $ultimoCodigo = $ventasModel->obtenerUltimoCodigoVenta(); // Método que debes implementar en el modelo
            // Generar el código en el formato V-000001
            if ($ultimoCodigo === NULL) {
                $codigo = "V-000001"; // Si no hay registros, comienza con el primer código
            } else {
                // Extraer el número del código (asumiendo el formato "V-XXXXXX")
                $numero = (int) substr($ultimoCodigo, 2); // Quita el prefijo "V-" y convierte a entero
                $codigo = "V-" . str_pad($numero + 1, 6, "0", STR_PAD_LEFT); // Generar el consecutivo
            }

            // Llenar el array $dataInsertVenta
            $dataInsertVenta = [
                'codigo' => $codigo,
                'fecha' => $data['venta']['fecha_compra'],
                'iva_aplicado' => $data['venta']['iva_aplicado'] == true ? 1 : 0,
                'iva' => $data['venta']['iva'],
                'subtotal' => $data['venta']['subtotal'],
                'total' => $data['venta']['total'],
                'descuento' => $data['venta']['descuento_total']
                // Otros atributos que necesites agregar
            ];

            $idVenta = $ventasModel->insertar($dataInsertVenta); // Método que debes implementar en el modelo

            foreach ($data['ordenes'] as $o => $orden) {
                $aplicar_iva = $orden['iva'] == true ? 1 : 0;

                $total = 0;
                $subtotal = 0;
                $descuento = 0;
                foreach ($orden['productos'] as $p) {
                    $subtotal += $p['total'];
                    $descuento += $p['descuento'];
                }

                $total = ($aplicar_iva) ?  ($subtotal - $descuento) * .16 : $subtotal - $descuento;
                $iva_aplicado = ($aplicar_iva) ? $subtotal * .16 : 0;

                $dataProveedor = $proveedorModel->obtenerPorId($o);

                // Obtener el último ID de venta desde el modelo
                $ultimoCodigo = $ventasModel->obtenerUltimoCodigoVenta(); // Método que debes implementar en el modelo
                // Generar el código en el formato V-000001
                if ($ultimoCodigo === NULL) {
                    $codigo = $dataProveedor . "-000001"; // Si no hay registros, comienza con el primer código
                } else {
                    // Extraer el número del código (asumiendo el formato "V-XXXXXX")
                    $numero = (int) substr($ultimoCodigo, 2); // Quita el prefijo "V-" y convierte a entero
                    $codigo = $dataProveedor->codigo . str_pad($numero + 1, 6, "0", STR_PAD_LEFT); // Generar el consecutivo
                }


                $dataInsertOrden = [
                    'id_venta' => $idVenta,
                    'id_proveedor' => $o,
                    'iva' => $orden['iva'],
                    'iva_aplicado' =>  $iva_aplicado,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'descuento' => $descuento,
                    'codigo' => $codigo

                ];
                $ordenCompraModel->insertar($dataInsertOrden);
            }





            // $data = $this->request->getPost();

            // Actualizar comentario
            $actualizadoComentario = $requisicionesModel->editarPorWhere(
                ["id" => $id],
                [
                    "id_estatus" => 4
                ]
            );

            if (!$actualizadoComentario) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Error al actualizar el comentario de la requisición.'
                ])->setStatusCode(500);
            }

            // Respuesta exitosa
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Datos guardados exitosamente'
            ])->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function guardar()
    {
        $requisicionesModel = new RequisicionesModel();
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();

        $data = $this->request->getPost();
        $dataInsert = [
            "id_usuario" => 745,
            "justificacion" => $data['comentarios'],
            "id_estatus" => 1
        ];

        $id_requisicion = $requisicionesModel->insertar($dataInsert);
        foreach ($data['productos'] as $p) {
            $dataInsert = [
                "id_requisicion" => $id_requisicion,
                "cantidad" => $p['cantidad'],
                "id_variante" => $p['id'],
                "validado" => 0
            ];
            $requisicionesInventarioDetalleModel->insertar($dataInsert);
        }


        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Datos guardados exitosamente'
        ])->setStatusCode(200);
    }
}
