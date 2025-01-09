<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modern E-commerce Store')</title>
    <link href="{assets('assets/vendor/bootstrap/css/bootstrap.min.css')}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Include SweetAlert 2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.1.9/sweetalert2.min.css">

<!-- Include SweetAlert 2 JS -->
    <style>
        @yield('custom-styles')

    </style>
</head>
<body>
    @include('partials.topbar')
    @include('partials.navbar')
    @yield('content')

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Include Bootstrap JS if using Bootstrap for modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.1.9/sweetalert2.all.min.js"></script>

    <script>
        @yield('custom-scripts')
    </script>
</body>
</html>
