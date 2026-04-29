<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarApiController extends Controller
{
    private function rulesForStore(): array
    {
        return [
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:' . implode(',', Car::categoryOptions())],
            'year' => ['required', 'integer', 'between:1980,2100'],
            'plate_number' => ['required', 'string', 'max:30', 'unique:cars,plate_number'],
            'color' => ['nullable', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'in:' . implode(',', Car::fuelTypeOptions())],
            'transmission' => ['nullable', 'in:' . implode(',', Car::transmissionOptions())],
            'seats' => ['required', 'integer', 'between:2,9'],
            'daily_price' => ['required', 'numeric', 'min:1'],
            'status' => ['required', 'in:' . implode(',', Car::statusOptions())],
            'description' => ['nullable', 'string'],
            'image' => ['prohibited'],
            'image_type' => ['prohibited'],
        ];
    }

    private function rulesForUpdate(Car $car): array
    {
        return [
            'brand' => ['sometimes', 'required', 'string', 'max:100'],
            'model' => ['sometimes', 'required', 'string', 'max:100'],
            'category' => ['sometimes', 'required', 'in:' . implode(',', Car::categoryOptions())],
            'year' => ['sometimes', 'required', 'integer', 'between:1980,2100'],
            'plate_number' => ['sometimes', 'required', 'string', 'max:30', 'unique:cars,plate_number,' . $car->id],
            'color' => ['nullable', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'in:' . implode(',', Car::fuelTypeOptions())],
            'transmission' => ['nullable', 'in:' . implode(',', Car::transmissionOptions())],
            'seats' => ['sometimes', 'required', 'integer', 'between:2,9'],
            'daily_price' => ['sometimes', 'required', 'numeric', 'min:1'],
            'status' => ['sometimes', 'required', 'in:' . implode(',', Car::statusOptions())],
            'description' => ['nullable', 'string'],
            'image' => ['prohibited'],
            'image_type' => ['prohibited'],
        ];
    }

    public function index(): JsonResponse
    {
        $cars = Car::orderBy('id', 'desc')->get();

        return response()->json(['cars' => $cars]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rulesForStore());

        $car = Car::create($validated);

        return response()->json([
            'message' => 'Car created.',
            'car' => $car,
        ], 201);
    }

    public function update(Request $request, Car $car): JsonResponse
    {
        $validated = $request->validate($this->rulesForUpdate($car));

        $car->update($validated);

        return response()->json([
            'message' => 'Car updated.',
            'car' => $car->fresh(),
        ]);
    }

    public function destroy(Car $car): JsonResponse
    {
        $car->delete();

        return response()->json([
            'message' => 'Car deleted.',
        ]);
    }
}
