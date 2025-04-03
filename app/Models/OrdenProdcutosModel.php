<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdenProdcutosModel extends Model
{
    protected $table = 'ordenes_productos'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Llave primaria
    protected $allowedFields = [
        'id_orden',
        'id_requisicion_inventario',
        'cantidad',
        'precio_unitario',
        'descuento',
        'total'
    ]; // Campos permitidos para inserción/actualización
    protected $returnType = 'object'; // Tipo de retorno (objeto)
    protected $useTimestamps = true; // Habilitar timestamps (created_at, updated_at)

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

    public function obtenerPorWhere($where)
    {
        return $this->where($where)->findAll();
    }
}
