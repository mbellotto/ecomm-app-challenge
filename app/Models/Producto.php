<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Producto
{
    private $filePath;

    public function __construct()
    {
        $this->filePath = storage_path(env('PRODUCTOS_FILE_LOCATION'));
    }

    public function load()
    {
        if (!File::exists($this->filePath)) {
            $productos = [];
            $this->save($productos);
        } else {
            $productos = json_decode(File::get($this->filePath));
        }

        return $productos;
    }

    public function save($productos)
    {
        File::put($this->filePath, json_encode($productos));
    }
}
