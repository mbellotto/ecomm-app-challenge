@extends('layouts.app')

@section('content')
    <div>
        <h2>PRODUCTOS</h2>

        <table id="productosTable" class="table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Título</th>
                <th scope="col">Precio</th>
                <th scope="col">Creación</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($productos as $producto)
                <tr data-id="<?= $producto->id ?>" scope="row">
                    <td><?= $producto->id ?></td>
                    <td><?= $producto->title ?></td>
                    <td><?= $producto->price ?></td>
                    <td><?= $producto->created_at ?></td>
                    <td>
                        <button class="edit-btn btn btn-primary btn-sm">Editar</button>
                        <button class="delete-btn btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
