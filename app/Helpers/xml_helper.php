<?php
if (!function_exists('validarXMLConXSD')) {
    function validarXMLConXSD($rutaXML, $rutaXSD, $rutaXSDTimbre)
    {
        libxml_use_internal_errors(true);

        // Verificar archivos
        if (!file_exists($rutaXML) || !file_exists($rutaXSD) || !file_exists($rutaXSDTimbre)) {
            return ['status' => false, 'mensaje' => "❌ Archivo XML o XSD no encontrado."];
        }

        // Cargar el XML completo
        $xml = new DOMDocument();
        if (!$xml->load($rutaXML)) {
            return ['status' => false, 'mensaje' => "❌ Error al cargar el XML."];
        }

        // 🔹 1️⃣ Remover el nodo <TimbreFiscalDigital> antes de validar con cfdv40.xsd
        $xmlSinTimbre = clonarSinTimbreFiscal($xml);
        if (!$xmlSinTimbre->schemaValidate($rutaXSD)) {
            return ['status' => false, 'mensaje' => generarMensajeError("CFDI", libxml_get_errors())];
        }

        // 🔹 2️⃣ Validar el Timbre Fiscal Digital por separado
        $xpath = new DOMXPath($xml);
        $xpath->registerNamespace("cfdi", "http://www.sat.gob.mx/cfd/4");
        $xpath->registerNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");

        $nodoTimbre = $xpath->query("//cfdi:Complemento/tfd:TimbreFiscalDigital")->item(0);

        if (!$nodoTimbre) {
            return ['status' => false, 'mensaje' => "❌ No se encontró el Timbre Fiscal Digital en el XML."];
        }

        // 🔹 3️⃣ Validar solo el nodo de Timbre Fiscal
        $xmlTimbre = new DOMDocument();
        $xmlTimbre->appendChild($xmlTimbre->importNode($nodoTimbre, true));

        if (!$xmlTimbre->schemaValidate($rutaXSDTimbre)) {
            return ['status' => false, 'mensaje' => generarMensajeError("Timbre Fiscal", libxml_get_errors())];
        }

        return ['status' => true, 'mensaje' => "✅ XML válido según el esquema SAT y el Timbre Fiscal Digital."];
    }

    function generarMensajeError($tipo, $errores)
    {
        $mensaje = "❌ XML NO válido. Errores en el esquema $tipo:\n";
        foreach ($errores as $error) {
            $mensaje .= "Línea {$error->line}: {$error->message}\n";
        }
        libxml_clear_errors();
        return $mensaje;
    }

    function clonarSinTimbreFiscal($xml)
    {
        $nuevoXml = clone $xml;
        $xpath = new DOMXPath($nuevoXml);
        $xpath->registerNamespace("cfdi", "http://www.sat.gob.mx/cfd/4");
        $xpath->registerNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");

        $nodoTimbre = $xpath->query("//cfdi:Complemento/tfd:TimbreFiscalDigital")->item(0);
        if ($nodoTimbre) {
            $nodoTimbre->parentNode->removeChild($nodoTimbre);
        }

        return $nuevoXml;
    }
}
