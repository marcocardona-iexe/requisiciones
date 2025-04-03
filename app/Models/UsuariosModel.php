<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre',
        'username',
        'password'
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    // Método para obtener todas las órdenes de compra
    public function obtenerTodos()
    {
        return $this->findAll();
    }

    // Método para obtener una orden de compra por su ID
    public function obtenerPorId($id)
    {
        return $this->find($id);
    }

    // Método para insertar una nueva orden de compra y devolver el ID insertado
    public function insertar($data)
    {
        $this->insert($data);
        return $this->insertID(); // Devuelve el ID de la última inserción
    }

    // Método para actualizar una orden de compra por su ID
    public function actualizar($id, $data)
    {
        return $this->update($id, $data);
    }

    // Método para eliminar una orden de compra por su ID
    public function eliminar($id)
    {
        return $this->delete($id);
    }

    /**
    Obtener ventas por condición WHERE
     *
     * @param array $where
     * @return array|null
     */
    public function obtenerPorWhere($where)
    {
        // Consulta para obtener registros según la condición WHERE
        return $this->where($where)->findAll();
    }


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
