<?php

namespace App\Controllers;

use App\Models\RequisicionesModel;
use App\Models\RequisicionesInventarioDetalleModel;
use App\Models\ProvedoresModel;
use App\Models\InventarioProveedoresModel;
use App\Models\VentasModel;
use App\Models\OrdenCompraModel;
use App\Models\ProveedoresModel;
use App\Models\OrdenProdcutosModel;

class RequisicionesController extends BaseController
{
    protected $ordenProdcutosModel;

    public function __construct()
    {
        $this->ordenProdcutosModel = new OrdenProdcutosModel();
    }
    /**
     * Cargar la vista de la lista de requisiciones.
     *
     * @return \CodeIgniter\HTTP\Response La vista cargada.
     */

    public function lista()
    {
        // Cargar los js necesarios
        $datajs = [
            'scripts' => [
                'public/assets/system/js/requisiciones/requisiciones.js',
                'public/assets/system/js/requisiciones/cancelaciones.js',
                'public/assets/system/js/requisiciones/realizar_compra.js',
                'public/assets/system/js/requisiciones/ver_solicitud.js',
                'public/assets/system/js/requisiciones/autorizar.js',
                'public/assets/system/js/requisiciones/ver_compra.js'

            ]
        ];
        // Cargar los datos necesarios para la vista
        $data = [
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js', $datajs),
        ];

        // Cargar la vista de la lista de requisiciones
        return view('requisiciones/lista', $data);
    }


    /**
     * Obtener los datos de las requisiciones para el DataTable.
     *
     * @return \CodeIgniter\HTTP\Response JSON con los datos de las requisiciones.
     */
    public function obtenerRequisiciones()
    {
        // Cargar el modelo y la librería de request
        $request = service('request');
        // Cargar el modelo de requisiciones
        $requisicionesModel = new RequisicionesModel();
        // Obtener los parámetros de búsqueda y ordenación del DataTable
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
        // Obtener el nombre de la columna a ordenar
        $order_column = $columns[$order_column_index] ?? 'id';
        // Obtener datos paginados
        $data = $requisicionesModel->ObtenerRequisicionesDataTable($search, $order_column, $order_dir, $start, $length);
        // Contar registros
        $totalRecords = $requisicionesModel->totalRecordsDataTable();
        $totalFiltered = $requisicionesModel->totalFilteredRecordsDataTable($search);
        // Formatear los datos para el DataTable
        return $this->response->setJSON([
            'draw' => intval($request->getPost('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }


    /**
     * Obtener los detalles de una requisición específica, esto es para la primer validacion del sistema.
     *
     * @param int $idRequisicion ID de la requisición.
     * @return \CodeIgniter\HTTP\Response JSON con los detalles de la requisición.
     */
    public function obtener_detalle_requisicion($idRequisicion)
    {
        // Cargar el modelo de requisiciones inventario detalle
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();
        $requisicionesModel = new RequisicionesModel();

        // Validar que el parámetro sea un número válido
        if (!is_numeric($idRequisicion)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID de requisición inválido.'
            ])->setStatusCode(400);
        }

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
                'cantidad'   => $d['cantidad'] . ' (' . $d['unidad'] . ')',
                'stock'      => $d['stock'],
                'categoria'  => $d['nombre'],
                'detalle'    => $d['detalles']
            ];
        }, $detalles);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $responseDetalles,
            'requisicion' => $dataRequisicion
        ])->setStatusCode(200);
    }


    /**
     * Validar parcialmente una requisición.
     *
     * @param int $id ID de la requisición.
     * @return \CodeIgniter\HTTP\Response JSON con el resultado de la operación.
     */
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
                'cantidad'   => $d['cantidad'] . '(' . $d['unidad'] . ')',
                'stock'      => $d['stock'],
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
                'cantidad'   => $d['cantidad']  . ' (' . $d['unidad'] . ')',
                'stock'      => $d['stock'],
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
                $fecha_entrega = $orden['fecha'];

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
                $ultimoCodigo = $ordenCompraModel->obtenerUltimoCodigoVenta($dataProveedor->id); // Obtener el último código usado por el proveedor

                if ($ultimoCodigo === NULL) {
                    $codigo = $dataProveedor->codigo . "-000001"; // Si no hay registros previos, comienza con el primer código
                } else {
                    // Extraer el número del código (asumiendo el formato "XXXX-XXXXXX")
                    $partes = explode("-", $ultimoCodigo); // Dividir el código en partes usando "-"

                    if (isset($partes[1])) {
                        $numero = (int) $partes[1]; // Convertir la segunda parte a número entero
                        $codigo = $dataProveedor->codigo . "-" . str_pad($numero + 1, 6, "0", STR_PAD_LEFT); // Generar el nuevo código
                    } else {
                        // Si el formato del código no es el esperado, reiniciar la numeración
                        $codigo = $dataProveedor->codigo . "-000001";
                    }
                }



                $dataInsertOrden = [
                    'id_venta' => $idVenta,
                    'id_proveedor' => $o,
                    'iva' => $orden['iva'],
                    'iva_aplicado' =>  $iva_aplicado,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'descuento' => $descuento,
                    'codigo' => $codigo,
                    'fecha_entrega' => $fecha_entrega

                ];
                $idOrden = $ordenCompraModel->insertar($dataInsertOrden);
                // Ahora, incluir el id_orden en cada producto
                foreach ($orden['productos'] as $p) {
                    // echo "<pre>";
                    // print_r($p);
                    // echo "</pre>";
                    $dataInsert = [
                        "id_orden" => $idOrden, // Se agrega el id_orden generado
                        "id_requisicion_inventario" => $p['idProducto'],
                        "cantidad" => $p['cantidad'],
                        "precio_unitario" => $p['precio'],
                        "descuento" => $p['descuento'],
                        "total" => $p['total']
                    ];

                    // Aquí puedes insertar en la base de datos, por ejemplo:
                    $this->ordenProdcutosModel->insertar($dataInsert);
                }
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

    public function get_compra($id_requisicion)
    {
        $ventasModel = new VentasModel();
        $ordenCompraModel = new OrdenCompraModel();
        $ordenProdcutosModel = new OrdenProdcutosModel();

        $dataVenta = $ventasModel->obtenerPorWhere(['id_requisicion' => $id_requisicion]);

        $dataInsertOrdenes = $ordenCompraModel->obtenerPorWhere(['id_venta' => $dataVenta[0]->id]);
        $productos = [];
        foreach ($dataInsertOrdenes as $orden) {
            $productos[] = $ordenProdcutosModel->obtenerPorWhere(['id_orden' => $orden->id]);
        }

        $data = [
            "venta" => $dataVenta,
            "ordenes" => $dataInsertOrdenes,
            "productos" => $productos
        ];

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Datos obtenidos exitosamente.',
            'data'    => $data,
        ])->setStatusCode(200);
    }


    public function ver_compra_realizada()
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
                'cantidad'   => $d['cantidad']  . ' (' . $d['unidad'] . ')',
                'stock'      => $d['stock'],
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


    /**
     * Guardar una nueva requisición.
     *
     * @return \CodeIgniter\HTTP\Response JSON con el resultado de la operación.
     */

    public function guardar()
    {
        $requisicionesModel = new RequisicionesModel();
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();

        $data = $this->request->getPost();
        $dataInsert = [
            "id_usuario" => $data['id_usuario'],
            "justificacion" => $data['comentarios'],
            "id_estatus" => 1,
            "nombre" => $data['nombre'],
            "departamento" => $data['departamento'],
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
