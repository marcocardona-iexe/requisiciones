<?php

namespace App\Controllers;

use App\Models\AreasModel;

class AreasController extends BaseController
{
    protected $areasModel;

    public function __construct()
    {
        $this->areasModel = new AreasModel();
    }

    public function obtener()
    {
        // Llamar al método obtenerTodos del modelo
        $datos = $this->areasModel->obtenerTodos();

        // Devolver los datos como respuesta JSON usando $this->response->setJSON
        return $this->response->setJSON($datos);
    }
}
