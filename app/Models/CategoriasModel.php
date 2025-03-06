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

    public function obtenerCategoria($idInventario)
    {
        // Usando el Query Builder para la tabla inventarios
        $builder = $this->db->table('inventario');
        
        // Seleccionar columnas necesarias
        $builder->select('categorias.categoria');
        
        // Realizar el JOIN con la tabla categorias
        $builder->join('categorias', 'inventario.id_categoria = categorias.id', 'left');
        
        // Filtro por ID de inventario
        $builder->where('inventario.id', $idInventario);
        
        // Ejecutar la consulta
        $query = $builder->get();
        
        // Obtener el resultado como objeto
        $result = $query->getRow();
    
        return $result;
    }
    
}
