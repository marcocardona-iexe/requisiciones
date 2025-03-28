<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdenCompraModel extends Model
{
    protected $table = 'ordenes_compra'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Llave primaria
    protected $allowedFields = [
        'id_venta',
        'id_proveedor',
        'iva_aplicado',
        'codigo',
        'iva',
        'subtotal',
        'total',
        'descuento'
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

    public function obtenerUltimoCodigoVenta()
    {
        // Consulta para obtener el último código de la tabla de ventas
        $resultado = $this->select('codigo')
            ->orderBy('id', 'DESC') // Ordenar por ID descendente para obtener el último registro
            ->limit(1)
            ->get()
            ->getRow();

        return $resultado ? $resultado->codigo : null; // Retorna el código o null si no hay registros
    }
}
