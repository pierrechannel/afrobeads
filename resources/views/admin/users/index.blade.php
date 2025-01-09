@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h5 class="card-title">User Management</h5>
            <button class="btn btn-primary mb-3" id="addUserBtn">
                <i class="fas fa-plus-circle"></i> Create New User
            </button>
            <table class="table table-bordered table-striped table-hover" id="user-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Management Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="userModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Create New User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" novalidate>
                        <input type="hidden" id="userId" name="userId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="invalid-feedback">Please enter a username</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-id-card me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Please enter full name</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Role
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="user">User</option>
                                </select>
                                <div class="invalid-feedback">Please select a role</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="invalid-feedback">Password is required for new users</div>
                                <small class="form-text text-muted">Leave blank to keep existing password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                <div class="invalid-feedback">Passwords must match</div>
                            </div>
                        </div>

                        <div id="errorMessages" class="alert alert-danger mt-3" style="display:none;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn">
                        <i class="fas fa-save me-2"></i>Save User
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
    console.log('Document Loaded: Initializing User Management');

    const userTable = initializeDataTable();
    initializeUserForm();

    function initializeDataTable() {
        console.log('Initializing DataTable');
        return $('#user-table').DataTable({
            ajax: {
                url: '/api/users',
                dataSrc: 'data',
                error: function(xhr, error, thrown) {
                    console.error('DataTable Ajax Error:', error, thrown);
                }
            },
            columns: [
                { data: 'id' },
                { data: 'username', defaultContent: '<span class="text-muted">N/A</span>' },
                { data: 'name', defaultContent: '<span class="text-muted">N/A</span>' },
                { data: 'email', defaultContent: '<span class="text-muted">N/A</span>' },
                {
                    data: 'role',
                    render: function (data) {
                        const badgeClass = {
                            'admin': 'bg-danger',
                            'manager': 'bg-warning',
                            'user': 'bg-success'
                        };
                        return `<span class="badge ${badgeClass[data] || 'bg-secondary'}">${data || 'Unassigned'}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info edit-user" data-id="${data.id}" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-user" data-id="${data.id}" title="Delete User">
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
                emptyTable: 'No users found',
                zeroRecords: 'No matching users found'
            }
        });
    }

    function initializeUserForm() {
        const $form = $('#userForm');
        const $modal = $('#userModal');
        const $errorMessages = $('#errorMessages');

        console.log('Initializing User Form functionalities');

        function resetForm() {
            console.log('Resetting form');
            $form[0].reset();
            $form.removeClass('was-validated');
            $errorMessages.hide().empty();
            $('#userId').val('');
            $('#userModalLabel').html('<i class="fas fa-user-plus me-2"></i>Create New User');
        }

        $('#addUserBtn').on('click', function() {
            console.log('Add User button clicked');
            resetForm();
            $modal.modal('show');
        });

        $('#user-table').on('click', '.edit-user', function() {
            const userId = $(this).data('id');
            console.log(`Editing user with ID: ${userId}`);

            // Fetch user details
            $.get(`/api/users/${userId}`)
                .done(function(response) {
                    console.log('User details fetched:', response);
                    $('#userId').val(response.id);
                    $('#username').val(response.username);
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#role').val(response.role || '');

                    $('#userModalLabel').html('<i class="fas fa-user-edit me-2"></i>Edit User');
                    $('#userModal').modal('show');
                })
                .fail(function(xhr) {
                    console.error('Failed to fetch user data:', xhr);
                    alert(`Error fetching user data: ${xhr.responseJSON?.message || 'An unknown error occurred'}`);
                });
        });

        $('#user-table').on('click', '.delete-user', function() {
            const userId = $(this).data('id');
            console.log(`Attempting to delete user with ID: ${userId}`);

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: `/api/users/${userId}`,
                    method: 'DELETE',
                    success: function() {
                        console.log(`User ${userId} deleted successfully`);
                        userTable.ajax.reload();
                        alert('User deleted successfully');
                    },
                    error: handleAjaxError
                });
            }
        });

        $('#saveUserBtn').on('click', function() {
            console.log('Save User button clicked');
            if (!$form[0].checkValidity()) {
                console.warn('Form validation failed');
                $form.addClass('was-validated');
                return;
            }

            const userId = $('#userId').val();
            const method = userId ? 'PUT' : 'POST';
            const url = userId ? `/api/users/${userId}` : '/api/users';

            const userData = {
                username: $('#username').val().trim(),
                name: $('#name').val().trim(),
                email: $('#email').val().trim(),
                role: $('#role').val()
            };

            const password = $('#password').val().trim();
            if (password) {
                userData.password = password;
                userData.password_confirmation = $('#password_confirmation').val().trim();
            }

            console.log('Sending user data:', userData);

            $.ajax({
                url: url,
                method: method,
                data: JSON.stringify(userData),
                contentType: 'application/json',
                success: function(response) {
                    console.log('User saved successfully:', response);
                    userTable.ajax.reload();
                    $modal.modal('hide');
                    resetForm();
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
