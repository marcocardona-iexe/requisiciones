<?php

namespace App\Models;

use CodeIgniter\Model;

class RequisicionesModel extends Model
{
    protected $table = 'requisiciones'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Llave primaria de la tabla
    protected $allowedFields = [
        'id_usuario',
        'justificacion',
        'id_estatus',
        'comentario_estatus',
        'fecha_entregado',
        'fecha_entrega'
    ]; // Campos permitidos para operaciones de inserción y actualización
    protected $useTimestamps = true; // Utilizar campos de timestamp automáticos
    protected $returnType = 'object';



    /**
     * Buscar un tipo de beneficiario por su ID.
     *
     * @param int $id ID del tipo de beneficiario.
     * @return object|null Tipo de beneficiario encontrado o null si no existe.
     */
    public function obtenerPorId(int $id)
    {
        return $this->find($id); // Busca un registro por ID
    }


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



    public function ObtenerRequisicionesDataTable($search, $order_column, $order_dir, $start, $length)
    {
        $query = $this->db->table($this->table);

        // Búsqueda
        if (!empty($search)) {
            $query->groupStart()
                ->like('id_usuario', $search)
                ->orLike('justificacion', $search)
                ->orLike('id_estatus', $search)
                ->orLike('comentario_estatus', $search)
                ->orLike('fecha_entregado', $search)
                ->orLike('fecha_entrega', $search)
                ->groupEnd();
        }

        // Ordenación
        if (!empty($order_column)) {
            $query->orderBy($order_column, $order_dir);
        }

        // Paginación
        $query->limit($length, $start);

        return $query->get()->getResultArray();
    }

    public function TotalRecordsDataTable()
    {
        return $this->countAll();
    }

    public function TotalFilteredRecordsDataTable($search)
    {
        $query = $this->db->table($this->table);

        if (!empty($search)) {
            $query->groupStart()
                ->like('id_usuario', $search)
                ->orLike('justificacion', $search)
                ->orLike('id_estatus', $search)
                ->orLike('comentario_estatus', $search)
                ->orLike('fecha_entregado', $search)
                ->orLike('fecha_entrega', $search)
                ->groupEnd();
        }

        return $query->countAllResults();
    }
}
