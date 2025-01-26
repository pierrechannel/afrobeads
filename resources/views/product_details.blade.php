@extends('layouts.app')

@section('title', 'Product Details')

@section('content')


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .product-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
        }
        .carousel-item img {
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
        }
        .product-details {
            padding-left: 30px;
        }
        .price-tag {
            color: #007bff;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .related-product-card {
            transition: transform 0.3s ease;
        }
        .related-product-card:hover {
            transform: scale(1.05);
        }
        .related-product-card img {
            height: 250px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-container">
            <h1 class="text-center mb-4">Product Details</h1>

            <div id="loading" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div id="product-info" class="row" style="display: none;">
                <!-- Product content will be dynamically inserted here -->
            </div>

            <div id="related-products" class="mt-5" style="display: none;">
                <h3 class="text-center mb-4">Related Products</h3>
                <div class="row" id="related-products-list">
                    <!-- Related products will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        const productId = 1; // Dynamic product ID

        $.ajax({
            url: `/api/products/${productId}`,
            method: 'GET',
            success: function(response) {
                $('#loading').hide();
                $('#product-info').show();

                const product = response.product;
                const imagesHtml = product.images.map((image, index) => `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <img src="storage/${image.image_url}" class="d-block w-100" alt="${product.name}">
                    </div>
                `).join('');

                const primaryImageIndicators = product.images.map((image, index) => `
                    <button type="button" data-bs-target="#productCarousel"
                            data-bs-slide-to="${index}"
                            class="${index === 0 ? 'active' : ''}"
                            aria-current="${index === 0}">
                    </button>
                `).join('');

                $('#product-info').html(`
                    <div class="col-md-6">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                ${primaryImageIndicators}
                            </div>
                            <div class="carousel-inner">
                                ${imagesHtml}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 product-details">
                        <h2 class="mb-3">${product.name}</h2>
                        <p class="text-muted mb-2"><strong>Category:</strong> ${product.category.name}</p>
                        <p class="price-tag mb-3">$${product.base_price}</p>

                        <h5 class="mt-4">Description</h5>
                        <p class="text-secondary">${product.description}</p>

                        <div class="d-flex align-items-center mt-4">
                            <input type="number" class="form-control me-3" style="width: 100px;" value="1" min="1">
                            <button id="addToCart" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                `);

                // Fetch and display related products
                $.ajax({
                    url: `/api/products/${productId}/related`,
                    method: 'GET',
                    success: function(relatedResponse) {
                        $('#related-products').show();
                        const relatedProductsHtml = relatedResponse.related_products.map(relatedProduct => `
                            <div class="col-md-4 mb-4">
                                <div class="card related-product-card">
                                    <img src="storage/${relatedProduct.primary_image}" class="card-img-top" alt="${relatedProduct.name}">
                                    <div class="card-body">
                                        <h5 class="card-title">${relatedProduct.name}</h5>
                                        <p class="card-text text-muted">${relatedProduct.category.name}</p>
                                        <p class="card-text price-tag">$${relatedProduct.base_price}</p>
                                        <a href="/product/${relatedProduct.id}" class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        $('#related-products-list').html(relatedProductsHtml);
                    },
                    error: function() {
                        $('#related-products').hide();
                    }
                });
            },
            error: function() {
                $('#loading').hide();
                $('#product-info').html(`
                    <div class="col-12 text-center">
                        <p class="text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error retrieving product details. Please try again later.
                        </p>
                    </div>
                `);
            }
        });

        $(document).on('click', '#addToCart', function() {
            const quantity = $('input[type="number"]').val();

            $.ajax({
                url: '/add-to-cart',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: `${quantity} item(s) added successfully.`
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error adding product to cart.'
                    });
                }
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
