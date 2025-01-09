<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb9z7WjL38LJ2bNeKtkX1Fgf61H31hk4n3C4E" crossorigin="anonymous">
    <!-- Custom CSS if you have it -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar bg-primary text-white vh-100 p-3 position-fixed">
            <div class="logo text-2xl mb-4 text-center">Admin</div>
            <div class="menu-item active cursor-pointer p-2 rounded-md hover:bg-primary-dark">
                <i class="fas fa-chart-line me-2"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item cursor-pointer p-2 rounded-md hover:bg-primary-dark">
                <i class="fas fa-box-open me-2"></i>
                <span>Products</span>
            </div>
            <div class="menu-item cursor-pointer p-2 rounded-md hover:bg-primary-dark">
                <i class="fas fa-shopping-cart me-2"></i>
                <span>Orders</span>
            </div>
            <!-- Add more menu items as needed -->
        </div>

        <div class="main-content ms-5 p-3" style="margin-left: 250px;"> <!-- Adjust margin to accommodate sidebar width -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pZWuU05B0pl8jO0gIFOQSMQWqYFcYNtW/0/Zp5jEKuZpE5Er5trzkO4OELHdwA" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Add click handlers for menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelector('.menu-item.active').classList.remove('active');
                item.classList.add('active');
            });
        });
    </script>
</body>
</html>
