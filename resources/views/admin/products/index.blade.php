@extends('admin.layouts.app')

@section('content')
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product Management</h5>
            <a href="/admin/products/create" class="btn btn-light" >
                <i class="fas fa-plus-circle me-2"></i>Create New Product
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="brandFilter" class="form-select">
                        <option value="">All Brands</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="priceRangeSlider" class="form-label">Price Range</label>
                    <input type="range" class="form-range" id="priceRangeSlider" min="0" max="1000" step="10">
                    <div class="d-flex justify-content-between">
                        <span id="minPriceLabel">$0</span>
                        <span id="maxPriceLabel">$1000</span>
                    </div>
                </div>
            </div>

            <div id="globalMessage" class="alert" style="display:none;"></div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="product-table">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Base Price</th>
                            <th>Brand</th>
                            <th>Gender</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Create/Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" novalidate enctype="multipart/form-data">
                        <input type="hidden" id="productId" name="productId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="men">Men</option>
                                    <option value="women">Women</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="base_price" class="form-label">Base Price</label>
                                <input type="number" class="form-control" id="base_price" name="base_price" required min="0" step="0.01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div id="imagePreviewContainer" class="d-flex mt-2"></div>
                        </div>

                        <div id="variantsSection" class="mb-3">
                            <label class="form-label">Product Variants</label>
                            <button type="button" class="btn btn-secondary" id="addVariantBtn">Add Variant</button>
                            <div id="variantsContainer"></div>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets\js\jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets\vendor\DataTables\datatables.min.js') }}"></script>

