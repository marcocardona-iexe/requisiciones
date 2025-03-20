<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'username',
        'password',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    public function buscarUsuario($username, $password)
    {
        // Usando el Query Builder para la tabla de usuarios
        $builder = $this->builder();
        
        // Seleccionar las columnas necesarias (por ejemplo, id y password)
        $builder->select('id, username, password'); // Asegúrate de que el nombre de la columna sea correcto

        // Filtro por el nombre de usuario
        $builder->where('username', $username);
        
        // Ejecutar la consulta
        $query = $builder->get();
        
        // Verificar si se encontró un usuario
        $result = $query->getRow();
        
        // Si no se encontró el usuario
        if (!$result) {

            $data = [
                'correo' => false,
                'password' => false
            ];

            return $data;  // Retorna null si el usuario no existe
        }
        
        // Verificar si la contraseña es correcta
        if (password_verify($password, $result->password)) {

            $data = [
                'id' => $result->id,
                'correo' => true,
                'password' => true
            ];

            return $data;

        } else {

            $data = [
                'correo' => true,
                'password' => false
            ];

            return $data;
        }
    }

}
