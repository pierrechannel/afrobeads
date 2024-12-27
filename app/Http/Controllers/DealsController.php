<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use RealRashid\SweetAlert\Facades\Alert;

class DealsController extends Controller
{
    public function index()
    {
        // Here you can fetch deals from a database or define them manually
        $deals = [
            [
                'title' => '50% Off on Annual Subscription',
                'description' => 'Sign up today and get 50% off your first year!',
                'expires' => '2023-12-31',
                'image' => 'assets/img\card.jpg',
            ],
            [
                'title' => 'Buy One Get One Free',
                'description' => 'Purchase one item and get another of equal or lesser value free.',
                'expires' => '2023-11-30',
                'image' => 'assets/img\card.jpg',
            ],
            [
                'title' => 'Free Shipping on Orders Over $50',
                'description' => 'Enjoy free shipping on all orders over $50. No promo code needed!',
                'expires' => '2023-10-31',
                'image' => 'assets/img\card.jpg',
            ]
        ];
        //Alert::success('Success Title', 'Success Message');

        return view('deals', compact('deals'));
    }
}
