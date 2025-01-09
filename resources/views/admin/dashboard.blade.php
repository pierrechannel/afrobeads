@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <section class="dashboard">
        <div class="row g-4" id="dashboard-data">
            <div class="col-12">
                <h1 class="fs-2 mb-4">Dashboard Overview</h1>
            </div>
            <!-- Loading state -->
            <div class="d-flex justify-content-center w-100">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log("Fetching dashboard data...");

    // Retrieve the access token from sessionStorage
    const accessToken = sessionStorage.getItem('accessToken');

    $.ajax({
        url: "http://127.0.0.1:8000/api/dashboard",
        method: "GET",
        dataType: "json",
        headers: {
            'Authorization': `Bearer ${accessToken}` // Include the token in the Authorization header
        },
        success: function(response) {
            console.log("Response received:", response);
            if (response.success) {
                let data = response.data;

                // Populate dashboard data
                $("#dashboard-data").html(`
                    <!-- Stats Cards Row -->
                    <div class="col-12">
                        <div class="row g-4">
                            <!-- Total Sales Card -->
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                                    <i class="bi bi-cart fs-4 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Total Sales</h6>
                                                <p class="small text-muted mb-0">This Month</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">$${data.total_sales}</h4>
                                            <span class="badge bg-success ms-2">+8.5%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Revenue Card -->
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                                    <i class="bi bi-currency-dollar fs-4 text-success"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Revenue</h6>
                                                <p class="small text-muted mb-0">This Month</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">$${data.total_revenue}</h4>
                                            <span class="badge bg-success ms-2">+12.3%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expenses Card -->
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-danger bg-opacity-10 p-3 rounded">
                                                    <i class="bi bi-currency-exchange fs-4 text-danger"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Expenses</h6>
                                                <p class="small text-muted mb-0">This Month</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">$${data.total_expenses}</h4>
                                            <span class="badge bg-danger ms-2">-2.8%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Debt Card -->
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                                    <i class="bi bi-cash-stack fs-4 text-info"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Total Debt</h6>
                                                <p class="small text-muted mb-0">Current</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">$${data.total_debt}</h4>
                                            <span class="badge bg-info ms-2">Pending</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="col-12 col-xxl-8">
                        <!-- Recent Sales Table -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Recent Sales</h5>
                                    <button class="btn btn-primary btn-sm">View All</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.recent_sales.length > 0 ? data.recent_sales.map(sale => `
                                                <tr>
                                                    <th scope="row">${sale.id}</th>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-light rounded-circle p-2 me-2">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                            ${sale.customer ? sale.customer.name : 'N/A'}
                                                        </div>
                                                    </td>
                                                    <td>${sale.product ? sale.product.name : 'N/A'}</td>
                                                    <td>$${(sale.total_price || 0)}</td>
                                                    <td>
                                                        <span class="badge ${sale.is_paid ? 'bg-success' : 'bg-warning'} rounded-pill">
                                                            ${sale.is_paid ? 'Paid' : 'Pending'}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `).join('') : '<tr><td colspan="5" class="text-center">No recent sales</td></tr>'}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Top Selling Products -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Top Selling Products</h5>
                                    <button class="btn btn-primary btn-sm">View All</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Sold</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.top_selling_products.map(product => `
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-light rounded p-2 me-2">
                                                                <i class="bi bi-box"></i>
                                                            </div>
                                                            ${product.name}
                                                        </div>
                                                    </td>
                                                    <td>$${product.price}</td>
                                                    <td>${product.sold_quantity}</td>
                                                    <td>$${(product.price * product.sold_quantity).toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Content -->
                    <div class="col-12 col-xxl-4">
                        <!-- Top Customers -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Top Customers</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    ${data.top_customers.map(customer => `
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <span>${customer.name}</span>
                                            </div>
                                            <span class="badge bg-success rounded-pill">Top Client</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alerts -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Low Stock Alerts</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    ${data.low_stock_alerts.length > 0 ? data.low_stock_alerts.map(alert => `
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 rounded p-2 me-2">
                                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                                </div>
                                                <span>${alert.name}</span>
                                            </div>
                                            <span class="badge bg-danger rounded-pill">${alert.stock_quantity} left</span>
                                        </div>
                                    `).join('') : '<div class="list-group-item text-center">No low stock alerts</div>'}
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Status -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">Inventory Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.inventory_status.map(item => `
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <small>${item.name}</small>
                                                        </div>
                                                    </td>
                                                    <td><small>$${item.price}</small></td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="col-12 mt-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">Monthly Sales</div>
                                    <div class="card-body">
                                        <div id="salesChart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">Product Distribution</div>
                                    <div class="card-body">
                                        <div id="productChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                // Initialize ApexCharts after data loading and DOM update
                initializeCharts();
            } else {
                console.error("Error in response:", response.message);
                $("#dashboard-data").html('<div class="alert alert-danger">Error: ' + response.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching dashboard data:', error);
            $("#dashboard-data").html('<div class="alert alert-danger">Error loading data. Please try again later.</div>');
        }
    });
});

// Function to initialize ApexCharts
function initializeCharts() {
    // Sales Chart Options
    const salesOptions = {
        chart: {
            type: 'area',
            height: 350
        },
        series: [{
            name: 'Sales',
            data: [12000, 19000, 15000, 21000, 16000, 23000]
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9
            }
        }
    };

    // Product Chart Options
    const productOptions = {
        chart: {
            type: 'donut',
            height: 350
        },
        series: [300, 240, 180, 280],
        labels: ['Product A', 'Product B', 'Product C', 'Product D'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Create chart instances
    const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
    const productChart = new ApexCharts(document.querySelector("#productChart"), productOptions);

    // Render charts
    salesChart.render();
    productChart.render();

    // AJAX update functions for charts
    function updateCharts() {
        console.log("Updating charts...");
        $.ajax({
            url: 'api/sales',
            success: function(data) {
                console.log("Sales data updated:", data);
                salesChart.updateSeries([{
                    data: data.sales
                }]);
                salesChart.updateOptions({
                    xaxis: {
                        categories: data.months
                    }
                });
            }
        });

        $.ajax({
            url: 'api/products',
            success: function(data) {
                console.log("Product data updated:", data);
                productChart.updateSeries(data.quantities);
                productChart.updateOptions({
                    labels: data.products
                });
            }
        });
    }

    // Update every 5 minutes
    setInterval(updateCharts, 300000);
}
</script>
@endsection
