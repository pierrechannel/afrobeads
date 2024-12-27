@extends('admin.app')

@section('content')
    <div class="header flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Dashboard Overview</h1>
        <input type="date" value="2024-12-26" class="border-gray-300 rounded-md py-2 px-4">
    </div>

    <div class="dashboard-cards grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="card bg-white rounded-md shadow-md p-6">
            <h3 class="text-gray-500 mb-2">Total Sales</h3>
            <div class="value text-2xl font-bold text-blue-900">$24,580</div>
        </div>
        <div class="card bg-white rounded-md shadow-md p-6">
            <h3 class="text-gray-500 mb-2">Total Orders</h3>
            <div class="value text-2xl font-bold text-blue-900">152</div>
        </div>
        <div class="card bg-white rounded-md shadow-md p-6">
            <h3 class="text-gray-500 mb-2">New Customers</h3>
            <div class="value text-2xl font-bold text-blue-900">28</div>
        </div>
        <div class="card bg-white rounded-md shadow-md p-6">
            <h3 class="text-gray-500 mb-2">Average Order Value</h3>
            <div class="value text-2xl font-bold text-blue-900">$161.71</div>
        </div>
    </div>

    <div class="recent-orders bg-white rounded-md shadow-md p-6">
        <h2 class="text-lg font-bold mb-4">Recent Orders</h2>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="bg-gray-100 p-3 text-left">Order ID</th>
                    <th class="bg-gray-100 p-3 text-left">Customer</th>
                    <th class="bg-gray-100 p-3 text-left">Product</th>
                    <th class="bg-gray-100 p-3 text-left">Amount</th>
                    <th class="bg-gray-100 p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3 border-b">#12345</td>
                    <td class="p-3 border-b">John Doe</td>
                    <td class="p-3 border-b">iPhone 15 Pro</td>
                    <td class="p-3 border-b">$999</td>
                    <td class="p-3 border-b">
                        <span class="bg-green-100 text-green-800 rounded-full px-3 py-1 text-xs">Completed</span>
                    </td>
                </tr>
                <tr>
                    <td class="p-3 border-b">#12344</td>
                    <td class="p-3 border-b">Jane Smith</td>
                    <td class="p-3 border-b">MacBook Air</td>
                    <td class="p-3 border-b">$1299</td>
                    <td class="p-3 border-b">
                        <span class="bg-orange-100 text-orange-800 rounded-full px-3 py-1 text-xs">Pending</span>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
@endsection
