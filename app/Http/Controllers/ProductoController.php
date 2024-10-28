<?php

namespace App\Http\Controllers;

use App\Services\ProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{

    private ProductoService $productoService;

    public function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
    }

    public function index(Request $request)
    {

        $searchField = $request->get("searchField");
        $search =$request->get("search");

        if ($searchField and $search) {
        // if($request->ajax()) {
            $productos = $this->productoService->getAll([
                "searchField" => $request->get("searchField"),
                "search" => $request->get("search")
            ]);
        }else {
            $productos = $this->productoService->getAll();
        }
        return view('productos.index', compact('productos', 'searchField', 'search'));
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
