<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    public function cars(Request $request)
    {
        $cars = Car::query()
            ->where('status', 'available')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q');

                $query->where(function ($inner) use ($keyword) {
                    $inner->where('brand', 'like', '%' . $keyword . '%')
                        ->orWhere('model', 'like', '%' . $keyword . '%')
                        ->orWhere('plate_number', 'like', '%' . $keyword . '%')
                        ->orWhere('color', 'like', '%' . $keyword . '%')
                        ->orWhere('category', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('brand'), fn ($query) => $query->where('brand', 'like', '%' . $request->string('brand') . '%'))
            ->when($request->filled('model'), fn ($query) => $query->where('model', 'like', '%' . $request->string('model') . '%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('color'), fn ($query) => $query->where('color', 'like', '%' . $request->string('color') . '%'))
            ->when($request->filled('fuel_type'), fn ($query) => $query->where('fuel_type', $request->string('fuel_type')))
            ->when($request->filled('transmission'), fn ($query) => $query->where('transmission', $request->string('transmission')))
            ->when($request->filled('seats'), fn ($query) => $query->where('seats', $request->integer('seats')))
            ->when($request->filled('year'), fn ($query) => $query->where('year', $request->integer('year')))
            ->when($request->filled('min_price'), fn ($query) => $query->where('daily_price', '>=', $request->input('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('daily_price', '<=', $request->input('max_price')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('pages.cars.index', [
            'cars' => $cars,
            'categories' => Car::categoryOptions(),
            'fuelTypes' => Car::fuelTypeOptions(),
            'transmissions' => Car::transmissionOptions(),
        ]);
    }

    public function showCar(Car $car)
    {
        return view('pages.cars.show', ['car' => $car]);
    }

    public function reserveForm(Request $request)
    {
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard')->with('error', 'Admin fiókkal nem lehet autót foglalni.');
        }

        $car = Car::where('status', 'available')->findOrFail($request->integer('car_id'));

        return view('client.reserve', [
            'car' => $car,
        ]);
    }

    public function reservationShow(Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth()->id(), 403);

        return view('client.reservation-show', [
            'reservation' => $reservation->load(['car', 'payments']),
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            $stats = [
                'cars' => Car::count(),
                'reservations' => Reservation::count(),
                'payments' => Payment::count(),
                'revenue' => Payment::where('status', 'paid')->sum('amount'),
            ];
        } else {
            $stats = [
                'reservations' => Reservation::where('user_id', $user->id)->count(),
                'active_reservations' => Reservation::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'confirmed', 'active'])
                    ->count(),
                'payments' => Payment::where('user_id', $user->id)->count(),
                'paid_total' => Payment::where('user_id', $user->id)
                    ->where('status', 'paid')
                    ->sum('amount'),
            ];
        }

        return view('client.profile', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => AuthController::phoneNumberRules($user->id),
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'phone_number.regex' => 'A telefonszám formátuma nem megfelelő. Példa: +36201234567 vagy 06201234567.',
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect('/client/profile')->with('success', 'A profil adatai frissültek.');
    }

    public function destroyProfile(Request $request)
    {
        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'A fiók törölve lett.');
    }

    public function clientDashboard()
    {
        $reservations = Reservation::with(['car', 'user', 'payments'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $payments = Payment::with(['reservation.car', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('client.dashboard', [
            'reservations' => $reservations,
            'payments' => $payments,
        ]);
    }

    public function adminDashboard()
    {
        $cars = Car::latest()->get();
        $reservations = Reservation::with(['car', 'user'])
            ->latest()
            ->paginate(10, ['*'], 'reservations_page')
            ->appends(['tab' => 'reservations']);
        $payments = Payment::with(['user', 'reservation.car'])->latest()->get();

        $stats = [
            'users' => User::count(),
            'cars' => Car::count(),
            'reservations' => Reservation::count(),
            'payments' => Payment::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.dashboard', [
            'cars' => $cars,
            'reservations' => $reservations,
            'payments' => $payments,
            'stats' => $stats,
        ]);
    }
}