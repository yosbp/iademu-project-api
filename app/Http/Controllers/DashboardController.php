<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Provider;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Get counts of Orders and Providers
        $ordersCount = Order::count();
        $providersCount = Provider::count();

        // Return
        return response()->json([
            'orders' => $ordersCount,
            'providers' => $providersCount
        ], 200);
    }
}
