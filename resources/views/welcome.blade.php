@extends('layouts.app')

@section('title', 'Home')

@section('custom-styles')
    <style>
        .hero-section { height: 100%; background-size: cover; }
        .loading-indicator {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
    </style>
@endsection

@section('content')
    <section class="hero-section">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('tomato.jpg') }}" class="d-block w-100" alt="Sale Banner">
                    <div class="carousel-caption text-start">
                        <h1 class="display-4 fw-bold">Summer Sale</h1>
                        <p class="lead">Up to 70% off on selected items</p>
                        <a href="#" class="btn btn-primary btn-lg">Shop Now</a>
                    </div>
                </div>
                <!-- Additional slides -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next" aria-label="Next Slide">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Featured Categories -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Categories</h2>
        <div class="row g-4" id="category-list"></div>
        <div class="loading-indicator" id="loading-categories">Loading categories...</div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" id="sort-dropdown-toggle" data-bs-toggle="dropdown">
                    Sort by
                </button>
                <ul class="dropdown-menu" id="sort-options">
                    <li><a class="dropdown-item" data-sort="latest" href="#">Latest</a></li>
                    <li><a class="dropdown-item" data-sort="low_to_high" href="#">Price: Low to High</a></li>
                    <li><a class="dropdown-item" data-sort="high_to_low" href="#">Price: High to Low</a></li>
                </ul>
            </div>
        </div>
        <div class="row g-4" id="product-list"></div>
        <div class="loading-indicator" id="loading-products">Loading products...</div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="product-modal-body">
                    <!-- Product details will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="add-to-cart-btn" class="btn btn-dark">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Document ready. Loading categories and products...');
    loadCategories();
    loadProducts();

    function loadCategories() {
        $('#loading-categories').show();
        console.log('Fetching categories from API...');
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            success: function(data) {
                console.log('Categories loaded:', data);
                $('#category-list').empty().append(data.map(category => `
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="${category.image || '/api/placeholder/400/300'}" class="card-img-top" alt="${category.name}">
                            <div class="card-body text-center">
                                <h5 class="card-title">${category.name}</h5>
                                <a href="#" class="btn btn-outline-dark">View Products</a>
                            </div>
                        </div>
                    </div>
                `));
            },
            error: function(xhr) {
                console.error('Error loading categories:', xhr.responseText);
                alert('Error loading categories. Check console for details.');
            },
            complete: function() {
                $('#loading-categories').hide();
                console.log('Categories loading complete.');
            }
        });
    }

    function loadProducts(sortOrder = 'latest') {
        $('#loading-products').show();
        console.log(`Fetching products from API with sort order: ${sortOrder}...`);
        $.ajax({
            url: `/api/products?sort=${sortOrder}`,  // Adjust your API to support sorting
            method: 'GET',
            success: function(data) {
                console.log('Products loaded:', data);
                $('#product-list').empty().append(data.map(product => `
                    <div class="col-md-3">
                        <div class="card h-100" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">
                            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                            <img src="${product.image || '/api/placeholder/300/300'}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted text-decoration-line-through">$${(product.price * 1.25).toFixed(2)}</span>
                                        <span class="ms-2 fw-bold">$${product.price.toFixed(2)}</span>
                                    </div>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <button class="btn btn-dark w-100 mt-3 view-details">View Details</button>
                            </div>
                        </div>
                    </div>
                `));
            },
            error: function(xhr) {
                console.error('Error loading products:', xhr.responseText);
                alert('Error loading products. Check console for details.');
            },
            complete: function() {
                $('#loading-products').hide();
                console.log('Products loading complete.');
            }
        });
    }

    // Sort Products
    $('#sort-options .dropdown-item').click(function(e) {
        e.preventDefault();
        const sortOrder = $(this).data('sort');
        console.log(`Sort option selected: ${sortOrder}`);
        loadProducts(sortOrder);
    });

    // View Details Modal
    $(document).on('click', '.view-details', function() {
        const card = $(this).closest('.card');
        const productId = card.data('id');
        const productName = card.data('name');
        const productPrice = card.data('price');

        console.log(`Viewing details for product ID: ${productId}, Name: ${productName}, Price: $${productPrice}`);
        $('#productModalLabel').text(productName);
        $('#product-modal-body').html(`
            <p><strong>Price:</strong> $${productPrice.toFixed(2)}</p>
            <p><strong>Description:</strong> This is a detailed description of the product.</p>
        `);
        $('#productModal').modal('show');
    });

    // Add to Cart (example functionality)
    $('#add-to-cart-btn').click(function() {
        console.log('Product added to cart!');
        alert('Product added to cart!');
        $('#productModal').modal('hide');
    });
});
</script>
@endsection
