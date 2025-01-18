@extends('admin.layouts.app')

@section('content')
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Product Management</h5>
            <button class="btn btn-primary mb-3" id="addProductBtn">
                <i class="fas fa-plus-circle"></i> Create New Product
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="product-table">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Date Created</th>
                            <th>Date Modified</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="productModalLabel">
                        <i class="fas fa-box me-2"></i>Create New Product
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" novalidate enctype="multipart/form-data">
                        <input type="hidden" id="productId" name="productId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter a product name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                            <div class="invalid-feedback">Please enter a description for the product.</div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01">
                            <div class="invalid-feedback">Please enter a valid price.</div>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                                <!-- Categories will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div class="invalid-feedback">Please upload product images.</div>
                        </div>
                        <div id="variantsSection" class="mb-3">
                            <label for="variants" class="form-label">Product Variants</label>
                            <button type="button" class="btn btn-secondary" id="addVariantBtn">Add Variant</button>
                            <div id="variantsContainer"></div>
                        </div>
                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productTable = initializeDataTable();
    const $productForm = $('#productForm');
    const $productModal = $('#productModal');
    const $errorMessages = $('#errorMessages');

    // Function to display global messages
    function showGlobalMessage(message, type = 'info') {
        const messageDiv = $('#globalMessage');
        messageDiv.removeClass('alert-info alert-success alert-danger')
            .addClass(`alert-${type}`).text(message).show();
        setTimeout(() => {
            messageDiv.fadeOut(300);
        }, 5000);
    }

    // Initialize DataTable
    function initializeDataTable() {
        return $('#product-table').DataTable({
            ajax: {
                url: '/api/products',
                dataSrc: 'products.data', // Accessing the 'data' array within 'products'
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load products. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'price' },
                { data: 'stock' },
                { data: 'created_at', render: function(data) {
                    return new Date(data).toLocaleDateString(); // Formatting date
                }},
                { data: 'updated_at', render: function(data) {
                    return data ? new Date(data).toLocaleDateString() : 'N/A'; // Formatting date or showing 'N/A'
                }},
                { data: 'category.name' }, // Accessing category name
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-product" data-id="${data.id}" title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-product" data-id="${data.id}" title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            processing: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No products found',
                zeroRecords: 'No matching products found'
            }
        });
    }

    // Reset form function
    function resetForm() {
        $productForm[0].reset();
        $productForm.removeClass('was-validated');
        $errorMessages.hide().empty();
        $('#productId').val('');
        $('#productModalLabel').text('Create New Product');
        loadCategories();
        $('#variantsContainer').empty(); // Clear variants
    }

    function loadCategories() {
    $.get('/api/categories', function(data) {
        const categorySelect = $('#category');
        categorySelect.empty().append('<option value="">Select a category</option>');

        // Iterate through the categories
        data.categories.forEach(category => {
            // Append the parent category
            categorySelect.append(`<option value="${category.id}">${category.name}</option>`);

            // Check for child categories
            if (category.children && category.children.length > 0) {
                category.children.forEach(child => {
                    categorySelect.append(`<option value="${child.id}">-- ${child.name}</option>`);
                });
            }
        });
    });
}


    $('#addProductBtn').on('click', function() {
        resetForm();
        $productModal.modal('show');
    });

    $('#product-table').on('click', '.edit-product', function() {
        const productId = $(this).data('id');

        $.get(`/api/products/${productId}`)
            .done(function(response) {
                $('#productId').val(response.id);
                $('#name').val(response.name);
                $('#description').val(response.description);
                $('#price').val(response.price);
                $('#category').val(response.category_id);

                // Load existing variants
                loadVariants(response.variants);

                $('#productModalLabel').text('Edit Product');
                loadCategories();

                $productModal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch product data:', xhr);
                showGlobalMessage('Error fetching product data.', 'danger');
            });
    });

    function loadVariants(variants) {
        $('#variantsContainer').empty(); // Clear existing variants
        variants.forEach(variant => {
            addVariantRow(variant.size, variant.color, variant.sku, variant.stock_quantity, variant.price_adjustment);
        });
    }

    function addVariantRow(size = '', color = '', sku = '', stock_quantity = 0, price_adjustment = 0) {
        const variantRow = `
            <div class="row mb-3">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Size" value="${size}" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Color" value="${color}" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="SKU" value="${sku}" required>
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Stock" value="${stock_quantity}" required min="0">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Price Adjustment" value="${price_adjustment}" step="0.01">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-variant-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#variantsContainer').append(variantRow);
    }

    $('#addVariantBtn').on('click', function() {
        addVariantRow();
    });

    $('#variantsContainer').on('click', '.remove-variant-btn', function() {
        $(this).closest('.row').remove();
    });

    $('#product-table').on('click', '.delete-product', function() {
        const productId = $(this).data('id');

        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/api/products/${productId}`,
                method: 'DELETE',
                success: function() {
                    productTable.ajax.reload();
                    showGlobalMessage('Product deleted successfully', 'success');
                },
                error: function(xhr) {
                    console.error('Failed to delete product:', xhr);
                    showGlobalMessage('Error deleting product.', 'danger');
                }
            });
        }
    });

    $('#saveProductBtn').on('click', function() {
        if (!$productForm[0].checkValidity()) {
            $productForm.addClass('was-validated');
            return;
        }

        const productId = $('#productId').val();
        const method = productId ? 'PUT' : 'POST';
        const url = productId ? `/api/products/${productId}` : '/api/products';

        const variants = [];
        $('#variantsContainer .row').each(function() {
            const size = $(this).find('input').eq(0).val().trim();
            const color = $(this).find('input').eq(1).val().trim();
            const sku = $(this).find('input').eq(2).val().trim();
            const stock_quantity = $(this).find('input').eq(3).val().trim();
            const price_adjustment = $(this).find('input').eq(4).val().trim();

            variants.push({
                size,
                color,
                sku,
                stock_quantity,
                price_adjustment: price_adjustment ? parseFloat(price_adjustment) : 0
            });
        });

        const formData = new FormData($productForm[0]);
        formData.append('variants', JSON.stringify(variants));

        $.ajax({
            url: url,
            method: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                productTable.ajax.reload();
                $productModal.modal('hide');
                resetForm();
                showGlobalMessage('Product saved successfully', 'success');
            },
            error: function(xhr) {
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    let detailedErrors = '';
                    for (const key in errors) {
                        detailedErrors += `<strong>${key}:</strong> ${errors[key].join(', ')}<br>`;
                    }
                    $errorMessages.html(detailedErrors).show();
                } else {
                    showGlobalMessage('Error saving product.', 'danger');
                }
            }
        });
    });
});
</script>
@endsection
