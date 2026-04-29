<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'users' => User::count(),
                'cars' => Car::count(),
                'reservations' => Reservation::count(),
                'payments' => Payment::count(),
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
                'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
                'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            ],
        ]);
    }
}
