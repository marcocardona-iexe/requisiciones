<?php

namespace App\Controllers;

use App\Models\InventarioModel;
use App\Models\CategoriasModel;



class ProveedoresController extends BaseController
{

    public function lista()
    {

        $data = [
            'menu' => view('layouts/menu'),
            'head' => view('layouts/head'),
            'nav' => view('layouts/nav'),
            'footer' => view('layouts/footer'),
            'js' => view('layouts/js'),
        ];

        return view('proveedores/lista', $data);
    }
}
