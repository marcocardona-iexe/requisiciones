<?php

namespace App\Controllers;

require APPPATH . 'Libraries/ValidacionXSD.php';

class ValidacionXML extends BaseController
{
    public function index()
    {

        $band1 = 0;
        $band2 = 0;

        $archivo = $_FILES['archivo'];

        $xmlOrigen = $archivo['tmp_name'];
        $randomDate = date('YmdHis');
        $xmlDestino =  FCPATH . 'assets/destino/nodoTimbrado_'.$randomDate.'.xml';
        
        if (file_exists($xmlDestino)) {
            if (unlink($xmlDestino)) {
            } else {
            }
        }
        
        $origenXML = new \DOMDocument();
        if (!$origenXML->load($xmlOrigen)) {
            $response['status'] = 'Error';
            $response['message'] = 'No se pudo cargar el archivo XML origen.';
            echo json_encode($response);
            return;
        }
        
        $destinoXML = new \DOMDocument();
        $destinoXML->preserveWhiteSpace = false;
        $destinoXML->formatOutput = true;
        
        if (!file_exists($xmlDestino)) {
            $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<root></root>";
            file_put_contents($xmlDestino, $xmlContent);
        }
        
        if (!$destinoXML->load($xmlDestino)) {
            $response['status'] = 'Error';
            $response['message'] = 'No se pudo cargar el archivo XML destino.';
            echo json_encode($response);
            return;
        }
        
        $xpathOrigen = new \DOMXPath($origenXML);
        $xpathOrigen->registerNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
        
        $timbreNodos = $xpathOrigen->query("//tfd:TimbreFiscalDigital");
        
        if ($timbreNodos->length === 0) {
            $response['status'] = 'Error';
            $response['message'] = 'No se encontró nodo TimbreFiscalDigital en el XML origen';
            echo json_encode($response);
            return;
        } else {
            //echo "Nodo encontrado en el XML origen. \n";
        }
        
        $timbreOrigen = $timbreNodos->item(0);
        $timbreImportado = $destinoXML->importNode($timbreOrigen, true);
        $destinoXML->removeChild($destinoXML->documentElement);
        $destinoXML->appendChild($timbreImportado);
        $xmlContent = $destinoXML->saveXML();
        
        if (file_put_contents($xmlDestino, $xmlContent)) {
            //echo "XML destino modificado exitosamente. \n";
        } else {
            $response['status'] = 'Error';
            $response['message'] = 'Error al guardar el XML destino.';
            echo json_encode($response);
            return;
        }
        
        $valXSD = new \ValidacionXSD();
        if (!$valXSD->validar($xmlDestino, FCPATH . 'assets/destino/timbrado.xsd')) {
            $response['status'] = 'Error';
            $response['message'] = $valXSD->mostrarError();
            echo json_encode($response);
            return;
        } else {
            $band1 = 1;
        }
        
        $xmlString = file_get_contents($xmlOrigen);
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);
        
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $xpath->registerNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
        
        $timbre = $xpath->query('//cfdi:Complemento/tfd:TimbreFiscalDigital')->item(0);
        if ($timbre) {
            $timbreString = $dom->saveXML($timbre);
            $comment = $dom->createComment($timbreString);
            $timbre->parentNode->insertBefore($comment, $timbre);
            $timbre->parentNode->removeChild($timbre);
        }
        
        file_put_contents($xmlOrigen, $dom->saveXML());
        
        $valXSD = new \ValidacionXSD();
        if (!$valXSD->validar($xmlOrigen, FCPATH . 'assets/destino/factura.xsd')) {
            $response['status'] = 'Error';
            $response['message'] = $valXSD->mostrarError();
            echo json_encode($response);
            return;
        } else {
            $band2 = 1;
        }
        
        $total = "";
        $subtotal = "";
        $ValorUnitario = "";

        $xmlString = file_get_contents($xmlOrigen);
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlString);
        
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');

        $comprobante = $xpath->query('//cfdi:Comprobante')->item(0);

        if ($comprobante) {
            $total = $comprobante->getAttribute('Total');
            $subtotal = $comprobante->getAttribute('SubTotal');
        }

        $conceptos = [];
        $conceptoNodes = $xpath->query('//cfdi:Conceptos/cfdi:Concepto');

        foreach ($conceptoNodes as $concepto) {

            $impuestos = [];
            $impuestoNodes = $xpath->query('cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado', $concepto);
            foreach ($impuestoNodes as $impuesto) {
                $impuestos[] = [
                    'Importe' => $impuesto->getAttribute('Importe')
                ];
            }

            $conceptoData = [
                'ValorUnitario' => $concepto->getAttribute('ValorUnitario'),
                'ClaveUnidad' => $concepto->getAttribute('ClaveUnidad'),
                'Cantidad' => $concepto->getAttribute('Cantidad'),
                'Importe' => $concepto->getAttribute('Importe'),
                "impuestos" => $impuestos
            ];

            // Agregar los datos del concepto y sus impuestos al arreglo
            $conceptos[] = [
                'concepto' => $conceptoData,
            ];
        }

        $comments = $xpath->query('//comment()');
        foreach ($comments as $comment) {
            if (strpos($comment->nodeValue, '<tfd:TimbreFiscalDigital') !== false) {
                $parentNode = $comment->parentNode;
                $timbreDoc = new \DOMDocument();
                $timbreDoc->loadXML($comment->nodeValue);
                $timbreOriginal = $dom->importNode($timbreDoc->documentElement, true);
                $parentNode->insertBefore($timbreOriginal, $comment);
                $parentNode->removeChild($comment);
                break;
            }
        }
        
        file_put_contents($xmlOrigen, $dom->saveXML());
        
        if($band1 == 1 && $band2 == 1){
            $response['status'] = 'success';
            $response['total'] = $total;
            $response['subtotal'] = $subtotal;
            $response['conceptos'] = $conceptos;

            echo "<pre>";
            var_dump($response);
            echo "</pre>";

        }

    }

    public function vista()
    {
        return view('validacion_xml');
    }
}
