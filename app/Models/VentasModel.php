<?php

namespace App\Models;

use CodeIgniter\Model;

class VentasModel extends Model
{
    protected $table = 'ventas'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Llave primaria

    // Campos permitidos para inserción y actualización
    protected $allowedFields = [
        'fecha',
        'codigo',
        'iva_aplicado',
        'iva',
        'subtotal',
        'total',
        'descuento',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $returnType = 'object';

    // Métodos principales
    /**
     * Insertar una nueva venta
     *
     * @param array $data
     * @return bool|int
     */
    public function insertar($data)
    {
        return $this->insert($data);
    }

    /**
     * Obtener todas las ventas
     *
     * @return array
     */
    public function getAllVentas()
    {
        return $this->findAll();
    }

    /**
     * Obtener una venta por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getVentaById($id)
    {
        return $this->find($id);
    }

    /**
     * Crear una nueva venta
     *
     * @param array $data
     * @return bool|int
     */
    public function createVenta(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Actualizar una venta existente
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateVenta($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Eliminar una venta por su ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteVenta($id)
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
