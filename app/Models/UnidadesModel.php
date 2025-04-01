<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo para manejar la tabla "areas"
class UnidadesModel extends Model
{
    // Nombre de la tabla asociada en la base de datos
    protected $table = 'unidades';

    // Nombre de la llave primaria de la tabla
    protected $primaryKey = 'id';

    // Campos permitidos para inserción y actualización
    protected $allowedFields = [
        'unidades',     // Nombre del área (debe coincidir con la columna en la base de datos)
        'descripcion' // Descripción del área (debe coincidir con la columna en la base de datos)
    ];

    // Habilitar el uso de timestamps (created_at y updated_at)
    protected $useTimestamps = true;

    // Tipo de retorno de los resultados (en este caso, objetos)
    protected $returnType = 'object';

    /**
     * Obtener un registro por su ID
     *
     * @param int $id ID del registro a buscar
     * @return object|null Retorna el registro encontrado o null si no existe
     */
    public function obtenerPorId(int $id)
    {
        return $this->find($id); // Busca un registro por su ID
    }

    /**
     * Obtener registros que cumplan con una condición
     *
     * @param array $where Condición para filtrar los registros
     * @return array Retorna un arreglo de objetos con los registros encontrados
     */
    public function obtenerPorWhere(array $where)
    {
        return $this->where($where)->findAll(); // Busca registros que cumplan con la condición
    }

    /**
     * Editar un registro por su ID
     *
     * @param int $id ID del registro a actualizar
     * @param array $data Datos a actualizar
     * @return bool Retorna true si la actualización fue exitosa, false en caso contrario
     */
    public function editarPorId(int $id, array $data)
    {
        return $this->update($id, $data); // Actualiza un registro por su ID
    }

    /**
     * Editar registros que cumplan con una condición
     *
     * @param array $where Condición para filtrar los registros
     * @param array $data Datos a actualizar
     * @return bool Retorna true si la actualización fue exitosa, false en caso contrario
     */
    public function editarPorWhere(array $where, array $data)
    {
        return $this->update($where, $data); // Actualiza registros según una condición
    }

    /**
     * Eliminar un registro por su ID
     *
     * @param int $id ID del registro a eliminar
     * @return bool Retorna true si la eliminación fue exitosa, false en caso contrario
     */
    public function eliminarPorId(int $id)
    {
        return $this->delete($id); // Elimina un registro por su ID
    }

    /**
     * Eliminar registros que cumplan con una condición
     *
     * @param array $where Condición para filtrar los registros
     * @return bool Retorna true si la eliminación fue exitosa, false en caso contrario
     */
    public function eliminarPorWhere(array $where)
    {
        return $this->where($where)->delete(); // Elimina registros según una condición
    }

    /**
     * Obtener todos los registros de la tabla
     *
     * @return array Retorna un arreglo de objetos con todos los registros
     */
    public function obtenerTodos()
    {
        return $this->findAll(); // Obtiene todos los registros de la tabla
    }
}
