@extends('layouts.app')

@section('title', 'Product Details')

@section('custom-styles')
<style>
    .product-gallery img {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    .product-gallery img.active {
        border-color: #000;
    }
    .quantity-input {
        width: 100px;
    }
    .size-option input[type="radio"] {
        display: none;
    }
    .size-option label {
        padding: 8px 20px;
        border: 1px solid #dee2e6;
        cursor: pointer;
        margin-right: 10px;
        border-radius: 4px;
    }
    .size-option input[type="radio"]:checked + label {
        background-color: #212529;
        color: white;
        border-color: #212529;
    }
    .color-option input[type="radio"] {
        display: none;
    }
    .color-option label {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        position: relative;
    }
    .color-option input[type="radio"]:checked + label::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 16px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
@endsection

@section('content')
<style>
    .product-gallery img {
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    .product-gallery img.active {
        border-color: #000;
    }
    .quantity-input {
        width: 100px;
    }
    .size-option input[type="radio"] {
        display: none;
    }
    .size-option label {
        padding: 8px 20px;
        border: 1px solid #dee2e6;
        cursor: pointer;
        margin-right: 10px;
        border-radius: 4px;
    }
    .size-option input[type="radio"]:checked + label {
        background-color: #212529;
        color: white;
        border-color: #212529;
    }
    .color-option input[type="radio"] {
        display: none;
    }
    .color-option label {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        position: relative;
    }
    .color-option input[type="radio"]:checked + label::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 16px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page" id="product-name-bread"></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="main-image mb-3">
                <img id="main-product-image" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="product-gallery d-flex gap-2">
                <!-- Thumbnail images will be loaded here -->
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 id="product-name" class="mb-3"></h1>

            <!-- Price -->
            <div class="mb-4">
                <span class="h3 me-2" id="product-price"></span>
                <span class="text-muted text-decoration-line-through" id="product-original-price"></span>
                <span class="badge bg-danger ms-2">Sale</span>
            </div>

            <!-- Rating -->
            <div class="mb-4">
                <div class="text-warning mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="text-dark ms-2">4.5/5</span>
                </div>
                <a href="#reviews" class="text-decoration-none">Read 24 Reviews</a>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5>Description</h5>
                <p id="product-description" class="text-muted"></p>
            </div>

            <!-- Color Selection -->
            <div class="mb-4">
                <h5>Color</h5>
                <div class="color-options">
                    <div class="color-option d-inline-block">
                        <input type="radio" name="color" id="color-red" value="red">
                        <label for="color-red" style="background-color: #dc3545;"></label>
                    </div>
                    <div class="color-option d-inline-block">
                        <input type="radio" name="color" id="color-blue" value="blue">
                        <label for="color-blue" style="background-color: #0d6efd;"></label>
                    </div>
                    <div class="color-option d-inline-block">
                        <input type="radio" name="color" id="color-black" value="black">
                        <label for="color-black" style="background-color: #212529;"></label>
                    </div>
                </div>
            </div>

            <!-- Size Selection -->
            <div class="mb-4">
                <h5>Size</h5>
                <div class="size-options">
                    <div class="size-option d-inline-block">
                        <input type="radio" name="size" id="size-s" value="S">
                        <label for="size-s">S</label>
                    </div>
                    <div class="size-option d-inline-block">
                        <input type="radio" name="size" id="size-m" value="M">
                        <label for="size-m">M</label>
                    </div>
                    <div class="size-option d-inline-block">
                        <input type="radio" name="size" id="size-l" value="L">
                        <label for="size-l">L</label>
                    </div>
                    <div class="size-option d-inline-block">
                        <input type="radio" name="size" id="size-xl" value="XL">
                        <label for="size-xl">XL</label>
                    </div>
                </div>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <h5>Quantity</h5>
                <div class="input-group quantity-input">
                    <button class="btn btn-outline-dark" type="button" id="decrease-quantity">-</button>
                    <input type="number" class="form-control text-center" id="quantity" value="1" min="1">
                    <button class="btn btn-outline-dark" type="button" id="increase-quantity">+</button>
                </div>
            </div>

            <!-- Add to Cart -->
            <div class="mb-4">
                <button class="btn btn-dark btn-lg me-2" id="add-to-cart">
                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                </button>
                <button class="btn btn-outline-dark btn-lg" id="add-to-wishlist">
                    <i class="fas fa-heart me-2"></i>Add to Wishlist
                </button>
            </div>

            <!-- Additional Info -->
            <div class="border-top pt-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">SKU</small>
                        <span id="product-sku"></span>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">Category</small>
                        <span id="product-category"></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Share</small>
                        <div class="mt-2">
                            <a href="#" class="text-dark me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-dark me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark me-3"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="text-dark"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description" role="tab">
                        Description
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="additional-info-tab" data-bs-toggle="tab" href="#additional-info" role="tab">
                        Additional Information
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab">
                        Reviews (24)
                    </a>
                </li>
            </ul>
            <div class="tab-content py-4" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div id="full-description"></div>
                </div>
                <div class="tab-pane fade" id="additional-info" role="tabpanel">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Weight</th>
                                <td id="product-weight"></td>
                            </tr>
                            <tr>
                                <th scope="row">Dimensions</th>
                                <td id="product-dimensions"></td>
                            </tr>
                            <tr>
                                <th scope="row">Materials</th>
                                <td id="product-materials"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <!-- Reviews will be loaded here -->
                    <div id="reviews-container"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row" id="related-products"></div>
        </div>
    </div>
</div>

<!-- Added to Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Added to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <img id="modal-product-image" src="" alt="" class="img-fluid me-3" style="max-width: 100px;">
                    <div>
                        <h6 id="modal-product-name"></h6>
                        <p class="mb-0">
                            Quantity: <span id="modal-quantity"></span><br>
                            Price: <span id="modal-price"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Continue Shopping</button>
                <a href="/cart" class="btn btn-dark">View Cart</a>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.1.9/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Get product ID from URL
    const productId = window.location.pathname.split('/').pop();

    // Load product details
    loadProductDetails(productId);
    loadRelatedProducts(productId);
    loadReviews(productId);

    function loadProductDetails(id) {
        $.ajax({
            url: `/api/products/${id}`,
            method: 'GET',
            success: function(product) {
                // Update breadcrumb
                $('#product-name-bread').text(product.name);

                // Update product details
                $('#product-name').text(product.name);
                $('#product-price').text(`$${product.price}`);
                $('#product-original-price').text(`$${(product.price * 1.25).toFixed(2)}`);
                $('#product-description').text(product.description);
                $('#full-description').html(product.full_description);
                $('#product-sku').text(product.sku);
                $('#product-category').text(product.category);

                // Update main image
                $('#main-product-image').attr('src', product.image || 'assets/img/card.jpg');

                // Load gallery images
                const galleryHTML = product.gallery.map(img => `
                    <img src="${img}" alt="" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                `).join('');
                $('.product-gallery').html(galleryHTML);

                // Additional info tab
                $('#product-weight').text(product.weight);
                $('#product-dimensions').text(product.dimensions);
                $('#product-materials').text(product.materials);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load product details'
                });
            }
        });
    }

    function loadRelatedProducts(id) {
        $.ajax({
            url: `/api/products/${id}/related`,
            method: 'GET',
            success: function(products) {
                const productsHTML = products.map(product => `
                    <div class="col-md-3">
                        <div class="card h-100">
                            <img src="${product.image || 'assets/img/card.jpg'}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">$${product.price}</p>
                                <a href="/products/${product.id}" class="btn btn-outline-dark">View Details</a>
                            </div>
                        </div>
                    </div>
                `).join('');
                $('#related-products').html(productsHTML);
            }
        });
    }

    function loadReviews(id) {
        $.ajax({
            url: `/api/products/${id}`,
            method: 'GET',
            success: function(reviews) {
                const reviewsHTML = reviews.map(review => `
                    <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                ${Array(review.rating).fill('<i class="fas fa-star"></i>').join('')}
                                ${Array(5-review.rating).fill('<i class="far fa-star"></i>').join('')}
                            </div>
                            <strong class="me-2">${review.user_name}</strong>
                            <small class="text-muted">${review.date}</small>
                        </div>
                        <p class="mb-0">${review.comment}</p>
                        <hr>
                    </div>
                `).join('');
                $('#reviews-container').html(reviewsHTML);
            }
        });
    }

    // Gallery image click handler
    $(document).on('click', '.product-gallery img', function() {
        const newSrc = $(this).attr('src');
        $('#main-product-image').attr('src', newSrc);
        $('.product-gallery img').removeClass('active');
        $(this).addClass('active');
    });

    // Quantity handlers
    $('#decrease-quantity').click(function() {
        const quantity = parseInt($('#quantity').val());
        if (quantity > 1) {
            $('#quantity').val(quantity - 1);
        }
    });

    $('#increase-quantity').click(function() {
        const quantity = parseInt($('#quantity').val());
        $('#quantity').val(quantity + 1);
    });

    $('#quantity').on('change', function() {
        const value = parseInt($(this).val());
        if (value < 1) {
            $(this).val(1);
        }
    });

    // Add to Cart handler
    $('#add-to-cart').click(function() {
        const quantity = parseInt($('#quantity').val());
        const color = $('input[name="color"]:checked').val();
        const size = $('input[name="size"]:checked').val();

        // Validate selections
        if (!color || !size) {
            Swal.fire({
                icon: 'warning',
                title: 'Please Select Options',
                text: 'Please select both color and size before adding to cart'
            });
            return;
        }

        // Add to cart API call
        $.ajax({
            url: '/api/cart/add',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                color: color,
                size: size
            },
            success: function(response) {
                // Update modal content
                $('#modal-product-image').attr('src', $('#main-product-image').attr('src'));
                $('#modal-product-name').text($('#product-name').text());
                $('#modal-quantity').text(quantity);
                $('#modal-price').text($('#product-price').text());

                // Show modal
                $('#cartModal').modal('show');

                // Update cart count in header (if implemented)
                if (response.cart_count) {
                    $('.cart-count').text(response.cart_count);
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add item to cart. Please try again.'
                });
            }
        });
    });

    // Add to Wishlist handler
    $('#add-to-wishlist').click(function() {
        $.ajax({
            url: '/api/wishlist/add',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Wishlist',
                    text: 'This item has been added to your wishlist',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add item to wishlist. Please try again.'
                });
            }
        });
    });
});
</script>
@endsection
