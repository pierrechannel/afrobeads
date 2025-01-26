@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .cart-item {
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        background-color: #f8f9fa;
    }

    .quantity-input {
        width: 60px !important;
    }

    .btn-quantity {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .alert-floating {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        min-width: 300px;
    }

    .product-image {
        object-fit: cover;
        height: 120px;
        width: 120px;
    }

    .order-summary {
        position: sticky;
        top: 20px;
    }

    .fade-enter {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<main class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold">Shopping Cart</h1>
            <p class="text-muted lead">Review your items and checkout when ready</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="cart-items" class="list-group list-group-flush">
                        <div class="p-4 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading cart...</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <button id="clearCartButton" class="btn btn-outline-danger d-flex align-items-center">
                            <i class="bi bi-trash me-2"></i>
                            Clear Cart
                        </button>
                        <div class="text-end">
                            <small class="text-muted">Total</small>
                            <h3 id="cartTotal" class="mb-0 fw-bold">$0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm order-summary">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span id="subtotal" class="fw-bold">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-bold">Calculated at checkout</span>
                        </div>
                        <hr>
                        <button id="checkoutButton" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <span class="me-2">Proceed to Checkout</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        Free shipping on orders over $50!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alertArea" class="alert-floating"></div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        function updateCart() {
            $.get('/api/cart', function(response) {
                let cartHtml = '';
                let total = 0;

                if (response.cart_items.length === 0) {
                    cartHtml = `
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                            <h4 class="text-muted">Your cart is empty</h4>
                            <p class="mb-4">Looks like you haven't added any items yet.</p>
                            <a href="/shop" class="btn btn-primary">Continue Shopping</a>
                        </div>`;
                } else {
                    response.cart_items.forEach(item => {
                        const itemTotal = item.variant.getFinalPriceAttribute * item.quantity;
                        total += itemTotal;
                        cartHtml += `
                            <div class="cart-item list-group-item py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="${item.variant.product.images[0] || 'assets/img/card.jpg'}" alt="${item.variant.product.name}"
                                             class="product-image rounded">
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mb-1">${item.variant.product.name}</h5>
                                        <p class="text-muted mb-0">Price: $${item.variant.getFinalPriceAttribute}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-quantity update-quantity"
                                                    data-id="${item.id}" data-change="-1">−</button>
                                            <input type="number" class="form-control quantity-input text-center"
                                                   value="${item.quantity}" min="1" data-id="${item.id}">
                                            <button class="btn btn-outline-secondary btn-quantity update-quantity"
                                                    data-id="${item.id}" data-change="1">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <h5 class="mb-0">$${itemTotal}</h5>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button class="btn btn-link text-danger remove-item p-0" data-id="${item.id}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                    });
                }

                $('#cart-items').html(cartHtml);
                $('#cartTotal, #subtotal').text(`$${total.toFixed(2)}`);
            });
        }

        // Update Quantity
        $(document).on('click', '.update-quantity', function() {
            const itemId = $(this).data('id');
            const change = parseInt($(this).data('change'));
            const inputField = $(this).closest('.input-group').find('.quantity-input');
            const newQuantity = parseInt(inputField.val()) + change;

            if (newQuantity > 0) {
                $.ajax({
                    url: `/api/cart/${itemId}`,
                    method: 'PUT',
                    data: { quantity: newQuantity },
                    success: function() {
                        updateCart();
                    },
                    error: function() {
                        showAlert("Failed to update item quantity", "error");
                    }
                });
            }
        });

        // Remove Item
        $(document).on('click', '.remove-item', function() {
            const itemId = $(this).data('id');
            $.ajax({
                url: `/api/cart/${itemId}`,
                method: 'DELETE',
                success: function() {
                    updateCart();
                    showAlert("Item removed from cart", "success");
                },
                error: function() {
                    showAlert("Failed to remove item from cart", "error");
                }
            });
        });

        // Clear Cart
        $('#clearCartButton').on('click', function() {
            if (confirm('Are you sure you want to clear your cart?')) {
                $.ajax({
                    url: `/api/cart`,
                    method: 'DELETE',
                    success: function() {
                        updateCart();
                        showAlert("Cart cleared", "success");
                    },
                    error: function() {
                        showAlert("Failed to clear cart", "error");
                    }
                });
            }
        });

        // Checkout
        $('#checkoutButton').on('click', function() {
            $.get('/api/cart', function(response) {
                if (response.cart_items.length === 0) {
                    showAlert("Your cart is empty", "error");
                } else {
                    window.location.href = '/checkout';
                }
            });
        });

        // Display Alerts
        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alert = `
                <div class="alert ${alertClass} alert-dismissible fade show shadow-sm fade-enter" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            const alertElement = $(alert).appendTo('#alertArea');
            setTimeout(() => {
                alertElement.alert('close');
            }, 3000);
        }

        // Initialize cart on page load
        updateCart();
    });
</script>
@endsection
