@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Sales Management</h5>
            <button class="btn btn-primary mb-3" id="addSaleBtn">
                <i class="fas fa-plus-circle"></i> Create New Sale
            </button>
            <table class="table table-bordered table-striped table-hover" id="sale-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Store</th>
                        <th>Customer</th>
                        <th>Total Price</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

  {{-- Enhanced Modal for Add/Edit Sale --}}
<div class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="saleModalLabel">
                    <i class="fas fa-shopping-cart me-2"></i> New Sale Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="saleForm" novalidate>
                    <input type="hidden" id="saleId" name="saleId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customerId" class="form-label">
                                <i class="fas fa-user me-2"></i>Customer
                            </label>
                            <div class="input-group">
                                <select class="form-select" id="customerId" name="customerId" required>
                                    <option value="">Select Customer</option>
                                </select>
                                <button type="button" class="btn btn-secondary" id="addCustomerBtn" title="Add New Customer">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Please select a customer.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="isPaid" class="form-label">
                                <i class="fas fa-money-check me-2"></i>Payment Status
                            </label>
                            <select class="form-select" id="isPaid" name="isPaid" required>
                                <option value="">Select Payment Status</option>
                                <option value="1">Paid</option>
                                <option value="0">Pending</option>
                            </select>
                            <div class="invalid-feedback">Please select payment status.</div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-list-alt me-2"></i>Sale Items
                            </h6>
                            <button type="button" class="btn btn-sm btn-success" id="addSaleItemBtn">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body" id="saleItemsContainer">
                            <div id="saleItemsList">
                                <!-- Dynamically added sale items -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="totalPrice" class="form-label">
                                        <i class="fas fa-receipt me-2"></i>Total Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control disabled" id="totalPrice" name="totalPrice" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveSaleBtn">
                    <i class="fas fa-save me-2"></i>Save Sale
                </button>
            </div>
        </div>
    </div>

