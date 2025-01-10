@extends('layouts.app')

@section('title', 'Products')

@section('custom-styles')
<style>
    .filter-sidebar {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }
    .price-range-inputs {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .product-grid {
        min-height: 500px;
    }
    .loading-indicator {
        display: none;
        text-align: center;
        margin: 20px 0;
    }
</style>
@endsection

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">

<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h1>Our Products</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="filter-sidebar">
                <h4 class="mb-3">Filters</h4>

                <!-- Categories -->
                <div class="mb-4">
                    <h5>Categories</h5>
                    <div class="form-check" id="category-filters">
                        <div class="loading-indicator" id="loading-categories">Loading categories...</div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <h5>Price Range</h5>
                    <div class="price-range-inputs mb-3">
                        <input type="number" class="form-control form-control-sm" id="min-price" placeholder="Min">
                        <span>-</span>
                        <input type="number" class="form-control form-control-sm" id="max-price" placeholder="Max">
                    </div>
                    <button class="btn btn-sm btn-dark w-100" id="apply-price-filter">Apply</button>
                </div>

                <!-- Sort By -->
                <div class="mb-4">
                    <h5>Sort By</h5>
                    <select class="form-select" id="sort-select">
                        <option value="latest">Latest</option>
                        <option value="low_to_high">Price: Low to High</option>
                        <option value="high_to_low">Price: High to Low</option>
                        <option value="popularity">Popularity</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <button class="btn btn-outline-dark w-100" id="clear-filters">Clear All Filters</button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span id="product-count">0</span> Products Found
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-dark active" id="grid-view">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark" id="list-view">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4 product-grid" id="product-list"></div>
            <div class="loading-indicator" id="loading-products">Loading products...</div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<!-- Product Quick View Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="product-modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="" id="modal-product-image" class="img-fluid" alt="Product Image">
                    </div>
                    <div class="col-md-6">
                        <h4 id="modal-product-name"></h4>
                        <div class="mb-3">
                            <span class="text-muted text-decoration-line-through" id="modal-original-price"></span>
                            <span class="ms-2 h4" id="modal-sale-price"></span>
                        </div>
                        <div class="mb-3" id="modal-product-description"></div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control w-25" id="modal-quantity" value="1" min="1">
                        </div>
                        <button class="btn btn-dark" id="modal-add-to-cart">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.1.9/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
        let currentPage = 1;
        let currentFilters = {
            categories: [],
            minPrice: null,
            maxPrice: null,
            sortBy: 'latest'
        };

        // Initial load
        loadCategories();
        loadProducts();

        function loadCategories() {
            $('#loading-categories').show();
            $.ajax({
                url: '/api/categories',
                method: 'GET',
                success: function(data) {
                    const categoryFilters = data.map(category => `
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" value="${category.id}" id="category-${category.id}">
                            <label class="form-check-label" for="category-${category.id}">
                                ${category.name}
                            </label>
                        </div>
                    `).join('');
                    $('#category-filters').html(categoryFilters);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load categories'
                    });
                },
                complete: function() {
                    $('#loading-categories').hide();
                }
            });
        }

        function loadProducts() {
            $('#loading-products').show();
            const queryParams = new URLSearchParams({
                page: currentPage,
                sort: currentFilters.sortBy,
                ...currentFilters.minPrice && { min_price: currentFilters.minPrice },
                ...currentFilters.maxPrice && { max_price: currentFilters.maxPrice },
                ...currentFilters.categories.length && { categories: currentFilters.categories.join(',') }
            });

            $.ajax({
                url: `/api/products?${queryParams}`,
                method: 'GET',
                success: function(response) {
                    const products = response.data;
                    $('#product-count').text(response.total);

                    const productHTML = products.map(product => `
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                                <img src="${product.image || 'assets/img/card.jpg'}" class="card-img-top" alt="${product.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted text-decoration-line-through">$${(product.price * 1.25).toFixed(2)}</span>
                                            <span class="ms-2 fw-bold">$${product.price}</span>
                                        </div>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark w-100 mt-3 quick-view" data-id="${product.id}">Quick View</button>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    $('#product-list').html(productHTML);
                    updatePagination(response.current_page, response.last_page);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load products'
                    });
                },
                complete: function() {
                    $('#loading-products').hide();
                }
            });
        }

        function updatePagination(currentPage, lastPage) {
            let pagination = '';

            // Previous button
            pagination += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= lastPage; i++) {
                pagination += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Next button
            pagination += `
                <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>
            `;

            $('#pagination').html(pagination);
        }

        // Event Handlers
        $('#sort-select').change(function() {
            currentFilters.sortBy = $(this).val();
            loadProducts();
        });

        $(document).on('change', '.category-filter', function() {
            currentFilters.categories = $('.category-filter:checked').map(function() {
                return $(this).val();
            }).get();
            loadProducts();
        });

        $('#apply-price-filter').click(function() {
            currentFilters.minPrice = $('#min-price').val() || null;
            currentFilters.maxPrice = $('#max-price').val() || null;
            loadProducts();
        });

        $('#clear-filters').click(function() {
            currentFilters = {
                categories: [],
                minPrice: null,
                maxPrice: null,
                sortBy: 'latest'
            };
            $('.category-filter').prop('checked', false);
            $('#min-price, #max-price').val('');
            $('#sort-select').val('latest');
            loadProducts();
        });

        $(document).on('click', '.quick-view', function() {
            const productId = $(this).data('id');
            // Fetch product details and show modal
            $.ajax({
                url: `/api/products/${productId}`,
                success: function(product) {
                    $('#modal-product-name').text(product.name);
                    $('#modal-product-image').attr('src', product.image || 'assets/img/card.jpg');
                    $('#modal-original-price').text(`$${(product.price * 1.25).toFixed(2)}`);
                    $('#modal-sale-price').text(`$${product.price}`);
                    $('#modal-product-description').text(product.description);
                    $('#productModal').modal('show');
                }
            });
        });

        $('#modal-add-to-cart').click(function() {
            const quantity = $('#modal-quantity').val();
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                text: `${quantity} item(s) added to your cart`,
                timer: 2000,
                showConfirmButton: false
            });
            $('#productModal').modal('hide');
        });

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            currentPage = $(this).data('page');
            loadProducts();
        });
    });
</script>
@endsection
