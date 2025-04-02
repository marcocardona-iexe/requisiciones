<?php

namespace App\Controllers;

use App\Models\CategoriasModel;

class CategoriasController extends BaseController
{
    protected $categoriasModel;

    public function __construct()
    {
        $this->categoriasModel = new CategoriasModel();
    }

    public function obtener()
    {
        // Llamar al método obtenerTodos del modelo
        $datos = $this->categoriasModel->obtenerTodos();

        // Devolver los datos como respuesta JSON usando $this->response->setJSON
        return $this->response->setJSON($datos);
    }
}
