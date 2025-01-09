@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Stock Management</h5>
            <button class="btn btn-primary mb-3" id="addStockBtn">
                <i class="fas fa-plus-circle"></i> Create New Stock
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <table class="table table-bordered table-striped table-hover" id="stock-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Store Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Modal -->
    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="stockModalLabel">
                        <i class="fas fa-box me-2"></i>Create New Stock Entry
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="stockForm" novalidate>
                        <input type="hidden" id="stockId" name="stockId">

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="">Select a product</option>
                                <!-- Products will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a product.</div>
                        </div>

                        <div class="mb-3">
                            <label for="store_id" class="form-label">Store</label>
                            <select class="form-select" id="store_id" name="store_id" required>
                                <option value="">Select a store</option>
                                <!-- Stores will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a store.</div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="0">
                            <div class="invalid-feedback">Please enter a valid quantity.</div>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveStockBtn">
                        <i class="fas fa-save me-2"></i>Save Stock
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stockTable = initializeDataTable();
    initializeStockForm();

    function showGlobalMessage(message, type = 'info') {
        const messageDiv = $('#globalMessage');
        messageDiv.removeClass('alert-info alert-success alert-danger')
            .addClass(`alert-${type}`)
            .text(message)
            .show();

        setTimeout(() => {
            messageDiv.fadeOut(300);
        }, 5000);
    }

    function initializeDataTable() {
        return $('#stock-table').DataTable({
            ajax: {
                url: '/api/stocks',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load stocks. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'product.name' },
                { data: 'store.name' },
                { data: 'quantity' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-stock" data-id="${data.id}" title="Edit Stock">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-stock" data-id="${data.id}" title="Delete Stock">
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
                emptyTable: 'No stocks found',
                zeroRecords: 'No matching stocks found'
            }
        });
    }

    function initializeStockForm() {
        const $form = $('#stockForm');
        const $modal = $('#stockModal');
        const $errorMessages = $('#errorMessages');

        function resetForm() {
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#stockId').val('');
            $('#stockModalLabel').text('Create New Stock Entry');
            loadProducts();
            loadStores();
        }

        function loadProducts() {
            $.ajax({
                url: '/api/products', // Change to your endpoint for products
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(data) {
                const productSelect = $('#product_id');
                productSelect.empty().append('<option value="">Select a product</option>');
                data.forEach(product => {
                    productSelect.append(`<option value="${product.id}">${product.name}</option>`);
                });
            })
            .fail(function(xhr) {
                console.error('Error loading products:', xhr);
                handleAjaxError(xhr);
            });
        }

        function loadStores() {
            $.ajax({
                url: '/api/stores', // Change to your endpoint for stores
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(data) {
                const storeSelect = $('#store_id');
                storeSelect.empty().append('<option value="">Select a store</option>');
                data.forEach(store => {
                    storeSelect.append(`<option value="${store.id}">${store.name}</option>`);
                });
            })
            .fail(function(xhr) {
                console.error('Error loading stores:', xhr);
                handleAjaxError(xhr);
            });
        }

        // Add Stock Button
        $('#addStockBtn').on('click', function() {
            resetForm();
            $modal.modal('show');
        });

        // Edit Stock
        $('#stock-table').on('click', '.edit-stock', function() {
            const stockId = $(this).data('id');

            $.get(`/api/stocks/${stockId}`, {
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(response) {
                $('#stockId').val(response.id);
                $('#product_id').val(response.product_id);
                $('#store_id').val(response.store_id);
                $('#quantity').val(response.quantity);
                $('#stockModalLabel').text('Edit Stock Entry');
                $modal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch stock data:', xhr);
                showGlobalMessage(`Error fetching stock data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`, 'danger');
            });
        });

        // Delete Stock
        $('#stock-table').on('click', '.delete-stock', function() {
            const stockId = $(this).data('id');
            if (confirm('Are you sure you want to delete this stock?')) {
                $.ajax({
                    url: `/api/stocks/${stockId}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                    },
                    success: function() {
                        stockTable.ajax.reload();
                        showGlobalMessage('Stock deleted successfully', 'success');
                    },
                    error: handleAjaxError
                });
            }
        });

        // Save Stock
        $('#saveStockBtn').on('click', function() {
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            const stockId = $('#stockId').val();
            const method = stockId ? 'PUT' : 'POST';
            const url = stockId ? `/api/stocks/${stockId}` : '/api/stocks';

            const stockData = {
                product_id: $('#product_id').val(),
                store_id: $('#store_id').val(),
                quantity: parseInt($('#quantity').val()) // Ensure quantity is a number
            };

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                data: JSON.stringify(stockData),
                contentType: 'application/json',
                success: function(response) {
                    stockTable.ajax.reload();
                    $modal.modal('hide');
                    resetForm();
                    showGlobalMessage('Stock saved successfully', 'success');
                },
                error: handleAjaxError
            });
        });

        function handleAjaxError(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'An unexpected error occurred';
            $errorMessages.hide().empty();

            if (xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                let detailedErrors = '';
                for (const key in errors) {
                    detailedErrors += `<strong>${key}:</strong> ${errors[key].join(', ')}<br>`;
                }
                $errorMessages.html(detailedErrors).show();
            } else {
                $errorMessages.text(errorMsg).show();
            }

            showGlobalMessage(errorMsg, 'danger');
        }
    }
});
</script>
@endsection
