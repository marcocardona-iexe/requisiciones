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
        $data = $this->request->getPost();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // $dataProveedor = $proveedoresModel->obtenerPorId($data['id_proveedor']);
        // echo "<pre>";
        // print_r($dataProveedor);
        // echo "</pre>";

        // $data = [
        //     "proveedor" => $dataProveedor->proveedor
        // ];
        // die;
        // Generar HTML de la vista
        $html = view('orden_compra/pdf_orden_compra');

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
}
