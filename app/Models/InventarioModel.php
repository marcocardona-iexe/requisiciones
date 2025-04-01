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


    /**
     * Obtener el inventario con paginación y búsqueda.
     * 
     * @param int $limit Número de registros por página.
     * @param int $start Índice de inicio para la paginación.
     * @param string $search Término de búsqueda.
     * @param string $order_column Columna para ordenar.
     * @param string $order_dir Dirección del ordenamiento (ASC o DESC).
     * @param string $categoria ID de la categoría.
     * @param string $area ID del área.
     * @return array Lista de registros filtrados y ordenados.
     */
    // Obtener el inventario con paginación y búsqueda.
    public function getInventario($limit, $start, $search = '', $order_column = 'inventario.id', $order_dir = 'ASC', $categoria = '', $area = '')
    {
        $builder = $this->db->table('inventario')
            ->select('
                inventario.id,
                inventario_detalles.id_variante,
                inventario.nombre,
                GROUP_CONCAT(CONCAT(inventario_detalles.atributo, ": ", inventario_detalles.valor) 
                ORDER BY inventario_detalles.atributo SEPARATOR ", ") AS caracteristicas,
                categorias.id AS id_categoria,
                categorias.categoria AS categoria_nombre,
                areas.id AS id_area,
                areas.area AS area_nombre,
                inventario_detalles.stock,
                inventario_detalles.stock_minimo
            ')
            ->join('inventario_detalles', 'inventario.id = inventario_detalles.id_inventario', 'inner')
            ->join('categorias', 'inventario.id_categoria = categorias.id', 'inner')
            ->join('areas', 'inventario.id_area = areas.id', 'inner')
            ->groupBy('inventario_detalles.id_variante');

        // Aplicar búsqueda
        if (!empty($search)) {
            $builder->groupStart()
                ->like('inventario.nombre', $search)
                ->orLike('categorias.categoria', $search)
                ->orLike('area.area', $search)
                ->groupEnd();
        }

        // Filtros por categoría y área (Se filtra por ID)
        if (!empty($categoria)) {
            $builder->where('inventario.id_categoria', $categoria);
        }
        if (!empty($area)) {
            $builder->where('inventario.id_area', $area);
        }

        // Aplicar ordenamiento y paginación
        $builder->orderBy($order_column, $order_dir)
            ->limit($limit, $start);

        return $builder->get()->getResultArray();
    }

    /**
     * Obtener el total de registros en la tabla inventario.
     * 
     * @param string $search Término de búsqueda.
     * @param string $categoria ID de la categoría.
     * @param string $area ID del área.
     * @return int Total de registros.
     */
    // Obtener el total de registros en la tabla inventario.
    public function getTotalInventario($search = '', $categoria = '', $area = '')
    {
        $builder = $this->db->table('inventario')
            ->select('COUNT(DISTINCT inventario.id) as total')
            ->join('inventario_detalles', 'inventario.id = inventario_detalles.id_inventario', 'inner')
            ->join('categorias', 'inventario.id_categoria = categorias.id', 'inner')
            ->join('areas', 'inventario.id_area = areas.id', 'inner');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('inventario.nombre', $search)
                ->orLike('categorias.categoria', $search)
                ->orLike('areas.area', $search)
                ->groupEnd();
        }

        if (!empty($categoria)) {
            $builder->where('inventario.id_categoria', $categoria);
        }
        if (!empty($area)) {
            $builder->where('inventario.id_area', $area);
        }

        return $builder->get()->getRow()->total;
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


    /**
     * Búsqueda para Select2 con AJAX.
     * 
     * @param string $q Término de búsqueda ingresado por el usuario.
     * @return array Lista de resultados coincidentes para el término de búsqueda.
     */

    public function buscar_inventario($q)
    {
        // Utilizando el Query Builder para realizar la consulta
        $builder = $this->db->table('inventario_detalles d'); // Seleccionamos la tabla 'inventario_detalles' con alias 'd'

        // Seleccionamos las columnas que queremos en la consulta
        $builder->select('d.id_variante AS id');
        $builder->select('GROUP_CONCAT(CONCAT(i.nombre, " - ", d.atributo, ": ", d.valor) SEPARATOR ", ") AS caracteristicas');

        // Realizamos el JOIN con la tabla 'inventario' usando el alias 'i'
        $builder->join('inventario i', 'd.id_inventario = i.id');

        // Agrupamos por la columna 'd.id_inventario'
        $builder->groupBy('d.id_variante');

        // Aplicamos la condición HAVING con LIKE para filtrar los resultados
        $builder->having('GROUP_CONCAT(CONCAT(i.nombre, " - ", d.atributo, ": ", d.valor) SEPARATOR ", ") LIKE', '%' . $q . '%');

        // Ejecutamos la consulta
        $query = $builder->get();

        // Devolvemos los resultados de la consulta
        return $query->getResult(); // Devuelve un arreglo de resultados
    }
}
