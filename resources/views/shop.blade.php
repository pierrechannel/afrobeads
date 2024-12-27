@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<!-- Include Bootstrap 5 CSS, Icons, and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<style>
:root {
    --bs-primary: #0967D2;
    --bs-primary-rgb: 9, 103, 210;
    --bs-secondary: #616E7C;
    --bs-success: #31C48D;
    --bs-danger: #F05252;
    --bs-warning: #FF9800;
    --bs-light: #F5F7FA;
    --bs-dark: #323F4B;
}

/* Global Styles */
body {
    background-color: var(--bs-light);
    color: var(--bs-dark);
}

/* Cards */
.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Product Card */
.product-card {
    height: 100%;
    position: relative;
    overflow: hidden;
}

.product-image-wrapper {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
    background-color: #fff;
}

.product-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-wrapper img {
    transform: scale(1.05);
}

/* Badges */
.sale-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

/* Wishlist Button */
.wishlist-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.wishlist-btn:hover {
    background-color: var(--bs-danger);
    color: white;
}

/* Filter Sidebar */
.filter-sidebar .card {
    margin-bottom: 1rem;
}

.color-swatch {
    display: inline-block;
    width: 30px;
    height: 30px;
    margin: 0.25rem;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.color-swatch:hover, .color-swatch.selected {
    transform: scale(1.1);
    border-color: var(--bs-primary);
}

/* Rating Stars */
.rating-stars {
    color: var(--bs-warning);
}

/* Custom Range Slider */
.form-range::-webkit-slider-thumb {
    background: var(--bs-primary);
}

/* Pagination */
.pagination .page-link {
    color: var(--bs-primary);
    border: none;
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border-radius: 0.375rem;
}

.pagination .page-item.active .page-link {
    background-color: var(--bs-primary);
    color: white;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 0.5rem;
}

/* Search Input */
.search-input-group {
    position: relative;
}

.search-input-group .form-control {
    padding-right: 40px;
    border-radius: 0.375rem;
}

.search-input-group .search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bs-secondary);
    z-index: 4;
}

/* Responsive */
@media (max-width: 992px) {
    .filter-sidebar {
        margin-bottom: 2rem;
    }
}

/* Price Display */
.price-wrapper {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
}

.price-original {
    text-decoration: line-through;
    color: var(--bs-secondary);
    font-size: 0.875rem;
}

.price-current {
    font-weight: 600;
    color: var(--bs-primary);
    font-size: 1.125rem;
}

/* Stock Badge */
.stock-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>

<main class="container py-4">
    <div class="row g-4">
        <!-- Filter Sidebar -->
        <aside class="col-lg-3">
            <div class="filter-sidebar">
                <!-- Search -->
                <div class="card">
                    <div class="card-body">
                        <div class="search-input-group">
                            <input type="text" class="form-control" placeholder="Search products...">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Categories</h6>
                        <div class="categories" id="categories"></div>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Price Range</h6>
                        <div class="price-range-wrapper">
                            <input type="range" class="form-range mb-3" min="0" max="1000" value="500">
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" placeholder="Min">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" placeholder="Max">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colors -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Colors</h6>
                        <div id="color-palette" class="d-flex flex-wrap"></div>
                    </div>
                </div>

                <!-- Ratings -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Ratings</h6>
                        <div id="ratings"></div>
                    </div>
                </div>

                <button class="btn btn-primary w-100">Apply Filters</button>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">Products</h4>
                    <p class="text-muted mb-0">Showing 1-12 of 36 products</p>
                </div>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm">
                        <option>Sort by: Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest First</option>
                    </select>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-grid"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="product-grid">
                <!-- Products will be loaded here -->
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</main>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalProductImage" class="img-fluid rounded" src="" alt="">
                    </div>
                    <div class="col-md-6">
                        <h4 id="modalProductName"></h4>
                        <p id="modalProductDescription" class="text-muted"></p>
                        <div class="mb-3">
                            <span id="modalProductPrice" class="h4"></span>
                            <span id="modalProductStock" class="badge bg-success ms-2"></span>
                        </div>
                        <div class="d-grid">
                            <button id="addToCartButton" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

// Add this script section after your existing script tags

