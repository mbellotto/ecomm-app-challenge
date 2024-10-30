<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductoControllerTest extends TestCase
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
    }


    public function testShow()
    {
        $mock = \Mockery::mock('App\Services\ProductoService');
        $mock->shouldReceive('find')
            ->with('id', 1)
            ->andReturn((object) ['id' => 1, 'title' => 'Producto 1', 'price' => 100]);

        $this->app->instance('App\Services\ProductoService', $mock);

        $response = $this->get('/productos/1');
        $response->assertStatus(200);
        $response->assertJson(['id' => 1, 'title' => 'Producto 1', 'price' => 100]);
    }

    public function testStore()
    {
        $mock = \Mockery::mock('App\Services\ProductoService');
        $mock->shouldReceive('create')
            ->andReturn(1);

        $this->app->instance('App\Services\ProductoService', $mock);

        $response = $this->post('/productos', [
            'title' => 'Nuevo Producto',
            'price' => 100,
        ]);

        $response->assertStatus(302);
    }

    public function testUpdate()
    {
        $mock = \Mockery::mock('App\Services\ProductoService');
        $mock->shouldReceive('update')
            ->with(1, [
                'id' => 1,
                'title' => 'Producto Actualizado',
                'price' => 150,
            ]);

        $this->app->instance('App\Services\ProductoService', $mock);

        $response = $this->put('/productos/1', [
            'id' => 1,
            'title' => 'Producto Actualizado',
            'price' => 150,
        ]);

        $response->assertStatus(302);
    }

    public function testDestroy()
    {
        $mock = \Mockery::mock('App\Services\ProductoService');
        $mock->shouldReceive('delete')
            ->with(1)
            ->andReturn(1);

        $this->app->instance('App\Services\ProductoService', $mock);

        $response = $this->delete('/productos/1');
        $response->assertStatus(302);
    }
}
