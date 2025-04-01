<?php

namespace App\Controllers;

use App\Models\RequisicionesInventarioDetalleModel;
use App\Models\RequisicionesModel;



class RequisicionesInventarioDetalleController extends BaseController
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

        return view('template/lista', $data);
    }




}
