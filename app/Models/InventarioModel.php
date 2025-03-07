<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarioModel extends Model
{
    protected $table = 'inventario'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Llave primaria de la tabla
    protected $allowedFields = [
        'nombre',
        'id_categoria'

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


    public function obtenerInventarioDetallesDataTable($search, $orderColumn, $orderDir, $start, $length)
    {
        $builder = $this->db->table('inventario_detalles d')
            ->select('
                d.id_variante AS id_variante, 
                d.id_inventario AS id_inventario, 
                i.nombre AS nombre_general, 
                GROUP_CONCAT(CONCAT(d.atributo, ": ", d.valor) ORDER BY d.atributo SEPARATOR ", ") AS caracteristicas, 
                categorias.categoria,
                SUM(d.stock_individual) AS stock_total
            ')
            ->join('inventario i', 'd.id_inventario = i.id')
            ->join('categorias', 'i.id_categoria = categorias.id')
            ->groupBy('d.id_variante, d.id_inventario');

        // Filtro de búsqueda
        if (!empty($search)) {
            $builder->groupStart()
                ->like('i.nombre', $search)
                ->orLike('d.atributo', $search)
                ->orLike('d.valor', $search)
                ->orLike('categorias.categoria', $search)
                ->groupEnd();
        }

        // Ordenación
        if (!empty($orderColumn) && !empty($orderDir)) {
            $builder->orderBy($orderColumn, $orderDir);
        }

        // Paginación
        if ($length > 0) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }

    public function contarFilasTotalesDataTable()
    {
        return $this->db->table('inventario_detalles')->countAll();
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

    public function contarFilasFiltradasDataTable($search)
    {
        $builder = $this->db->table('inventario_detalles d')
            ->join('inventario i', 'd.id_inventario = i.id')
            ->join('categorias', 'i.id_categoria = categorias.id');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('i.nombre', $search)
                ->orLike('d.atributo', $search)
                ->orLike('d.valor', $search)
                ->orLike('categorias.categoria', $search)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }
}
