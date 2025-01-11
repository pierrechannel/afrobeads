@extends('admin.layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<div class="container">
    <h1>Product List</h1>
    <table id="product-table" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <button id="add-product-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" required>
                                <option value="">Select a category</option>
                                <!-- Populate categories dynamically here -->
                                <option value="1">Category 1</option>
                                <option value="2">Category 2</option>
                                <option value="3">Category 3</option>
                                <!-- Add more categories as needed -->
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendor/DataTables/datatables.min.js') }}"></script>

<script>
$(document).ready(function() {
    const table = $('#product-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/products',
            dataSrc: ''
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Serial number
                }
            },
            { data: 'name' },
            { data: 'price' },
            {
                data: 'category.name',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            {
                data: 'image',
                render: function(data) {
                    return data ? `<img src="${data}" width="50">` : 'N/A';
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>`;
                }
            }
        ]
    });

    $('#product-table tbody').on('click', '.delete-btn', function() {
        const productId = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: '/api/products/' + productId,
                method: 'DELETE',
                success: function() {
                    table.ajax.reload(null, true);
                },
                error: function(xhr) {
                    alert('Failed to delete product. ' + xhr.responseText);
                }
            });
        }
    });

    $('#product-table tbody').on('click', '.edit-btn', function() {
        const productId = $(this).data('id');

        $.ajax({
            url: '/api/products/' + productId,
            method: 'GET',
            success: function(data) {
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#category_id').val(data.category_id); // Assuming your product API returns a `category_id`
                $('#stock').val(data.stock); // Assuming your product API returns stock
                $('#image').val(''); // Reset the image field
                $('#addProductModal').modal('show');
                $('#addProductModal .modal-title').text('Edit Product');

                $('#addProductForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    $.ajax({
                        url: '/api/products/' + productId,
                        type: 'PUT',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function() {
                            table.ajax.reload(null, true);
                            $('#addProductModal').modal('hide');
                        },
                        error: function(xhr) {
                            handleValidationErrors(xhr);
                        }
                    });
                });
            },
            error: function(xhr) {
                alert('Failed to retrieve product for editing.');
            }
        });
    });

    $('#addProductForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: '/api/products',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                table.ajax.reload(null, true);
                $('#addProductModal').modal('hide');
            },
            error: function(xhr) {
                handleValidationErrors(xhr);
            }
        });
    });

    function handleValidationErrors(xhr) {
        const errors = xhr.responseJSON.errors;
        for (const [key, messages] of Object.entries(errors)) {
            const input = $(`#${key}`);
            input.addClass('is-invalid');
            input.next('.invalid-feedback').html(messages.join('<br>')).show();
        }
    }
});
</script>
@endsection
