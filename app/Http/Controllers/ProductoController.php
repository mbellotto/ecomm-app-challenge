<?php

namespace App\Http\Controllers;

use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductoController extends Controller
{

    private ProductoService $productoService;

    private $searchField = 'title';
    private $search;
    private $perPage;

    private $options = [
        'title' => 'Titulo',
        'price' => 'Precio',
        'created_at' => 'Creación',
    ];
    private $defaultOption = 'title';

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    public function index(Request $request)
    {

        $role = Session::get('user')->role;

        $this->searchField = $request->get("searchField");
        $this->search =$request->get("search");
        $this->perPage = $request->get("perPage") ? $request->get("perPage") : 10;

        $searchField = $this->searchField;
        $search = $this->search;
        $perPage = $this->perPage;
        $options = $this->options;

        if ($this->searchField and $this->search) {
            $productos = $this->productoService->getAll($this->searchField,$this->search, $perPage);
        }else {
            $productos = $this->productoService->getAll();
        }
        return view('productos.index', compact('productos', 'searchField', 'search', 'options', 'role'));
    }

    public function create()
    {
    }

    public function show($id)
    {
        Log::channel('productos')->info('GET /productos with ID={id}', ['id' => $id]);

        return $this->productoService->find('id', $id);
    }

    public function store(Request $request)
    {

        $validatedProduct = $request->validate([
            "title" => "required|string|max:128",
            "price" => "required|numeric",
        ]);

        $id = $this->productoService->create($validatedProduct);

        Log::channel('productos')->info('POST /productos with ID={id}', ['id' => $id]);

        return $id;
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $id = $request->input('id');

        $producto = array(
            "id" => $request->input('id'),
            "title" => $request->input('title'),
            "price" => $request->input('price')
        );

        $this->productoService->update($id, $producto);

        Log::channel('productos')->info('PUT /productos with ID={id}', ['id' => $id]);
    }

    public function destroy($id)
    {
        Log::channel('productos')->info('DELETE /productos with ID={id}', ['id' => $id]);

        return $this->productoService->delete($id);
    }

}
