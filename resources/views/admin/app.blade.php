<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="flex">
        <div class="sidebar bg-blue-900 text-white h-screen p-6 fixed">
            <div class="logo text-2xl mb-8 text-center">Admin</div>
            <div class="menu-item active cursor-pointer p-3 rounded-md hover:bg-blue-800 transition-colors">
                <i class="fas fa-chart-line mr-2"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item cursor-pointer p-3 rounded-md hover:bg-blue-800 transition-colors">
                <i class="fas fa-box-open mr-2"></i>
                <span>Products</span>
            </div>
            <div class="menu-item cursor-pointer p-3 rounded-md hover:bg-blue-800 transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span>Orders</span>
            </div>
            <!-- Add more menu items as needed -->
        </div>

        <div class="main-content ml-64 p-6 w-full">
            @yield('content')
        </div>
    </div>

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