<script>
$(document).ready(function() {
    const $productTable = $('#product-table');
    const $productForm = $('#productForm');
    const $productModal = $('#productModal');
    const $errorMessages = $('#errorMessages');

    function formatDate(data) {
        return data ? new Date(data).toLocaleDateString() : 'N/A';
    }

    function initializeDataTable(filters) {
        return $productTable.DataTable({
            ajax: {
                url: '/api/products',
                dataSrc: 'products.data',
                data: function(d) {
                    d.category = $('#categoryFilter').val();
                    d.brand = $('#brandFilter').val();
                    d.min_price = $('#priceRangeSlider').val().split(',')[0];
                    d.max_price = $('#priceRangeSlider').val().split(',')[1];
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'base_price', title: 'Price' },
                { data: 'brand' },
                { data: 'gender' },
                {
                    data: 'variants',
                    render: function(variants) {
                        return variants && variants.length > 0 ?
                            variants.reduce((total, variant) => total + variant.stock_quantity, 0) :
                            '0';
                    }
                },
                { data: 'category.name' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info edit-product" data-id="${data.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-product" data-id="${data.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            processing: true,
            responsive: true
        });
    }

    function displayUploadedImages(files) {
        const container = $('#imagePreviewContainer');
        container.empty();

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = $(`
                    <div class="image-preview position-relative me-2">
                        <img src="${e.target.result}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        <input type="radio" name="primary_image" value="${index}"
                               ${index === 0 ? 'checked' : ''} class="position-absolute top-0 start-0">
                    </div>
                `);
                container.append(img);
            };
            reader.readAsDataURL(file);
        });
    }

    function loadCategories(selectedCategory = null) {
        $.get('/api/categories', function(data) {
            const categorySelect = $('#category');
            categorySelect.empty().append('<option value="">Select a category</option>');

            data.categories.forEach(category => {
                const option = $(`<option value="${category.id}">${category.name}</option>`);
                if (selectedCategory && category.id === selectedCategory) {
                    option.prop('selected', true);
                }
                categorySelect.append(option);

                if (category.children && category.children.length > 0) {
                    category.children.forEach(child => {
                        const childOption = $(`<option value="${child.id}">-- ${child.name}</option>`);
                        if (selectedCategory && child.id === selectedCategory) {
                            childOption.prop('selected', true);
                        }
                        categorySelect.append(childOption);
                    });
                }
            });
        });
    }

    function populateFilters(filters) {
        const categoryFilter = $('#categoryFilter');
        const brandFilter = $('#brandFilter');
        const priceRangeSlider = $('#priceRangeSlider');

        // Categories
        filters.categories.forEach(category => {
            categoryFilter.append(`<option value="${category.id}">${category.name}</option>`);
        });

        // Brands
        filters.brands.forEach(brand => {
            brandFilter.append(`<option value="${brand}">${brand}</option>`);
        });

        // Price Range
        const { min, max } = filters.price_range;
        priceRangeSlider.attr({
            'min': min,
            'max': max,
            'value': `${min},${max}`
        });
        $('#minPriceLabel').text(`$${min}`);
        $('#maxPriceLabel').text(`$${max}`);
    }

    const productTable = initializeDataTable();

    // Fetch and populate filters
    $.get('/api/products', function(response) {
        populateFilters(response.filters);
    });

    // Filter change events
    $('#categoryFilter, #brandFilter, #priceRangeSlider').on('change', function() {
        productTable.ajax.reload();
    });

    $('#images').on('change', function() {
        displayUploadedImages(this.files);
    });

    // Modal interactions
    $('#addProductBtn').on('click', function() {
        $productForm[0].reset();
        $('#productId').val('');
        $('#productModalLabel').text('Create New Product');
        loadCategories();
        $('#variantsContainer').empty();
        $('#imagePreviewContainer').empty();
        $productModal.modal('show');
    });

    function addVariantRow(variant = {}) {
        const row = `
            <div class="row mb-2 variant-row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Size"
                           value="${variant.size || ''}" name="variant_size">
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="Color"
                           value="${variant.color || ''}" name="variant_color">
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="SKU"
                           value="${variant.sku || ''}" name="variant_sku">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Stock"
                           value="${variant.stock_quantity || 0}" name="variant_stock">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Price Adj."
                           value="${variant.price_adjustment || 0}" name="variant_price_adj" step="0.01">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-variant-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#variantsContainer').append(row);
    }

    $('#addVariantBtn').on('click', () => addVariantRow());

    $('#variantsContainer').on('click', '.remove-variant-btn', function() {
        $(this).closest('.variant-row').remove();
    });

    // Edit Product
    $productTable.on('click', '.edit-product', function() {
        const productId = $(this).data('id');

        $.get(`/api/products/${productId}`, function(product) {
            $('#productId').val(product.id);
            $('#name').val(product.name);
            $('#description').val(product.description);
            $('#base_price').val(product.base_price);
            $('#brand').val(product.brand);
            $('#gender').val(product.gender);

            loadCategories(product.category_id);

            // Clear and populate variants
            $('#variantsContainer').empty();
            if (product.variants) {
                product.variants.forEach(variant => addVariantRow(variant));
            }

            // Display existing images
            const imagePreview = $('#imagePreviewContainer');
            imagePreview.empty();
            if (product.images) {
                product.images.forEach((image, index) => {
                    const img = $(`
                        <div class="image-preview position-relative me-2">
                            <img src="${image.image_url}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            <input type="radio" name="primary_image"
                                   value="${index}"
                                   ${image.is_primary ? 'checked' : ''}>
                        </div>
                    `);
                    imagePreview.append(img);
                });
            }

            $productModal.modal('show');
        });
    });
// Save Product
$('#saveProductBtn').on('click', function() {
        if (!$productForm[0].checkValidity()) {
            $productForm.addClass('was-validated');
            return;
        }

        const productId = $('#productId').val();
        const method = productId ? 'PUT' : 'POST';
        const url = productId ? `/api/products/${productId}` : '/api/products';

        // Collect variants
        const variants = $('#variantsContainer .variant-row').map(function() {
            return {
                size: $(this).find('[name="variant_size"]').val(),
                color: $(this).find('[name="variant_color"]').val(),
                sku: $(this).find('[name="variant_sku"]').val(),
                stock_quantity: parseInt($(this).find('[name="variant_stock"]').val() || 0),
                price_adjustment: parseFloat($(this).find('[name="variant_price_adj"]').val() || 0)
            };
        }).get();

        const formData = new FormData($productForm[0]);
        formData.append('variants', JSON.stringify(variants));

        // Handle primary image
        const primaryImageIndex = $('input[name="primary_image"]:checked').val();
        formData.append('primary_image_index', primaryImageIndex);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                productTable.ajax.reload();
                $productModal.modal('hide');

                // Show success message
                $('#globalMessage')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .text('Product saved successfully')
                    .show()
                    .delay(3000)
                    .fadeOut();
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Error saving product.';

                if (Object.keys(errors).length > 0) {
                    errorMessage = Object.values(errors).flat().join('<br>');
                }

                $errorMessages
                    .html(errorMessage)
                    .show();
            }
        });
    });

    // Delete Product
    $productTable.on('click', '.delete-product', function() {
        const productId = $(this).data('id');

        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/api/products/${productId}`,
                method: 'DELETE',
                success: function() {
                    productTable.ajax.reload();
                    $('#globalMessage')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .text('Product deleted successfully')
                        .show()
                        .delay(3000)
                        .fadeOut();
                },
                error: function() {
                    $('#globalMessage')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .text('Failed to delete product')
                        .show()
                        .delay(3000)
                        .fadeOut();
                }
            });
        }
    });
});
</script>
@endsection
