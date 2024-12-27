<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data for the dashboard cards and recent orders
        $totalSales = 24580;
        $totalOrders = 152;
        $newCustomers = 28;
        $averageOrderValue = 161.71;
        $recentOrders = [
            [
                'id' => '#12345',
                'customer' => 'John Doe',
                'product' => 'iPhone 15 Pro',
                'amount' => 999,
                'status' => 'Completed'
            ],
            [
                'id' => '#12344',
                'customer' => 'Jane Smith',
                'product' => 'MacBook Air',
                'amount' => 1299,
                'status' => 'Pending'
            ],
            // Add more recent orders as needed
        ];

        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'newCustomers' => $newCustomers,
            'averageOrderValue' => $averageOrderValue,
            'recentOrders' => $recentOrders
        ]);
    }


}
