@extends('admin.layouts.app')

@section('content')
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">Category Management</h5>
            <button class="btn btn-primary mb-3" id="addCategoryBtn">
                <i class="fas fa-plus-circle"></i> Create New Category
            </button>

            <div id="globalMessage" class="alert alert-info" style="display:none;"></div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0" id="category-table">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="categoryModalLabel">
                        <i class="fas fa-folder me-2"></i>Create New Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" novalidate enctype="multipart/form-data"> <!-- Added enctype for file upload -->
                        <input type="hidden" id="categoryId" name="categoryId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter a category name.</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                            <div class="invalid-feedback">Please enter a description.</div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image (optional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"> <!-- Changed to file input -->
                        </div>
                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">
                        <i class="fas fa-save me-2"></i>Save Category
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categoryTable = initializeDataTable();
    const $categoryForm = $('#categoryForm');
    const $categoryModal = $('#categoryModal');
    const $errorMessages = $('#errorMessages');

    function showGlobalMessage(message, type = 'info') {
        const messageDiv = $('#globalMessage');
        messageDiv.removeClass('alert-info alert-success alert-danger')
            .addClass(`alert-${type}`).text(message).show();
        setTimeout(() => {
            messageDiv.fadeOut(300);
        }, 5000);
    }

    function initializeDataTable() {
        return $('#category-table').DataTable({
            ajax: {
                url: '/api/categories',
                dataSrc: 'categories',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                    showGlobalMessage('Failed to load categories. Please refresh the page.', 'danger');
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'image', render: function(data) {
                    return data ? `<img src="/storage/${data}" alt="${data}" style="width: 50px; height: 50px;"/>` : 'No Image';
                }},
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-category" data-id="${data.id}" title="Edit Category">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-category" data-id="${data.id}" title="Delete Category">
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
                emptyTable: 'No categories found',
                zeroRecords: 'No matching categories found'
            }
        });
    }

    function resetForm() {
        $categoryForm[0].reset();
        $categoryForm.removeClass('was-validated');
        $errorMessages.hide().empty();
        $('#categoryId').val('');
        $('#categoryModalLabel').text('Create New Category');
    }

    $('#addCategoryBtn').on('click', function() {
        resetForm();
        $categoryModal.modal('show');
    });

    $('#category-table').on('click', '.edit-category', function() {
        const categoryId = $(this).data('id');

        $.get(`/api/categories/${categoryId}`)
            .done(function(response) {
                $('#categoryId').val(response.category.id);
                $('#name').val(response.category.name);
                $('#description').val(response.category.description);
                $('#image').val(''); // Clear the image input for editing

                $('#categoryModalLabel').text('Edit Category');
                $categoryModal.modal('show');
            })
            .fail(function(xhr) {
                console.error('Failed to fetch category data:', xhr);
                showGlobalMessage('Error fetching category data.', 'danger');
            });
    });

    $('#category-table').on('click', '.delete-category', function() {
        const categoryId = $(this).data('id');

        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: `/api/categories/${categoryId}`,
                method: 'DELETE',
                success: function() {
                    categoryTable.ajax.reload();
                    showGlobalMessage('Category deleted successfully', 'success');
                },
                error: function(xhr) {
                    console.error('Failed to delete category:', xhr);
                    showGlobalMessage('Error deleting category.', 'danger');
                }
            });
        }
    });

    $('#saveCategoryBtn').on('click', function() {
        if (!$categoryForm[0].checkValidity()) {
            $categoryForm.addClass('was-validated');
            return;
        }

        const categoryId = $('#categoryId').val();
        const method = categoryId ? 'PUT' : 'POST';
        const url = categoryId ? `/api/categories/${categoryId}` : '/api/categories';

        const formData = new FormData($categoryForm[0]); // Use FormData to handle the file upload

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false, // Important for file uploads
            contentType: false, // Important for file uploads
            success: function(response) {
                categoryTable.ajax.reload();
                $categoryModal.modal('hide');
                resetForm();
                showGlobalMessage('Category saved successfully', 'success');
            },
            error: function(xhr) {
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    let detailedErrors = '';
                    for (const key in errors) {
                        detailedErrors += `<strong>${key}:</strong> ${errors[key].join(', ')}<br>`;
                    }
                    $errorMessages.html(detailedErrors).show();
                } else {
                    showGlobalMessage('Error saving category.', 'danger');
                }
            }
        });
    });
});
</script>
@endsection
