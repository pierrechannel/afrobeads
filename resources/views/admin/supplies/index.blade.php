@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Supply Management</h5>
            <button class="btn btn-primary mb-3" id="addSupplyBtn">
                <i class="fas fa-plus-circle"></i> Create New Supply
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <table class="table table-bordered table-striped table-hover" id="supply-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Store Name</th>
                        <th>Supplier Name</th>
                        <th>Total Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Supply Modal -->
    <div class="modal fade" id="supplyModal" tabindex="-1" aria-labelledby="supplyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplyModalLabel">
                        <i class="fas fa-box me-2"></i>Create New Supply Entry
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="supplyForm" novalidate>
                        <input type="hidden" id="supplyId" name="supplyId">

                        <div class="mb-3">
                            <label for="store_id" class="form-label">Store</label>
                            <select class="form-select" id="store_id" name="store_id" required>
                                <option value="">Select a store</option>
                                <!-- Stores will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a store.</div>
                        </div>

                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                <option value="">Select a supplier</option>
                                <!-- Suppliers will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select a supplier.</div>
                        </div>

                        <div class="mb-3">
                            <label for="total_cost" class="form-label">Total Cost</label>
                            <input type="number" class="form-control" id="total_cost" name="total_cost" required min="0" step="0.01">
                            <div class="invalid-feedback">Please enter a valid total cost.</div>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveSupplyBtn">
                        <i class="fas fa-save me-2"></i>Save Supply
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
    const supplyTable = initializeDataTable();
    initializeSupplyForm();

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
        return $('#supply-table').DataTable({
            ajax: {
                url: '/api/supplies',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load supplies. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'store.name' },
                { data: 'supplier.name' },
                { data: 'total_cost' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-supply" data-id="${data.id}" title="Edit Supply">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-supply" data-id="${data.id}" title="Delete Supply">
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
                emptyTable: 'No supplies found',
                zeroRecords: 'No matching supplies found'
            }
        });
    }

    function initializeSupplyForm() {
        const $form = $('#supplyForm');
        const $modal = $('#supplyModal');
        const $errorMessages = $('#errorMessages');

        function resetForm() {
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#supplyId').val('');
            $('#supplyModalLabel').text('Create New Supply Entry');
            loadStores();
            loadSuppliers();
        }

        function loadStores() {
            $.ajax({
                url: '/api/stores',
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

        function loadSuppliers() {
            $.ajax({
                url: '/api/suppliers',
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(data) {
                const supplierSelect = $('#supplier_id');
                supplierSelect.empty().append('<option value="">Select a supplier</option>');
                data.forEach(supplier => {
                    supplierSelect.append(`<option value="${supplier.id}">${supplier.name}</option>`);
                });
            })
            .fail(function(xhr) {
                console.error('Error loading suppliers:', xhr);
                handleAjaxError(xhr);
            });
        }

        $('#addSupplyBtn').on('click', function() {
            resetForm();
            $modal.modal('show');
        });

        $('#supply-table').on('click', '.edit-supply', function() {
            const supplyId = $(this).data('id');

            $.get(`/api/supplies/${supplyId}`, {
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(response) {
                $('#supplyId').val(response.id);
                $('#store_id').val(response.store_id);
                $('#supplier_id').val(response.supplier_id);
                $('#total_cost').val(response.total_cost);
                $('#supplyModalLabel').text('Edit Supply Entry');
                $modal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch supply data:', xhr);
                showGlobalMessage(`Error fetching supply data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`, 'danger');
            });
        });

        $('#supply-table').on('click', '.delete-supply', function() {
            const supplyId = $(this).data('id');
            if (confirm('Are you sure you want to delete this supply?')) {
                $.ajax({
                    url: `/api/supplies/${supplyId}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                    },
                    success: function() {
                        supplyTable.ajax.reload();
                        showGlobalMessage('Supply deleted successfully', 'success');
                    },
                    error: handleAjaxError
                });
            }
        });

        $('#saveSupplyBtn').on('click', function() {
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            const supplyId = $('#supplyId').val();
            const method = supplyId ? 'PUT' : 'POST';
            const url = supplyId ? `/api/supplies/${supplyId}` : '/api/supplies';

            const supplyData = {
                store_id: $('#store_id').val(),
                supplier_id: $('#supplier_id').val(),
                total_cost: parseFloat($('#total_cost').val())
            };

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                data: JSON.stringify(supplyData),
                contentType: 'application/json',
                success: function(response) {
                    supplyTable.ajax.reload();
                    $modal.modal('hide');
                    resetForm();
                    showGlobalMessage('Supply saved successfully', 'success');
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
