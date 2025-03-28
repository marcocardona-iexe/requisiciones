<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedoresModel extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigo',
        'proveedor',
        'vende',
        'rfc',
        'codigo_postal',
        'pais',
        'telefono',
        'correo',
        'contacto',
        'telefono_contacto',
        'correo_contacto',
        'banco',
        'cuenta',
        'clabe',
        'status',
        'direccion'
    ];
    protected $useTimestamps = true;
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

    public function obtenerTodasCategorias()
    {
        // Usando el Query Builder para la tabla categorias
        $builder = $this->db->table('categorias');

        // Seleccionar la columna 'categoria'
        $builder->select('id, categoria');

        // Ejecutar la consulta
        $query = $builder->get();

        // Obtener todos los resultados como un array de objetos
        $result = $query->getResult();

        return $result;
    }

    public function guardar($proveedor)
    {

        $data = [
            'proveedor'        => $proveedor->proveedor,
            'vende'            => $proveedor->vende,
            'rfc'              => $proveedor->rfc,
            'codigo_postal'    => $proveedor->codigo_postal,
            'pais'             => $proveedor->pais,
            'telefono'         => $proveedor->telefono,
            'correo'           => $proveedor->correo,
            'contacto'         => $proveedor->contacto,
            'telefono_contacto' => $proveedor->telefono_contacto,
            'correo_contacto'  => $proveedor->correo_contacto,
            'banco'            => $proveedor->banco,
            'cuenta'           => $proveedor->cuenta,
            'clabe'            => $proveedor->clabe,
            'status'           => $proveedor->status
        ];

        return $this->insert($data);
    }

    public function obtenerTodos()
    {
        return $this->findAll();
    }
}
