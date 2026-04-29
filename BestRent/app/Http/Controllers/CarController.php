<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CarController extends Controller
{
    public function create()
    {
        return view('admin.cars.create', [
            'categories' => Car::categoryOptions(),
            'fuelTypes' => Car::fuelTypeOptions(),
            'transmissions' => Car::transmissionOptions(),
            'statuses' => Car::statusOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
            'image_type' => ['required', 'in:file,link'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_link' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);

        $validated = $this->fillImageData($request, $validated);

        $car = Car::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Autó létrehozva.',
                'car' => $car,
            ], 201);
        }

        return redirect('/admin/dashboard')->with('success', 'Autó sikeresen létrehozva!');
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
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
            'image_type' => ['required', 'in:file,link'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_link' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);

        $validated = $this->fillImageData($request, $validated, $car);

        $car->update($validated);

        $from = $request->input('from', '/admin/dashboard');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Autó frissítve.',
                'car' => $car->fresh(),
            ]);
        }

        return redirect($from)->with('success', 'Autó sikeresen módosítva!');
    }

    public function edit(Car $car)
    {
        $from = request()->query('from', '/admin/dashboard');
        return view('admin.cars.edit', [
            'car' => $car,
            'from' => $from,
            'categories' => Car::categoryOptions(),
            'fuelTypes' => Car::fuelTypeOptions(),
            'transmissions' => Car::transmissionOptions(),
            'statuses' => Car::statusOptions(),
        ]);
    }

    public function destroy(Car $car): JsonResponse
    {
        $this->deleteStoredImage($car);

        $car->delete();

        return response()->json([
            'message' => 'Autó törölve.',
        ]);
    }

    private function fillImageData(Request $request, array $validated, ?Car $car = null): array
    {
        $selectedType = $validated['image_type'];
        $currentType = $car?->image_type;

        if (! $currentType && $car?->image) {
            $currentType = Str::startsWith($car->image, ['http://', 'https://']) ? 'link' : 'file';
        }

        if ($selectedType === 'file') {
            if ($request->hasFile('image_file')) {
                if ($car) {
                    $this->deleteStoredImage($car);
                }

                $validated['image'] = $request->file('image_file')->store('cars', 'public');
            } elseif (! $car || $currentType !== 'file' || ! $car->image) {
                throw ValidationException::withMessages([
                    'image_file' => 'Válassz ki egy képfájlt.',
                ]);
            }
        }

        if ($selectedType === 'link') {
            $imageLink = trim((string) $request->input('image_link'));

            if ($imageLink !== '') {
                if (! $this->isDirectImageLink($imageLink)) {
                    throw ValidationException::withMessages([
                        'image_link' => 'Közvetlen képlinket adj meg, ami .jpg, .jpeg, .png vagy .webp végű.',
                    ]);
                }

                if ($car) {
                    $this->deleteStoredImage($car);
                }

                $validated['image'] = $imageLink;
            } elseif (! $car || $currentType !== 'link' || ! $car->image) {
                throw ValidationException::withMessages([
                    'image_link' => 'Adj meg egy képlinket.',
                ]);
            }
        }

        $validated['image_type'] = $selectedType;
        unset($validated['image_file'], $validated['image_link']);

        return $validated;
    }

    private function isDirectImageLink(string $url): bool
    {
        $path = (string) parse_url($url, PHP_URL_PATH);

        return (bool) preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $path);
    }

    private function deleteStoredImage(Car $car): void
    {
        if (! $car->image) {
            return;
        }

        if (($car->image_type === 'link') || Str::startsWith($car->image, ['http://', 'https://'])) {
            return;
        }

        Storage::disk('public')->delete($car->image);
    }
}
