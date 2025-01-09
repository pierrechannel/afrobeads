@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Customer Management</h5>
            <button class="btn btn-primary mb-3" id="addCustomerBtn">
                <i class="fas fa-plus-circle"></i> Create New Customer
            </button>
            <table class="table table-bordered table-striped table-hover" id="customer-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="customerModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Create New Customer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm" novalidate>
                        <input type="hidden" id="customerId" name="customerId">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-2"></i>Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter customer name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                            <div class="invalid-feedback">Please enter a valid phone number.</div>
                        </div>
                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveCustomerBtn">
                        <i class="fas fa-save me-2"></i>Save Customer
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
    console.log('Document Loaded: Initializing Customer Management');

    let isDataTableInitialized = false;
    const customerTable = initializeDataTable();
    initializeCustomerForm();

    function initializeDataTable() {
    // Check if DataTable exists and destroy it if so
    if ($.fn.DataTable.isDataTable('#customer-table')) {
        console.log('Destroying existing DataTable instance');
        $('#customer-table').DataTable().clear().destroy();
    }

    console.log('Initializing DataTable');
    const dataTable = $('#customer-table').DataTable({
        ajax: {
            url: '/api/customers',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', error, thrown);
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info edit-customer" data-id="${data.id}" title="Edit Customer">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-customer" data-id="${data.id}" title="Delete Customer">
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
            emptyTable: 'No customers found',
            zeroRecords: 'No matching customers found'
        }
    });

    return dataTable;
}

    function initializeCustomerForm() {
        const $form = $('#customerForm');
        const $modal = $('#customerModal');
        const $errorMessages = $('#errorMessages');

        function resetForm() {
            console.log('Resetting form');
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#customerId').val('');
            $('#customerModalLabel').html('<i class="fas fa-user-plus me-2"></i>Create New Customer');
        }

        $('#addCustomerBtn').on('click', function() {
            console.log('Add Customer button clicked');
            resetForm();
            $modal.modal('show');
        });

        $('#customer-table').on('click', '.edit-customer', function() {
            const customerId = $(this).data('id');
            console.log(`Editing customer with ID: ${customerId}`);

            // Fetch customer details for editing
            $.get(`/api/customers/${customerId}`)
                .done(function(response) {
                    console.log('Customer details fetched:', response);
                    $('#customerId').val(response.id);
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#phone').val(response.phone);
                    $('#customerModalLabel').html('<i class="fas fa-user-edit me-2"></i>Edit Customer');
                    $modal.modal('show');
                })
                .fail(function(xhr) {
                    console.error('Failed to fetch customer data:', xhr);
                    alert(`Error fetching customer data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`);
                });
        });

        $('#customer-table').on('click', '.delete-customer', function() {
            const customerId = $(this).data('id');
            console.log(`Attempting to delete customer with ID: ${customerId}`);

            if (confirm('Are you sure you want to delete this customer?')) {
                $.ajax({
                    url: `/api/customers/${customerId}`,
                    method: 'DELETE',
                    success: function() {
                        console.log(`Customer ${customerId} deleted successfully`);
                        customerTable.ajax.reload();
                        alert('Customer deleted successfully');
                    },
                    error: handleAjaxError
                });
            }
        });

        $('#saveCustomerBtn').on('click', function() {
            console.log('Save Customer button clicked');
            if (!$form[0].checkValidity()) {
                console.warn('Form validation failed');
                $form.addClass('was-validated');
                return;
            }

            const customerId = $('#customerId').val();
            const method = customerId ? 'PUT' : 'POST';
            const url = customerId ? `/api/customers/${customerId}` : '/api/customers';

            const customerData = {
                name: $('#name').val().trim(),
                email: $('#email').val().trim(),
                phone: $('#phone').val().trim()
            };

            console.log('Sending customer data:', customerData);

            $.ajax({
                url: url,
                method: method,
                data: JSON.stringify(customerData),
                contentType: 'application/json',
                success: function(response) {
                    console.log('Customer saved successfully:', response);
                    customerTable.ajax.reload();  // Reload the DataTable data
                    $modal.modal('hide');  // Close the modal
                    resetForm();  // Reset form fields
                },
                error: handleAjaxError
            });
        });

        function handleAjaxError(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'An unexpected error occurred';
            console.error('Ajax Error:', errorMsg);
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
        }
    }
});
</script>
@endsection
