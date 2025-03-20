<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Login extends BaseController
{

    public function index()
    {

        return view('login/login');

    }

    public function validar()
    {
        // Get username and password from POST data
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Load the UserModel
        $UsuariosModel = new UsuariosModel();

        // Call the buscarUsuario method to search for the user and verify the password
        $user = $UsuariosModel->buscarUsuario($username, $password);

        if ($user["correo"] == true && $user["password"] == true){

            session()->set('user_id', $user["id"]);
            session()->set('username', $username);

            return $this->response->setJSON(['status' => 'success']);

        }
        if ($user["correo"] == false && $user["password"] == false){
            
            return $this->response->setJSON(['status' => 'correo_invalido']);

        }
        if ($user["correo"] == true && $user["password"] == false){
            
            return $this->response->setJSON(['status' => 'password_invalido']);

        }
    }

}
