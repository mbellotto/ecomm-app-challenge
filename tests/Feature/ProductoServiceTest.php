<?php

namespace Tests\Feature;

use App\Services\ProductoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductoServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        File::shouldReceive('exists')
            ->with(storage_path(env('PRODUCTOS_FILE_LOCATION')))
            ->andReturn(true);
        File::shouldReceive('get')
            ->with(storage_path(env('PRODUCTOS_FILE_LOCATION')))
            ->andReturn(json_encode([
                ['id' => 1, 'title' => 'Producto 1', 'price' => 100, 'created_at' => '2023-01-01 00:00:00'],
                ['id' => 2, 'title' => 'Producto 2', 'price' => 200, 'created_at' => '2023-01-02 00:00:00'],
                ['id' => 3, 'title' => 'Prueba', 'price' => 300, 'created_at' => '2023-01-03 00:00:00'],
            ]));
        File::shouldReceive('put')
            ->andReturn(true);
    }

    public function testFind()
    {
        $productoService = new ProductoService();
        $producto = $productoService->find('id', 3);
        $this->assertEquals('Prueba', $producto->title, "El título del producto es correcto.");
    }

    public function testFindNotFound()
    {
        $productoService = new ProductoService();
        $producto = $productoService->find('id', 13);
        $this->assertNull($producto, "El producto no existe.");
    }

    public function testCreate()
    {
        $productoService = new ProductoService();
        $id = $productoService->create( ['title' => 'Nuevo Producto', 'price' => 300]);
        $producto = $productoService->find('id', $id);
        $this->assertEquals('Nuevo Producto', $producto['title'], "Producto creado correctamente");
    }

    public function testUpdate()
    {
        $productoService = new ProductoService();
        $productoService->update(1, ['title' => 'Producto Actualizado', 'price' => 150]);
        $producto = $productoService->find('id', 1);
        $this->assertEquals('Producto Actualizado', $producto['title'], "El título del producto debería haberse actualizado.");
    }

    public function testDelete()
    {
        $productoService = new ProductoService();
        $id = $productoService->delete(3);
        $this->assertEquals(3, $id, "Producto eliminado.");
        $producto = $productoService->find('id', $id);
        $this->assertNull($producto, 'No se puedo encontrar el producto.');
    }
}
