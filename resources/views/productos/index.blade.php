@extends('layouts.app')

@section('content')
    <div>
        <h2>PRODUCTOS</h2>

        <!-- <nav class="navbar"> -->
            <!-- <div class="container-fluid">-->
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <button type="button" class="btn btn-sm btn-primary add-btn">
                            Agregar
                        </button>
                    </div>
                    <div class="col-md-7 col-sm-12 offset-md-2 offset-sm-0">
                        <form id="search-form" class="d-flex">
                            <div class="input-group">
                                <select id="form-select" aria-label="Campo de filtro">
                                    <option selected value="titulo">Titulo</option>
                                    <option value="precio">Precio</option>
                                    <option value="created_at">Creación</option>
                                </select>
                                <input class="form-control w-50" type="search" id="search-text" placeholder="Termino a buscar" aria-label="Buscar">
                                <button class="btn btn-outline-success" type="submit">Buscar</button>
                            </div>
                        </form>
                    </div>
                <!-- </div> -->
            </div>
        <!-- </nav> -->

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
                        <button data-id="<?= $producto->id ?>" class="edit-btn btn btn-primary btn-sm">Editar</button>
                        <button data-id="<?= $producto->id ?>" class="delete-btn btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="producto-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Productos" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="producto-form" action="{{ route('productos.store') }}" method="POST" novalidate="true">
                        @csrf

                        <div>
                            <input type="hidden" class="form-control" id="producto-id" name="id">
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="producto-title" name="title" required>
                            <div id="title-error-message" class="invalid-feedback">
                                Ingrese un titulo valido
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="producto-price" name="price" required>
                                <div id="price-error-message" class="invalid-feedback">
                                    Ingrese un precio valido
                                </div>
                            </div>
                        </div>

                        <div>
                            <input type="hidden" class="form-control" id="producto-created" name="created_at">
                        </div>

                        <!-- <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div> -->

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="submit-button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize modal instance

        let productoModal;
        let confirmacionModal;

        $(document).ready(function() {
            productoModal = new bootstrap.Modal(document.getElementById('producto-modal'), {
                keyboard: false
            });
            confirmacionModal = new bootstrap.Modal(document.getElementById('confirmacion-modal'), {
                keyboard: false
            });
        })

        $('.add-btn').on('click', function() {
            $('#producto-id').val('');
            $('#producto-title').val('');
            $('#producto-price').val(0);
            $('#producto-created').val('');

            productoModal.show();
        });

        $('.edit-btn').on('click', async function() {
            let id = $(this).data('id');

            const producto = await $.get(`productos/${id}`);

            $('#producto-id').val(producto.id);
            $('#producto-title').val(producto.title);
            $('#producto-price').val(producto.price);
            $('#producto-created').val(producto.created_at);

            productoModal.show();
        });

        $('.delete-btn').on('click', async function() {
            let id = $(this).data('id');

            $.ajax({
                url: `/productos/${id}`,
                type: 'DELETE',
                data:{
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response) {
                    // alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.errors);
                }
            });
        });

        $('#submit-button').on('click', function(event) {
            event.preventDefault();

            let id = $('#producto-id').val();
            let url = id ? `/productos/${id}` : '/productos';
            let type = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: type,
                data: $('#producto-form').serialize(),
                success: function(response) {
                    // alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    setFormErrors(xhr.responseJSON.errors)
                }
            });
        });

        $("#search-form").on('submit', (event) => {
            event.preventDefault();

            const search = $("#search-text").val();
            const searchField = $("#form-select").val();
            let queryString = '';
            if (search) {
                queryString = `?search=${search}&searchField=${searchField}`;
                $.ajax({
                    url: `{{ route('productos.index') }}${queryString}`,
                    method: 'GET',
                    success: function(response) {
                        location.reload();
                        // renderProducts(response.products);
                        // renderPagination(response.pagination);
                    },
                    error: function() {
                        alert('Error loading products');
                    }
                });
            }
        });

        // Function to open modal
        function openModal() {
            productoModal.show();
        }

        // Function to close modal
        function closeModal() {
            productoModal.hide();
        }

        function setFormErrors( errors ) {
            if (errors.title) {
                $('#producto-title').addClass('is-invalid');
            }
            if (errors.price) {
                $('#producto-price').addClass('is-invalid');
            }
        }

        $('#producto-title').on("keyup", () => $('#producto-title').removeClass('is-invalid'));
        $('#producto-price').on("keyup", () => $('#producto-price').removeClass('is-invalid'));


        // Event listener for when modal is completely hidden
        const modalElement = document.getElementById('producto-modal');
        modalElement.addEventListener('hidden.bs.modal', function (event) {
            console.log('Modal is hidden');
            // Add any cleanup logic here
        });

        // Event listener for when modal is completely shown
        modalElement.addEventListener('shown.bs.modal', function (event) {
            console.log('Modal is shown');
            // Add any initialization logic here
        });
    </script>
@endsection
