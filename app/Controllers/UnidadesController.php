<?php

namespace App\Controllers;

use App\Models\UnidadesModel;

class UnidadesController extends BaseController
{
    protected $unidadesModel;

    public function __construct()
    {
        $this->unidadesModel = new UnidadesModel();
    }

    public function obtener()
    {
        // Llamar al método obtenerTodos del modelo
        $datos = $this->unidadesModel->obtenerTodos();

        // Devolver los datos como respuesta JSON usando $this->response->setJSON
        return $this->response->setJSON($datos);
    }
}
