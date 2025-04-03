<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'categoria',  // Debe coincidir con el nombre de la columna en la base de datos
        'status',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'object';

    
    public function obtenerPorId(int $id)
    {
        return $this->find($id); // Busca un registro por ID
    }

    public function obtenerPorWhere(array $where)
    {
        return $this->where($where)->findAll();
    }

    public function editarPorId(int $id, array $data)
    {
        return $this->update($id, $data); // Actualiza un registro por ID
    }

    public function editarPorWhere(array $where, array $data)
    {
        return $this->update($where, $data); // Actualiza registros según una condición
    }

    public function obtenerTodos()
    {
        return $this->findAll(); // Devuelve todos los registros de la tabla
    }

    public function obtenerCategoria($idInventario)
    {
        $response = $this->db->table('inventario')
                     ->select('id_categoria')
                     ->where('id', $idInventario)
                     ->get()
                     ->getRow();

        if ($response) { 
            $idCategoria = $response->id_categoria;

            $categoria = $this->db->table('categorias')
                                  ->where('id', $idCategoria)
                                  ->get()
                                  ->getResult();

            return $categoria[0]->categoria;
        }

    }
}
