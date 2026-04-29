@extends('layouts.app')

@section('title', 'Autó szerkesztése')

@section('content')
    <div class="container my-5">
        <h2>Autó szerkesztése: {{ $car->brand }} {{ $car->model }}</h2>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/cars/{{ $car->id }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PATCH')
            <input type="hidden" name="from" value="{{ $from }}">

            <div class="col-md-6">
                <label for="brand" class="form-label">Márka</label>
                <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $car->brand) }}" required>
                @error('brand')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-6">
                <label for="model" class="form-label">Modell</label>
                <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model', $car->model) }}" required>
                @error('model')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="category" class="form-label">Kategória</label>
                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ old('category', $car->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="year" class="form-label">Év</label>
                <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $car->year) }}" min="1980" max="2100" required>
                @error('year')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="plate_number" class="form-label">Rendszám</label>
                <input type="text" class="form-control @error('plate_number') is-invalid @enderror" id="plate_number" name="plate_number" value="{{ old('plate_number', $car->plate_number) }}" required>
                @error('plate_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-3">
                <label for="fuel_type" class="form-label">Üzemanyag</label>
                <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type">
                    <option value="">Válassz...</option>
                    @foreach ($fuelTypes as $fuelType)
                        <option value="{{ $fuelType }}" {{ old('fuel_type', $car->fuel_type) == $fuelType ? 'selected' : '' }}>{{ $fuelType }}</option>
                    @endforeach
                </select>
                @error('fuel_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-3">
                <label for="transmission" class="form-label">Váltó</label>
                <select class="form-select @error('transmission') is-invalid @enderror" id="transmission" name="transmission">
                    <option value="">Válassz...</option>
                    @foreach ($transmissions as $transmission)
                        <option value="{{ $transmission }}" {{ old('transmission', $car->transmission) == $transmission ? 'selected' : '' }}>{{ $transmission }}</option>
                    @endforeach
                </select>
                @error('transmission')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-3">
                <label for="seats" class="form-label">Ülések száma</label>
                <input type="number" class="form-control @error('seats') is-invalid @enderror" id="seats" name="seats" value="{{ old('seats', $car->seats) }}" min="2" max="9" required>
                @error('seats')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-3">
                <label for="daily_price" class="form-label">Napi ár (Ft)</label>
                <input type="number" class="form-control @error('daily_price') is-invalid @enderror" id="daily_price" name="daily_price" value="{{ old('daily_price', $car->daily_price) }}" step="1" min="1" required>
                @error('daily_price')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="color" class="form-label">Szín</label>
                <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $car->color) }}">
                @error('color')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">Státusz</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ old('status', $car->status) == $status ? 'selected' : '' }}>
                            {{ $status == 'available' ? 'Elérhető' : ($status == 'maintenance' ? 'Karbantartás' : 'Nem elérhető') }}
                        </option>
                    @endforeach
                </select>
                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4">
                <label for="image_type" class="form-label">Kép típusa</label>
                <select class="form-select @error('image_type') is-invalid @enderror" id="image_type" name="image_type">
                    <option value="file" {{ old('image_type', $car->image_type ?: 'file') == 'file' ? 'selected' : '' }}>Fájl feltöltés</option>
                    <option value="link" {{ old('image_type', $car->image_type) == 'link' ? 'selected' : '' }}>Képlink</option>
                </select>
                @error('image_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4" id="image-file-group">
                <label for="image_file" class="form-label">Új képfájl</label>
                <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Max. 5 MB, formátum: jpg, jpeg, png, webp</div>
                @error('image_file')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-md-4" id="image-link-group">
                <label for="image_link" class="form-label">Képlink</label>
                <input type="url" class="form-control @error('image_link') is-invalid @enderror" id="image_link" name="image_link" value="{{ old('image_link', ($car->image_type === 'link' ? $car->image : '')) }}" placeholder="https://pelda.hu/auto.jpg">
                <div class="form-text">Csak közvetlen képlink jó.</div>
                @error('image_link')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            @if ($car->image_url)
                <div class="col-12">
                    <p class="mb-2 fw-semibold">Jelenlegi kép</p>
                    <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="img-fluid rounded border image-max-220-cover">
                </div>
            @endif

            <div class="col-12">
                <label for="description" class="form-label">Leírás</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $car->description) }}</textarea>
                @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Mentés</button>
                <a href="{{ $from }}" class="btn btn-outline-secondary">Mégsem</a>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        const imageType = document.getElementById('image_type');
        const imageFileGroup = document.getElementById('image-file-group');
        const imageLinkGroup = document.getElementById('image-link-group');

        function toggleImageFields() {
            if (imageType.value === 'link') {
                imageFileGroup.style.display = 'none';
                imageLinkGroup.style.display = 'block';
            } else {
                imageFileGroup.style.display = 'block';
                imageLinkGroup.style.display = 'none';
            }
        }

        toggleImageFields();
        imageType.addEventListener('change', toggleImageFields);
    </script>
@endsection