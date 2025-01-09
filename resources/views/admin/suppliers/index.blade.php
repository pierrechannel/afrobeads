@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Supplier Management</h5>
            <button class="btn btn-primary mb-3" id="addSupplierBtn">
                <i class="fas fa-plus-circle"></i> Create New Supplier
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <table class="table table-bordered table-striped table-hover" id="supplier-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Supplier Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel">
                        <i class="fas fa-user-tie me-2"></i>Create New Supplier
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="supplierForm" novalidate>
                        <input type="hidden" id="supplierId" name="supplierId">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter the supplier's name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                            <div class="invalid-feedback">Please enter the contact person's name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                            <div class="invalid-feedback">Please enter a valid phone number.</div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address"></textarea>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveSupplierBtn">
                        <i class="fas fa-save me-2"></i>Save Supplier
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
    const supplierTable = initializeDataTable();
    initializeSupplierForm();

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
        return $('#supplier-table').DataTable({
            ajax: {
                url: '/api/suppliers',
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                dataSrc: '',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load suppliers. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'contact_person' },
                { data: 'contact_phone' },
                { data: 'contact_email' },
                { data: 'address' },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-supplier" data-id="${data.id}" title="Edit Supplier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-supplier" data-id="${data.id}" title="Delete Supplier">
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
                emptyTable: 'No suppliers found',
                zeroRecords: 'No matching suppliers found'
            }
        });
    }

    function initializeSupplierForm() {
        const $form = $('#supplierForm');
        const $modal = $('#supplierModal');
        const $errorMessages = $('#errorMessages');

        function resetForm() {
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#supplierId').val('');
            $('#supplierModalLabel').text('Create New Supplier');
        }

        // Add Supplier Button
        $('#addSupplierBtn').on('click', function() {
            resetForm();
            $modal.modal('show');
        });

        // Edit Supplier
        $('#supplier-table').on('click', '.edit-supplier', function() {
            const supplierId = $(this).data('id');

            $.get(`/api/suppliers/${supplierId}`, {
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                }
            })
            .done(function(response) {
                $('#supplierId').val(response.id);
                $('#name').val(response.name);
                $('#contact_person').val(response.contact_person);
                $('#contact_phone').val(response.contact_phone);
                $('#contact_email').val(response.contact_email);
                $('#address').val(response.address);
                $('#supplierModalLabel').text('Edit Supplier');
                $modal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch supplier data:', xhr);
                showGlobalMessage(`Error fetching supplier data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`, 'danger');
            });
        });

        // Delete Supplier
        $('#supplier-table').on('click', '.delete-supplier', function() {
            const supplierId = $(this).data('id');
            if (confirm('Are you sure you want to delete this supplier?')) {
                $.ajax({
                    url: `/api/suppliers/${supplierId}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                    },
                    success: function() {
                        supplierTable.ajax.reload();
                        showGlobalMessage('Supplier deleted successfully', 'success');
                    },
                    error: handleAjaxError
                });
            }
        });

        // Save Supplier
        $('#saveSupplierBtn').on('click', function() {
            if (!$form[0].checkValidity()) {
                $form.addClass('was-validated');
                return;
            }

            const supplierId = $('#supplierId').val();
            const method = supplierId ? 'PUT' : 'POST';
            const url = supplierId ? `/api/suppliers/${supplierId}` : '/api/suppliers';

            const supplierData = {
                name: $('#name').val(),
                contact_person: $('#contact_person').val(),
                contact_phone: $('#contact_phone').val(),
                contact_email: $('#contact_email').val(),
                address: $('#address').val()
            };

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${sessionStorage.getItem('accessToken')}`
                },
                data: JSON.stringify(supplierData),
                contentType: 'application/json',
                success: function(response) {
                    supplierTable.ajax.reload();
                    $modal.modal('hide');
                    resetForm();
                    showGlobalMessage('Supplier saved successfully', 'success');
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
