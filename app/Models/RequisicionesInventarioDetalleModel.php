<?php

namespace App\Models;

use CodeIgniter\Model;

class RequisicionesInventarioDetalleModel extends Model
{
    protected $table = 'requisiciones_inventario_detalle'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Llave primaria de la tabla
    protected $allowedFields = [
        'id_requisicion',
        'cantidad',
        'id_variante',
        'validado'
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


    public function obtenerDetallesRequisicion($idRequisicion)
    {
        return $this->db->table('requisiciones_inventario_detalle')
            ->select("
                requisiciones_inventario_detalle.id,
                requisiciones_inventario_detalle.id_variante,
                requisiciones_inventario_detalle.cantidad,
                requisiciones_inventario_detalle.validado,
                inventario_detalles.stock_individual,
                inventario.nombre,
                GROUP_CONCAT(DISTINCT CONCAT(inventario_detalles.atributo, ': ', inventario_detalles.valor) SEPARATOR ', ') AS detalles
            ")
            ->join('inventario_detalles', 'requisiciones_inventario_detalle.id_variante = inventario_detalles.id_variante', 'inner')
            ->join('inventario', 'inventario_detalles.id_inventario = inventario.id', 'inner')
            ->where('requisiciones_inventario_detalle.id_requisicion', $idRequisicion)
            ->groupBy('inventario_detalles.id_inventario')
            ->get()
            ->getResultArray(); // Retorna un array asociativo
    }
}
