@extends('layouts.app')

@section('title', 'African Marketplace')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    :root {
        --primary-color: #D4AF37;
        --accent-color: #8B4513;
        --text-dark: #2C1810;
    }

    .hero-section {
        height: 100%;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    .hero-section img {
        object-fit: cover;
    }

    .category-card {
        transition: transform 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
    }

    .product-card .badge {
        background-color: var(--primary-color);
    }

    .testimonial-section {
        background-color: #FDF5E6;
        border-top: 5px solid var(--primary-color);
    }

    .newsletter-section {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/img/african-fabric.jpg');
        background-size: cover;
        color: white;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/img/banner-image-2.jpg" class="d-block w-100 h-100" alt="African Marketplace">
                <div class="carousel-caption text-start">
                    <h1 class="display-3 fw-bold">Discover Africa's Finest</h1>
                    <p class="lead">Authentic African products crafted with tradition and love</p>
                    <a href="/products" class="btn btn-warning btn-lg px-4">Explore Collections</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/banner-image-2.jpg" class="d-block w-100 h-100" alt="African Artisans">
                <div class="carousel-caption text-start">
                    <h1 class="display-3 fw-bold">Support African Artisans</h1>
                    <p class="lead">Each purchase empowers local craftspeople</p>
                    <a href="/artisans" class="btn btn-warning btn-lg px-4">Meet Our Artisans</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 display-6">Explore African Heritage</h2>
        <div class="row g-4" id="category-list">
            <!-- Categories will be dynamically loaded -->
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="display-6">Featured Products</h2>
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                    Sort by
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" data-sort="traditional" href="#">Traditional</a></li>
                    <li><a class="dropdown-item" data-sort="modern" href="#">Modern</a></li>
                    <li><a class="dropdown-item" data-sort="price" href="#">Price</a></li>
                </ul>
            </div>
        </div>
        <div class="row g-4" id="product-list">
            <!-- Products will be dynamically loaded -->
        </div>
    </div>
</section>



<!-- Testimonials -->
<section class="testimonial-section py-5">
    <div class="container">
        <h2 class="text-center mb-5 display-6">What Our Customers Are Saying</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="assets\img\customer.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Customer">
                        <p class="mb-3">"The quality of African craftsmanship is unmatched. These products bring warmth to my home!"</p>
                        <footer class="text-muted">- Amara K., Kenya</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="assets\img\customer.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Customer">
                        <p class="mb-3">"I love the story behind each product. It's like bringing a piece of Africa into my home!"</p>
                        <footer class="text-muted">- David A., South Africa</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="assets\img\customer.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Customer">
                        <p class="mb-3">"Shopping here supports artisans, and the products are truly unique!"</p>
                        <footer class="text-muted">- Fatima S., Nigeria</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cultural Impact Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="display-6">Preserving African Heritage</h2>
                <p class="lead">Each product tells a story of tradition, craftsmanship, and cultural heritage.</p>
                <div class="d-flex gap-3 mt-4">
                    <div class="text-center">
                        <h3 class="h2 text-warning">1000+</h3>
                        <p>Artisans</p>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-warning">25+</h3>
                        <p>Countries</p>
                    </div>
                    <div class="text-center">
                        <h3 class="h2 text-warning">50K+</h3>
                        <p>Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets\img\thumb-avocado.png" class="img-fluid rounded-3" alt="African Craftsman">
            </div>
        </div>
    </div>
</section>
<!-- New Arrivals -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4 display-6">New Arrivals</h2>
        <div class="row g-4" id="new-arrivals-list">
            <!-- New arrivals will be dynamically loaded -->
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section py-5 text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="display-6 mb-4">Join Our Community</h2>
                <p class="lead mb-4">Subscribe to receive updates about new artisans, products, and African cultural insights</p>
                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                            <button class="btn btn-warning px-4" type="submit">Subscribe</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Load categories
        function loadCategories(retryCount = 0) {
            $('#category-list').html('<p class="text-center">Loading categories...</p>'); // Show loading message
            $.ajax({
                url: '/api/categories',
                method: 'GET',
                success: function(data) {
                    $('#category-list').empty().append(data.map(category => `
                        <div class="col-md-4">
                            <div class="card category-card h-100 border-0 shadow-sm">
                                <img src="${category.image || 'assets/img/product.png'}"
                                     class="card-img-top" alt="${category.name}" style="height: 150px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${category.name}</h5>
                                    <p class="text-muted">${category.description}</p>
                                    <a href="/categories/${category.id}" class="btn btn-outline-warning">Explore</a>
                                </div>
                            </div>
                        </div>
                    `));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Failed to load categories:", textStatus, errorThrown);
                    if (retryCount < 2) { // You may adjust the number of retries
                        loadCategories(retryCount + 1); // Retry
                    } else {
                        $('#category-list').html('<p class="text-danger text-center">Failed to load categories. Please try again later.</p>');
                    }
                }
            });
        }

        // Load products
        function loadProducts(sortOrder = 'traditional', retryCount = 0) {
            $('#product-list').html('<p class="text-center">Loading products...</p>'); // Show loading message
            $.ajax({
                url: `/api/products?sort=${sortOrder}`,
                method: 'GET',
                success: function(data) {
                    $('#product-list').empty().append(data.map(product => `
                        <div class="col-md-3">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <div class="badge position-absolute top-0 end-0 m-2">Authentic</div>
                                <img src="${product.image || 'assets/img/product.png'}"
                                     class="card-img-top" alt="${product.name}" style="height: 150px;">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="text-muted small">${product.origin}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">$${product.price}</span>
                                        <button class="btn btn-warning btn-sm view-details">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Failed to load products:", textStatus, errorThrown);
                    if (retryCount < 2) { // You may adjust the number of retries
                        loadProducts(sortOrder, retryCount + 1); // Retry
                    } else {
                        $('#product-list').html('<p class="text-danger text-center">Failed to load products. Please try again later.</p>');
                    }
                }
            });
        }

        // Load new arrivals
        function loadNewArrivals(retryCount = 0) {
            $('#new-arrivals-list').html('<p class="text-center">Loading new arrivals...</p>'); // Show loading message
            $.ajax({
                url: '/api/products', // Hypothetical endpoint for new arrivals
                method: 'GET',
                success: function(data) {
                    $('#new-arrivals-list').empty().append(data.map(product => `
                        <div class="col-md-3">
                            <div class="card product-card h-100 border-0 shadow-sm">
                                <div class="badge position-absolute top-0 end-0 m-2">New</div>
                                <img src="${product.image || 'assets/img/product.png'}"
                                     class="card-img-top" alt="${product.name}" style ="height: 150px;">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="text-muted">${product.description}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">$${product.price}</span>
                                        <button class="btn btn-warning btn-sm view-details">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Failed to load new arrivals:", textStatus, errorThrown);
                    if (retryCount < 2) { // You may adjust the number of retries
                        loadNewArrivals(retryCount + 1); // Retry
                    } else {
                        $('#new-arrivals-list').html('<p class="text-danger text-center">Failed to load new arrivals. Please try again later.</p>');
                    }
                }
            });
        }

        loadCategories();
        loadProducts();
        loadNewArrivals();

        // Initialize sort order change
        $('.dropdown-item').on('click', function(e) {
            e.preventDefault();
            const sortOrder = $(this).data('sort');
            loadProducts(sortOrder);
        });
    });
</script>
@endsection
