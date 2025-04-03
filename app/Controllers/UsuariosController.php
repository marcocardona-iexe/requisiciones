<?php

namespace App\Controllers;

use App\Models\CategoriasModel;
use App\Models\UsuariosModel;

class UsuariosController extends BaseController
{
    protected $usuariosModel;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
    }

    public function lista()
    {
        
        $datajs = [
            'scripts' => [
                '/public/assets/system/js/usuarios/usuarios.js'
            ]
        ];

        $data = [
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js',$datajs),
        ];

        return view('usuarios/lista', $data);
    }

    public function obtenerUsuarios()
    {
        $dataUsuarios = $this->usuariosModel->obtenerTodos();        
        return $this->response->setJSON(['data' => $dataUsuarios]);
    }
}
