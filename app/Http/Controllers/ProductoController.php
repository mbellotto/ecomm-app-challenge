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
        $user = Session::get('user');

        $this->searchField = $request->get("searchField");
        $this->search =$request->get("search");
        $this->perPage = $request->get("perPage") ? $request->get("perPage") : 10;

        $searchField = $this->searchField;
        $search = $this->search;
        $perPage = $this->perPage;
        $options = $this->options;
        $level = $user['level'];

        if ($this->searchField and $this->search) {
            $productos = $this->productoService->getAll($this->searchField,$this->search, $perPage);
        }else {
            $productos = $this->productoService->getAll();
        }
        return view('productos.index', compact('productos', 'searchField', 'search', 'options', 'level'));
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

        $request->validate([
            "title" => "required|string|max:128",
            "price" => "required|numeric",
        ]);

        $validatedProduct = [
            "title" => strip_tags($request->input("title")),
            "price" => strip_tags($request->input("price")),
        ];



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

        $request->validate([
            "title" => "required|string|max:128",
            "price" => "required|numeric",
        ]);

        $validatedProducto = array(
            "id" => strip_tags($request->input('id')),
            "title" => strip_tags($request->input('title')),
            "price" => strip_tags($request->input('price'))
        );

        $this->productoService->update($id, $validatedProducto);

        Log::channel('productos')->info('PUT /productos with ID={id}', ['id' => $id]);
    }

    public function destroy($id)
    {
        Log::channel('productos')->info('DELETE /productos with ID={id}', ['id' => $id]);

        return $this->productoService->delete($id);
    }

}