</div>
 <!-- Customer Modal -->
 <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="customerModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="customerForm" novalidate>
                    <input type="hidden" id="customerId" value="">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">
                            <i class="fas fa-user me-2"></i>Name
                        </label>
                        <input type="text" class="form-control" id="customerName" placeholder="Enter name" required>
                        <div class="invalid-feedback">Please enter customer name</div>
                    </div>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control" id="customerEmail" placeholder="Enter email" required>
                        <div class="invalid-feedback">Please enter a valid email</div>
                    </div>
                    <div class="mb-3">
                        <label for="customerPhone" class="form-label">
                            <i class="fas fa-phone me-2"></i>Phone
                        </label>
                        <input type="tel" class="form-control" id="customerPhone" placeholder="Enter phone number" required pattern="[0-9]{10}">
                        <div class="invalid-feedback">Please enter a valid 10-digit phone number</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="saveCustomerBtn">
                    <i class="fas fa-save me-2"></i>Save Customer
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const saleTable = initializeDataTable();
    initializeSaleForm();

    function initializeDataTable() {
        return $('#sale-table').DataTable({
            ajax: {
                url: '/api/sales',
                dataSrc: 'data'
            },
            columns: [
                { data: 'id' },
                { data: 'store.name' },
                { data: 'customer.name' },
                {
                    data: 'total_price',
                    render: $.fn.dataTable.render.number(',', '.', 2, '$')
                },
                {
                    data: 'is_paid',
                    render: data => `<span class="badge ${data ? 'bg-success' : 'bg-warning'}">
                        ${data ? 'Paid' : 'Pending'}
                    </span>`
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-sale" data-id="${data.id}" title="Edit Sale">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-sale" data-id="${data.id}" title="Delete Sale">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            processing: true,
            error: function(xhr, error, thrown) {
                console.error('Error fetching sales data:', xhr.responseText);
            }
        });
    }

    // Event delegation for edit and delete buttons
    $('#sale-table tbody').on('click', '.edit-sale', function () {
        const saleId = $(this).data('id');
        editSale(saleId);
    });

    $('#sale-table tbody').on('click', '.delete-sale', function () {
        const saleId = $(this).data('id');
        deleteSale(saleId);
    });

    function editSale(saleId) {
        // Fetch the sale details from the API
        $.get(`/api/sales/${saleId}`, function(data) {
            // Populate the form with the sale data
            $('#saleModalLabel').text('Edit Sale');
            $('#customerId').val(data.customer_id);
            $('#totalPrice').val(data.total_price);
            $('#saleItemsList').empty(); // Clear existing items

            data.sale_items.forEach(item => {
                addSaleItemRow(item); // Create a function to add item rows
            });

            $('#saleModal').modal('show');
        }).fail(handleAjaxError);
    }

    function deleteSale(saleId) {
        if (confirm('Are you sure you want to delete this sale?')) {
            $.ajax({
                url: `/api/sales/${saleId}`,
                method: 'DELETE',
                success: function() {
                    saleTable.ajax.reload();
                    alert('Sale deleted successfully.');
                },
                error: handleAjaxError
            });
        }
    }

    function initializeSaleForm() {
        const $form = $('#saleForm');
        const $modal = $('#saleModal');
        const $errorMessages = $('#errorMessages');
        const $productSelect = $('<select class="form-select product-select" required><option value="">Select Product</option></select>');

        function resetForm() {
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#saleItemsList').empty();
            updateTotalPrice(0);
            loadCustomers();
        }

        function loadCustomers() {
            $.get('/api/customers', function(customers) {
                const $customerSelect = $('#customerId');
                $customerSelect.empty().append('<option value="">Select Customer</option>');
                customers.forEach(customer => {
                    $customerSelect.append(`<option value="${customer.id}">${customer.name}</option>`);
                });
            }).fail(handleAjaxError);
        }

        function addSaleItemRow(item = { product_id: '', quantity: 1, price: 0 }) {
            const rowHtml = `
                <div class="sale-item card mb-2">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                ${$productSelect.prop('outerHTML')}
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="quantity" class="form-control item-quantity" placeholder="Qty" min="1" required value="${item.quantity}">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="price" class="form-control item-price" placeholder="Price" min="0.01" step="0.01" required readonly value="${item.price}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control item-total" placeholder="Total" readonly value="${(item.quantity * item.price).toFixed(2)}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-sale-item w-100">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#saleItemsList').append(rowHtml);
            loadProducts(); // Ensure products are loaded into the select
        }

        function loadProducts() {
            $.get('/api/products', function(products) {
                const $productSelects = $('#saleItemsList .product-select');
                $.each($productSelects, function(index, productSelect) {
                    const $select = $(productSelect);
                    $select.empty().append('<option value="">Select Product</option>');
                    products.forEach(product => {
                        $select.append(`<option value="${product.id}" data-price="${parseFloat(product.price)}">${product.name}</option>`);
                    });
                });
            }).fail(handleAjaxError);
        }

        $('#saleItemsList').on('change', '.product-select', function() {
            const $row = $(this).closest('.sale-item');
            const selectedOption = $(this).find("option:selected");

            const price = parseFloat(selectedOption.data('price'));
            if (!isNaN(price)) {
                $row.find('.item-price').val(price.toFixed(2));
                const quantity = parseFloat($row.find('.item-quantity').val()) || 0;
                $row.find('.item-total').val((quantity * price).toFixed(2));
            } else {
                $row.find('.item-price').val('');
                $row.find('.item-total').val('');
            }
            updateTotalPrice();
        });

        function updateTotalPrice() {
            const total = $('.item-total').toArray()
                .reduce((sum, el) => sum + (parseFloat($(el).val()) || 0), 0);
            $('#totalPrice').val(total.toFixed(2));
        }

        $('#addSaleItemBtn').on('click', function() {
            addSaleItemRow();
        });

        $('#saleItemsList').on('input', '.item-quantity', function() {
            const $row = $(this).closest('.sale-item');
            const quantity = parseFloat($(this).val()) || 0;
            const price = parseFloat($row.find('.item-price').val()) || 0;
            $row.find('.item-total').val((quantity * price).toFixed(2));
            updateTotalPrice();
        });

        $('#saleItemsList').on('click', '.remove-sale-item', function() {
            $(this).closest('.sale-item').remove();
            updateTotalPrice();
        });

        $('#saveSaleBtn').on('click', function() {
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            const saleData = {
                store_id: 1,
                user_id: 3,
                customer_id: $('#customerId').val(),
                is_paid: $('#isPaid').val(),
                total_price: $('#totalPrice').val(),
                sale_items: []
            };

            $('#saleItemsList .sale-item').each(function() {
                saleData.sale_items.push({
                    product_id: $(this).find('.product-select').val(),
                    quantity: $(this).find('.item-quantity').val(),
                    price: $(this).find('.item-price').val()
                });
            });

            $.ajax({
                url: '/api/sales',
                method: 'POST',
                data: JSON.stringify(saleData),
                contentType: 'application/json',
                success: function(response) {
                    saleTable.ajax.reload();
                    $modal.modal('hide');
                    resetForm();
                },
                error: handleAjaxError
            });
        });

        $('#addSaleBtn').on('click', function() {
            $modal.modal('show');
            $('#saleModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Create New Sale');
            resetForm();
        });

        function handleAjaxError(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'An unexpected error occurred';
            $errorMessages.text(errorMsg).show();
        }
    }
});
</script>
@endsection