<script>
$(document).ready(function() {
    // Global variables
    let currentProducts = [];
    let filters = {
        categories: [],
        priceRange: { min: 0, max: 1000 },
        colors: [],
        rating: null,
        search: '',
        sort: 'featured'
    };

    // Initialize tooltips and popovers
    $('[data-bs-toggle="tooltip"]').tooltip();
    $('[data-bs-toggle="popover"]').popover();

    // Initialize the page
    function initializePage() {
        fetchCategories();
        fetchProducts();
        generateColorPalette();
        generateRatings();
        setupEventListeners();
    }

    // Fetch categories from API
    function fetchCategories() {
        $.ajax({
            url: '/api/categories',
            method: 'GET',
            success: function(data) {
                renderCategories(data);
            },
            error: function(error) {
                console.error('Error fetching categories:', error);
                showToast('Error loading categories', 'error');
            }
        });
    }

    // Render categories in sidebar
    function renderCategories(categories) {
        let html = '';
        categories.forEach(category => {
            html += `
                <div class="form-check">
                    <input class="form-check-input category-filter"
                           type="checkbox"
                           id="category_${category.id}"
                           value="${category.id}">
                    <label class="form-check-label d-flex justify-content-between"
                           for="category_${category.id}">
                        <span>${category.name}</span>
                        <span class="badge bg-light text-dark">${category.count}</span>
                    </label>
                </div>`;
        });
        $('#categories').html(html);
    }

    // Fetch products from API
    function fetchProducts() {
        const loadingHtml = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading products...</p>
            </div>`;
        $('#product-grid').html(loadingHtml);

        $.ajax({
            url: '/api/products',
            method: 'GET',
            data: filters,
            success: function(data) {
                currentProducts = data;
                renderProducts(data);
                updateProductCount(data.length);
            },
            error: function(error) {
                console.error('Error fetching products:', error);
                showToast('Error loading products', 'error');
            }
        });
    }

    // Render products in grid
    function renderProducts(products) {
        if (products.length === 0) {
            $('#product-grid').html(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h4 class="mt-3">No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search criteria</p>
                    <button class="btn btn-outline-primary mt-2" onclick="resetFilters()">
                        Reset Filters
                    </button>
                </div>`);
            return;
        }

        let html = '';
        products.forEach(product => {
            const discount = calculateDiscount(product.price, product.originalPrice);
            html += `
                <div class="col-md-4 col-sm-6">
                    <div class="card product-card" data-product-id="${product.id}">
                        <div class="product-image-wrapper">
                            <img src="${product.image || '/placeholder.jpg'}"
                                 alt="${product.name}"
                                 class="product-image"
                                 onerror="this.src='/placeholder.jpg'">
                            <button class="wishlist-btn" data-product-id="${product.id}">
                                <i class="bi bi-heart${product.isWishlisted ? '-fill' : ''}"></i>
                            </button>
                            ${discount ? `
                                <div class="badge bg-danger sale-badge">
                                    -${discount}%
                                </div>` : ''}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-truncate">${product.name}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="price-wrapper">
                                    ${product.originalPrice ? `
                                        <span class="price-original">$${product.originalPrice.toFixed(2)}</span>` : ''}
                                    <span class="price-current">$${product.price}</span>
                                </div>
                                <span class="badge ${product.stock > 0 ? 'bg-success' : 'bg-danger'} stock-badge">
                                    ${product.stock > 0 ? 'In Stock' : 'Out of Stock'}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="rating-stars">
                                    ${generateRatingStars(product.rating)}
                                </div>
                                <button class="btn btn-primary btn-sm view-details-btn">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        $('#product-grid').html(html);
    }

    // Generate color palette
    function generateColorPalette() {
        const colors = [
            { name: 'Black', hex: '#000000' },
            { name: 'White', hex: '#FFFFFF' },
            { name: 'Red', hex: '#FF0000' },
            { name: 'Blue', hex: '#0000FF' },
            { name: 'Green', hex: '#00FF00' },
            { name: 'Yellow', hex: '#FFFF00' }
        ];

        let html = '';
        colors.forEach(color => {
            html += `
                <div class="color-swatch"
                     style="background-color: ${color.hex}"
                     data-color="${color.name}"
                     data-bs-toggle="tooltip"
                     title="${color.name}"></div>`;
        });
        $('#color-palette').html(html);
    }

    // Generate rating filter options
    function generateRatings() {
        let html = '';
        for (let i = 5; i >= 1; i--) {
            html += `
                <div class="form-check">
                    <input class="form-check-input rating-filter"
                           type="radio"
                           name="rating"
                           id="rating${i}"
                           value="${i}">
                    <label class="form-check-label" for="rating${i}">
                        ${generateRatingStars(i)} & Up
                    </label>
                </div>`;
        }
        $('#ratings').html(html);
    }

    // Generate rating stars
    function generateRatingStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="bi bi-star${i <= rating ? '-fill' : ''} text-warning"></i>`;
        }
        return stars;
    }

    // Calculate discount percentage
    function calculateDiscount(currentPrice, originalPrice) {
        if (!originalPrice || originalPrice <= currentPrice) return null;
        return Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
    }

    // Set up event listeners
    function setupEventListeners() {
        // Search input
        let searchTimeout;
        $('.search-input').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filters.search = $(this).val();
                fetchProducts();
            }, 500);
        });

        // Category filters
        $(document).on('change', '.category-filter', function() {
            filters.categories = $('.category-filter:checked').map(function() {
                return $(this).val();
            }).get();
            fetchProducts();
        });

        // Color filters
        $(document).on('click', '.color-swatch', function() {
            $(this).toggleClass('selected');
            filters.colors = $('.color-swatch.selected').map(function() {
                return $(this).data('color');
            }).get();
            fetchProducts();
        });

        // Rating filter
        $(document).on('change', '.rating-filter', function() {
            filters.rating = $(this).val();
            fetchProducts();
        });

        // Price range
        const priceRangeSlider = document.querySelector('.form-range');
        if (priceRangeSlider) {
            priceRangeSlider.addEventListener('change', function() {
                filters.priceRange.max = this.value;
                fetchProducts();
            });
        }

        // Sort products
        $('.sort-select').on('change', function() {
            filters.sort = $(this).val();
            fetchProducts();
        });

        // View details button
        $(document).on('click', '.view-details-btn', function() {
            const productId = $(this).closest('.product-card').data('product-id');
            const product = currentProducts.find(p => p.id === productId);
            if (product) {
                showProductModal(product);
            }
        });

        // Add to cart button
        $('#addToCartButton').on('click', function() {
            const productId = $(this).data('product-id');
            addToCart(productId);
        });

        // Wishlist button
        $(document).on('click', '.wishlist-btn', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            toggleWishlist(productId);
        });

        // Grid/List view toggle
        $('.view-toggle').on('click', function() {
            $('.view-toggle').removeClass('active');
            $(this).addClass('active');
            $('#product-grid').toggleClass('list-view', $(this).data('view') === 'list');
        });
    }

    // Show product modal
    function showProductModal(product) {
        $('#modalProductName').text(product.name);
        $('#modalProductImage').attr('src', product.image || '/placeholder.jpg');
        $('#modalProductDescription').text(product.description);
        $('#modalProductPrice').text(`$${product.price.toFixed(2)}`);
        $('#modalProductStock')
            .text(product.stock > 0 ? 'In Stock' : 'Out of Stock')
            .removeClass('bg-success bg-danger')
            .addClass(product.stock > 0 ? 'bg-success' : 'bg-danger');
        $('#addToCartButton').data('product-id', product.id);
        $('#productModal').modal('show');
    }

    // Add to cart function
    function addToCart(productId) {
        const product = currentProducts.find(p => p.id === productId);
        if (!product) return;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingItem = cart.find(item => item.id === productId);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                image: product.image
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        showToast('Product added to cart', 'success');
    }

    // Toggle wishlist function
    function toggleWishlist(productId) {
        const button = $(`.wishlist-btn[data-product-id="${productId}"]`);
        const icon = button.find('i');
        const isWishlisted = icon.hasClass('bi-heart-fill');

        if (isWishlisted) {
            icon.removeClass('bi-heart-fill').addClass('bi-heart');
            showToast('Removed from wishlist', 'success');
        } else {
            icon.removeClass('bi-heart').addClass('bi-heart-fill');
            showToast('Added to wishlist', 'success');
        }
    }

    // Update cart count
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const count = cart.reduce((total, item) => total + item.quantity, 0);
        $('.cart-count').text(count);
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                </div>
            </div>`;

        const toastElement = $(toast);
        $('.toast-container').append(toastElement);
        const bsToast = new bootstrap.Toast(toastElement);
        bsToast.show();

        setTimeout(() => {
            toastElement.remove();
        }, 3000);
    }

    // Update product count display
    function updateProductCount(count) {
        $('.product-count').text(`Showing ${count} products`);
    }

    // Reset all filters
    function resetFilters() {
        filters = {
            categories: [],
            priceRange: { min: 0, max: 1000 },
            colors: [],
            rating: null,
            search: '',
            sort: 'featured'
        };

        // Reset UI
        $('.category-filter').prop('checked', false);
        $('.color-swatch').removeClass('selected');
        $('.rating-filter').prop('checked', false);
        $('.search-input').val('');
        $('.form-range').val(1000);
        $('.sort-select').val('featured');

        fetchProducts();
    }

    // Initialize the page
    initializePage();
});
</script>
@endsection



