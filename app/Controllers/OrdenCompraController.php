<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\InventarioModel;
use App\Models\ProveedoresModel;
use App\Models\RequisicionesInventarioDetalleModel;




class OrdenCompraController extends BaseController
{


    public function index()
    {
        // $request = service('request');
        // $proveedoresModel = new ProveedoresModel();
        // $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();


        // Cargar la vista con los datos
        $html = view('orden_compra/pdf_orden_compra');

        // $data = $request->getPost();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // $provedor = $data['proveedor']['nombre'];
        // $id_proveedor = $data['id_proveedor'];
        // $productos = $data['proveedor']['productos'];

        // $dataProveedor = $proveedoresModel->obtenerPorId($id_proveedor);

        // foreach ($productos as &$p) {

        //     $p['info'] = $requisicionesInventarioDetalleModel->obtenerDetallesRequisicionPorId($p['idProducto']);
        // }
        // echo "<pre>";
        // print_r($productos);
        // echo "</pre>";

        // die;

        // Opciones de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        // Inicializar Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Descargar el PDF
        $dompdf->stream('orden_compra.pdf', ['Attachment' => 1]);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Orden de compra generada correctamente'
        ]);
    }


    public function imprimir_previo()
    {
        $proveedoresModel = new ProveedoresModel();
        $requisicionesInventarioDetalleModel = new RequisicionesInventarioDetalleModel();


        $data = $this->request->getPost();


        $productos = [];
        $subtotal = 0;
        $descuento_total = 0;
        foreach ($data["proveedor"]["productos"] as $rp) {
            $producto = new \stdClass; // Instantiate stdClass object

            $detalle = $requisicionesInventarioDetalleModel->obtenerDetallesRequisicionPorId($rp["idProducto"]);

            $producto->cantidad = $detalle->cantidad;
            $producto->producto = $detalle->nombre;
            $producto->descripcion = $detalle->detalles;
            $producto->cantidad = $detalle->cantidad;
            $producto->precio = $rp["precio"];
            $producto->descuento = $rp["descuento"];
            $producto->total = $rp["total"];
            $productos[] = $producto;

            $subtotal = $subtotal + $rp["total"];
            $descuento_total =  $descuento_total + $rp["descuento"];
        }
        $dataProveedor = $proveedoresModel->obtenerPorId($data['id_proveedor']);

        if ($data["proveedor"]['iva'] == 1) {
            $iva_aplicado = ($subtotal) * .16;
            $total_global = $iva_aplicado + $subtotal;
        } else {
            $iva_aplicado = 0;
            $total_global = $subtotal;
        }


        $proveedor = [
            "contacto" => $dataProveedor->contacto,
            "proveedor" => $dataProveedor->proveedor,
            "direccion" => $dataProveedor->direccion,
            "telefono" => $dataProveedor->telefono
        ];

        //Generar HTML de la vista
        $data = [
            "proveedor" => $proveedor,
            "productos" => $productos,
            "subtotal" => $subtotal,
            "descuento_total" => $descuento_total,
            "iva_aplicado" => $iva_aplicado,
            "total_global" => $total_global
        ];

        $html = view('orden_compra/pdf_orden_compra', $data);

        // Configurar Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // ⚠️ Importante: Asegurar que se envía el PDF correctamente
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename='orden_compra.pdf'"); // Inline para ver en el navegador
        echo $dompdf->output();
        exit();
    }


    public function validar()
    {
        // Cargar los helpers necesarios
        helper('xml');

        // Obtener el archivo XML desde el input
        $archivo = $this->request->getFile('xml');

        // Verificar que el archivo sea válido
        if (!$archivo->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => '❌ Error al cargar el archivo. No es válido.'
            ]);
        }

        // Mover el archivo XML a un directorio temporal
        $nombreArchivo = $archivo->getRandomName();
        $rutaXML = WRITEPATH . 'uploads/' . $nombreArchivo;
        $archivo->move(WRITEPATH . 'uploads', $nombreArchivo);

        // Ruta de los XSDs
        $rutaXSD = APPPATH . 'ThirdParty/cfdv40.xsd';
        $rutaXSDTimbre = APPPATH . 'ThirdParty/TimbreFiscalDigital.xsd'; // XSD de Timbre Fiscal Digital


        // Validar el XML con ambos XSDs
        $resultado = validarXMLConXSD($rutaXML, $rutaXSD, $rutaXSDTimbre);

        // Retornar la respuesta en formato JSON
        return $this->response->setJSON([
            'status' => $resultado['status'] ? 'success' : 'error',
            'message' => $resultado['mensaje']
        ]);
    }
}
