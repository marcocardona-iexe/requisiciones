<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarioProveedoresModel extends Model
{
    protected $table = 'inventario_proveedores'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Llave primaria de la tabla
    protected $allowedFields = [
        'id_proveedor',
        'id_inventario',
        'precio'

    ]; // Campos permitidos para operaciones de inserción y actualización
    protected $useTimestamps = true; // Utilizar campos de timestamp automáticos
    protected $returnType = 'object';

    /**
     * Insertar un nuevo usuario.
     * 
     * @param array $data Datos del usuario a insertar.
     * @return bool|int Retorna false en caso de error o el ID del usuario insertado.
     */
    public function insertar(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Obtener todos los registros de la tabla, ordenados por la fecha de creación más reciente.
     *
     * @return object Lista de registros.
     */
    public function obtenerTodos()
    {
        return $this->orderBy('created_at', 'DESC')->findAll(); // Ordena por created_at en orden descendente
    }

    /**
     * Obtener usuarios según una condición.
     * 
     * @param array $where Condición para filtrar usuarios.
     * @return array|null Lista de usuarios o null si no hay coincidencias.
     */
    public function obtenerPorWhere(array $where)
    {
        return $this->where($where)->findAll();
    }


    /**
     * Editar usuarios según una condición.
     * 
     * @param array $where Condición para filtrar usuarios a actualizar.
     * @param array $data Datos a actualizar.
     * @return bool Retorna true si se actualizaron registros, false en caso contrario.
     */
    public function editarPorWhere(array $where, array $data)
    {
        return $this->where($where)->set($data)->update();
    }


    public function obtenerProveedoresPorInventario($idInventario)
    {
        return $this->select('inventario_proveedores.precio, proveedores.proveedor')
            ->join('proveedores', 'inventario_proveedores.id_proveedor = proveedores.id')
            ->where('inventario_proveedores.id_inventario', $idInventario)
            ->findAll();
    }
}
