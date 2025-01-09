@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Expense Management</h5>
            <button class="btn btn-primary mb-3" id="addExpenseBtn">
                <i class="fas fa-plus-circle"></i> Create New Expense
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <table class="table table-bordered table-striped table-hover" id="expense-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="expenseModalLabel">
                        <i class="fas fa-money-bill-wave me-2"></i>Create New Expense
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="expenseForm" novalidate>
                        <input type="hidden" id="expenseId" name="expenseId">

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                            <div class="invalid-feedback">Please enter a description for the expense.</div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required min="0" step="0.01">
                            <div class="invalid-feedback">Please enter a valid amount.</div>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                                <!-- Categories will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveExpenseBtn">
                        <i class="fas fa-save me-2"></i>Save Expense
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
    console.log('Document Loaded: Initializing Expense Management');

    const expenseTable = initializeDataTable();
    initializeExpenseForm();

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
        const dataTable = $('#expense-table').DataTable({
            ajax: {
                url: '/api/expenses',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load expenses. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'description' },
                { data: 'amount' },
                { data: 'category.category_name' },
                { data: 'user.name' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-expense" data-id="${data.id}" title="Edit Expense">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-expense" data-id="${data.id}" title="Delete Expense">
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
                emptyTable: 'No expenses found',
                zeroRecords: 'No matching expenses found'
            }
        });

        return dataTable;
    }

    function initializeExpenseForm() {
        const $form = $('#expenseForm');
        const $modal = $('#expenseModal');
        const $errorMessages = $('#errorMessages');

        function resetForm() {
            console.log('Resetting form');
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#expenseId').val('');
            $('#expenseModalLabel').html('<i class="fas fa-money-bill-wave me-2"></i>Create New Expense');
            loadCategories(); // Load categories when resetting the form
        }

        function loadCategories() {
    const token = sessionStorage.getItem('accessToken');
    console.log('Access Token:', token); // Logging the access token to check if it's stored properly

    $.ajax({
        url: '/api/expense-categories',
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}` // Authorization header
        }
    })
    .done(function(data) {
        const categorySelect = $('#category');
        categorySelect.empty().append('<option value="">Select a category</option>');
        data.forEach(category => {
            categorySelect.append(`<option value="${category.id}">${category.category_name}</option>`);
        });
    })
    .fail(function(xhr) {
        // Enhanced error logging
        console.error('Error details:', xhr);
        handleAjaxError(xhr);
    });
}

        $('#addExpenseBtn').on('click', function() {
            console.log('Add Expense button clicked');
            resetForm();
            $modal.modal('show');
        });

        $('#expense-table').on('click', '.edit-expense', function() {
            const expenseId = $(this).data('id');
            console.log(`Editing expense with ID: ${expenseId}`);

            $.get(`/api/expenses/${expenseId}`, {
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(response) {
                console.log('Expense details fetched:', response);
                $('#expenseId').val(response.id);
                $('#description').val(response.description);
                $('#amount').val(response.amount);
                $('#category').val(response.category_id); // Set the selected category

                $('#expenseModalLabel').html('<i class="fas fa-money-bill-wave me-2"></i>Edit Expense');
                loadCategories(); // Load categories before showing the modal

                $modal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch expense data:', xhr);
                showGlobalMessage(`Error fetching expense data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`, 'danger');
            });
        });

        $('#expense-table').on('click', '.delete-expense', function() {
            const expenseId = $(this).data('id');
            console.log(`Attempting to delete expense with ID: ${expenseId}`);

            if (confirm('Are you sure you want to delete this expense?')) {
                $.ajax({
                    url: `/api/expenses/${expenseId}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                    },
                    success: function() {
                        console.log(`Expense ${expenseId} deleted successfully`);
                        expenseTable.ajax.reload();
                        showGlobalMessage('Expense deleted successfully', 'success');
                    },
                    error: handleAjaxError
                });
            }
        });

        $('#saveExpenseBtn').on('click', function() {
            console.log('Save Expense button clicked');
            if (!$form[0].checkValidity()) {
                console.warn('Form validation failed');
                $form.addClass('was-validated');
                return;
            }

            const expenseId = $('#expenseId').val();
            const method = expenseId ? 'PUT' : 'POST';
            const url = expenseId ? `/api/expenses/${expenseId}` : '/api/expenses';

            const userId = sessionStorage.getItem('userId'); // Ensure the key matches the one used during login
console.log('User ID:', userId);

const expenseData = {
    user_id: userId, // Use userId retrieved from sessionStorage
    description: $('#description').val().trim(), // Trimmed description input
    amount: $('#amount').val().trim(), // Trimmed amount input
    category_id: $('#category').val() // Category ID from the selection
};


            console.log('Sending expense data:', expenseData);

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                data: JSON.stringify(expenseData),
                contentType: 'application/json',
                success: function(response) {
                    console.log('Expense saved successfully:', response);
                    expenseTable.ajax.reload(); // Reload the DataTable data
                    $modal.modal('hide'); // Close the modal
                    resetForm(); // Reset form fields
                    showGlobalMessage('Expense saved successfully', 'success'); // Show success message
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
            showGlobalMessage(errorMsg, 'danger');
        }
    }
});
</script>
@endsection
