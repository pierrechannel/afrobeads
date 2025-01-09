<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - TujeSoft</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .login-container {
            max-width: 400px;
            width: 90%;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            height: 50px;
            width: auto;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e1e5ea;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid #e1e5ea;
            background-color: #f8f9fa;
        }
        .btn-primary {
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }
        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .form-check-label {
            color: #6c757d;
        }
        .credits {
            color: #6c757d;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <main>
        <div class="container">
            <section class="min-vh-100 d-flex align-items-center justify-content-center py-4">
                <div class="login-container">
                    <!-- Logo Section -->
                    <div class="text-center mb-4">
                        <a href="" class="logo d-inline-block">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="TujeSoft Logo" class="mb-3">
                            <h2 class="h3 text-primary mb-0">TujeSoft</h2>
                        </a>
                    </div>

                    <!-- Login Card -->
                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h5 class="card-title h4 mb-2">Welcome Back!</h5>
                                <p class="text-muted">Please login to your account</p>
                            </div>

                            <!-- Alert Messages -->
                            <div id="response-message" class="mb-3"></div>

                            <!-- Login Form -->
                            <form id="login-form" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-4">
                                    <label for="yourUsername" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input
                                            type="text"
                                            name="username"
                                            class="form-control"
                                            id="yourUsername"
                                            placeholder="Enter your username"
                                            required
                                        >
                                    </div>
                                    <div class="invalid-feedback">Please enter your username</div>
                                </div>

                                <div class="mb-4">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            id="yourPassword"
                                            placeholder="Enter your password"
                                            required
                                        >
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Please enter your password</div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="#" class="text-primary text-decoration-none small">Forgot Password?</a>
                                </div>

                                <button class="btn btn-primary w-100 mb-3" type="submit">
                                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                    Sign In
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4">

                        <small class="text-muted">
                            &copy; 2024 TujeSoft. Designed by <a href="https://npcode.com/" class="text-decoration-none">Npcode</a>
                        </small>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Previous head and body content remains the same -->

    <script>
        $(document).ready(function () {
            console.log('🚀 Login page initialized');
            console.group('Page Initialization');
            console.log('📝 CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            console.log('🔍 Checking for stored user session:', sessionStorage.getItem('userId') ? 'Found' : 'None');
            console.groupEnd();

            // Form validation setup
            const forms = document.querySelectorAll('.needs-validation');
            console.log('📋 Found', forms.length, 'form(s) with validation');

            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordInput = $('#yourPassword');
                const icon = $(this).find('i');
                const newType = passwordInput.attr('type') === 'password' ? 'text' : 'password';

                console.log('👁️ Password visibility toggled:', newType === 'text' ? 'Shown' : 'Hidden');

                passwordInput.attr('type', newType);
                icon.toggleClass('bi-eye bi-eye-slash');
            });

            // Form submission
            $('#login-form').on('submit', function (e) {
                e.preventDefault();
                console.group('🔐 Login Attempt');
                console.time('Login Request');

                const formData = $(this).serialize();
                console.log('📤 Form Data:', Object.fromEntries(new URLSearchParams(formData)));

                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const spinner = submitBtn.find('.spinner-border');
                submitBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                console.log('⏳ Loading state activated');

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    success: function (response) {
                        console.log('✅ Login Success:', response);

                        if (response.status === "success") {
                            console.log('👤 User authenticated:', {
                                userId: response.data.user.id,
                                username: response.data.user.username,
                                timestamp: new Date().toISOString()
                            });

                            // Store user ID, username, and token as needed
                            sessionStorage.setItem('userId', response.data.user.id);
                            sessionStorage.setItem('username', response.data.user.username);
                            sessionStorage.setItem('accessToken', response.data.access_token);  // Storing the access token
                            console.log('💾 User ID and access token stored in session storage');

                            // Display success message
                            $('#response-message').html(`
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>${response.message}</div>
                                </div>
                            `);

                            // Redirect to the dashboard
                            console.log('🔄 Redirecting to dashboard...');
                            setTimeout(() => {
                                window.location.href = '/dashboard'; // Redirect to the dashboard after a short delay for users to see the success message
                            }, 1000);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.group('❌ Login Error');
                        console.error('Status:', status);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseJSON || xhr.responseText);
                        console.groupEnd();

                        $('#response-message').html(`
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>Login failed. Please check your credentials and try again.</div>
                            </div>
                        `);
                    },
                    complete: function() {
                        console.timeEnd('Login Request');

                        // Reset button state
                        submitBtn.prop('disabled', false);
                        spinner.addClass('d-none');
                        console.log('⏳ Loading state deactivated');

                        console.groupEnd(); // End login attempt group
                    }
                });
            });

            // Monitor form changes
            $('#login-form input').on('change', function() {
                const inputName = $(this).attr('name');
                const inputType = $(this).attr('type');
                console.log('📝 Form input changed:', {
                    field: inputName,
                    type: inputType,
                    hasValue: Boolean($(this).val())
                });
            });

            // Monitor remember me checkbox
            $('#rememberMe').on('change', function() {
                console.log('🔒 Remember me:', this.checked ? 'Enabled' : 'Disabled');
            });

            // Log page load complete
            window.addEventListener('load', () => {
                console.log('✨ Page fully loaded');
                console.log('⏱️ Page load time:', performance.now().toFixed(2), 'ms');
            });
        });
    </script>

<!-- Rest of the HTML remains the same -->
</body>
</html>
