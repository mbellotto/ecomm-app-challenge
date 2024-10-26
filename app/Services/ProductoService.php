<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ProductoService {

    private $filePath;

    private $productos;

    public function __construct()
    {
        $this->filePath = storage_path(env('PRODUCTOS_FILE_LOCATION'));
        $this->loadProductos();
    }

    private function loadProductos()
    {
        if (!File::exists($this->filePath)) {
            // Presentar mensaje indicando que el archivo no existe
            // crear un archivo vacio
            $this->productos = [];
            $this->saveProductos($this->productos);
        } else {
            $this->productos = json_decode(File::get($this->filePath));
        }
    }

    private function saveProducto($productos)
    {
        File::put($this->filePath, json_encode($productos));
    }


    public function getAll()
    {
        return $this->productos;
    }

    public function find($key, $value)
    {
    }

    public function create(array $record)
    {
    }

    public function update($id, array $record)
    {
    }

    public function delete($id)
    {
    }
}
