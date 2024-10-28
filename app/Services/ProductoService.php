<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ProductoService {

    private $filePath;

    private $productos;

    private $nextId;

    public function __construct()
    {
        $this->filePath = storage_path(env('PRODUCTOS_FILE_LOCATION'));
        $this->loadProductos();
        $this->getLastId();
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

    private function saveProductos($productos)
    {
        File::put($this->filePath, json_encode($productos));
    }

    private function getLastId() {
        $lastRecord = $this->productos[count($this->productos) - 1];
        $this->nextId = $lastRecord->id + 1;
    }

    private function getNextId()
    {
        $id = $this->nextId;
        $this->nextId = $this->nextId + 1;
        return $id;
    }

    public function getAll($searchField=null, $search=null)
    {
        if ($searchField and $search) {
            $productos =collect($this->productos)->filter(function ($producto) use ($searchField,$search) {
                return stripos((string)$producto[$searchField], $search) !== false;
            });
        } else {
            $productos =$this->productos;
        }

        return $productos;
    }

    public function find($key, $value)
    {
        return collect($this->productos)->firstWhere($key, $value);
    }

    public function create(array $record)
    {
        $id = $this->getNextId();
        $record["id"] = $id;
        $record["created_at"] = date("Y-m-d H:i:s");

        $this->productos[] = $record;
        $this->saveProductos($this->productos);

        return $id;
    }

    public function update($id, array $record)
    {
        $record["created_at"] = date("Y-m-d H:i:s");

        foreach ($this->productos as &$producto) {
            if ($producto->id == $id) {
                $producto = array_merge((array)$producto, $record);
                break;
            }
        }
        $this->saveProductos($this->productos);
    }

    public function delete($id)
    {
        $productos = array_filter($this->productos, fn($producto) => $producto->id != $id);

        $this->productos = array_values($productos);

        /*
        foreach ($this->productos as &$producto) {
            if ($producto->id == $id) {
                $producto = null;
                break;
            }
        }
        */

        $this->saveProductos($this->productos);

        return $id;
    }
}
