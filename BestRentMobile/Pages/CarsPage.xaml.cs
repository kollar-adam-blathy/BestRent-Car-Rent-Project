using System.Collections.ObjectModel;
using BestRentMobile.Models;
using BestRentMobile.Services;

namespace BestRentMobile.Pages;

public partial class CarsPage : ContentPage
{
    private const string ApiBase = "http://127.0.0.1:8000/api";
    private readonly CarApiService _service = new CarApiService();
    private readonly ObservableCollection<CarItem> _cars = new ObservableCollection<CarItem>();
    private readonly List<string> _categories = new List<string>
    {
        "Sedan", "Hatchback", "SUV", "Terepjáró", "Pickup", "Cabrio", "Coupe", "Kombi",
    };
    private readonly List<string> _fuelTypes = new List<string>
    {
        "Benzin", "Dízel", "Hibrid", "Elektromos",
    };
    private readonly List<string> _transmissions = new List<string>
    {
        "Automata", "Manuális",
    };
    private readonly List<string> _statuses = new List<string>
    {
        "available", "maintenance", "unavailable",
    };
    private int _selectedCarId;

    public CarsPage()
    {
        InitializeComponent();
        CarsCollection.ItemsSource = _cars;
        CategoryPicker.ItemsSource = _categories;
        FuelTypePicker.ItemsSource = _fuelTypes;
        TransmissionPicker.ItemsSource = _transmissions;
        StatusPicker.ItemsSource = _statuses;
        CategoryPicker.SelectedIndex = 0;
        FuelTypePicker.SelectedIndex = 0;
        TransmissionPicker.SelectedIndex = 0;
        StatusPicker.SelectedIndex = 0;
        SeatsEntry.Text = "5";
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();

        if (_cars.Count == 0)
        {
            await LoadCarsAsync();
        }
    }

    private async Task LoadCarsAsync()
    {
        try
        {
            var cars = await _service.GetCarsAsync(ApiBase);

            _cars.Clear();
            foreach (var car in cars)
            {
                _cars.Add(car);
            }

            StatusLabel.Text = "Autók betöltve: " + _cars.Count;
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private async void OnLoadClicked(object sender, EventArgs e)
    {
        await LoadCarsAsync();
    }

    private void OnCarSelected(object sender, SelectionChangedEventArgs e)
    {
        var car = e.CurrentSelection.FirstOrDefault() as CarItem;

        if (car == null)
        {
            _selectedCarId = 0;
            return;
        }

        _selectedCarId = car.Id;
        BrandEntry.Text = car.Brand;
        ModelEntry.Text = car.Model;
        CategoryPicker.SelectedItem = car.Category;
        YearEntry.Text = car.Year.ToString();
        PlateEntry.Text = car.PlateNumber;
        ColorEntry.Text = car.Color ?? "";
        FuelTypePicker.SelectedItem = car.FuelType;
        TransmissionPicker.SelectedItem = car.Transmission;
        SeatsEntry.Text = car.Seats > 0 ? car.Seats.ToString() : "5";
        PriceEntry.Text = car.DailyPrice;
        StatusPicker.SelectedItem = car.Status;
        DescriptionEditor.Text = car.Description ?? "";
    }

    private async void OnAddClicked(object sender, EventArgs e)
    {
        try
        {
            var year = int.Parse(YearEntry.Text ?? "0");
            var seats = int.Parse(SeatsEntry.Text ?? "0");
            await _service.AddCarAsync(
                ApiBase,
                BrandEntry.Text ?? "",
                ModelEntry.Text ?? "",
                GetRequiredPickerValue(CategoryPicker, "Kategória"),
                year,
                PlateEntry.Text ?? "",
                ColorEntry.Text ?? "",
                GetRequiredPickerValue(FuelTypePicker, "Üzemanyag"),
                GetRequiredPickerValue(TransmissionPicker, "Váltó"),
                seats,
                PriceEntry.Text ?? "0",
                GetRequiredPickerValue(StatusPicker, "Státusz"),
                DescriptionEditor.Text ?? "");
            StatusLabel.Text = "Autó hozzáadva";
            await LoadCarsAsync();
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private async void OnUpdateClicked(object sender, EventArgs e)
    {
        try
        {
            if (_selectedCarId == 0)
            {
                StatusLabel.Text = "Válassz ki autót";
                return;
            }

            var year = int.Parse(YearEntry.Text ?? "0");
            var seats = int.Parse(SeatsEntry.Text ?? "0");
            await _service.UpdateCarAsync(
                ApiBase,
                _selectedCarId,
                BrandEntry.Text ?? "",
                ModelEntry.Text ?? "",
                GetRequiredPickerValue(CategoryPicker, "Kategória"),
                year,
                PlateEntry.Text ?? "",
                ColorEntry.Text ?? "",
                GetRequiredPickerValue(FuelTypePicker, "Üzemanyag"),
                GetRequiredPickerValue(TransmissionPicker, "Váltó"),
                seats,
                PriceEntry.Text ?? "0",
                GetRequiredPickerValue(StatusPicker, "Státusz"),
                DescriptionEditor.Text ?? "");
            StatusLabel.Text = "Autó módosítva";
            await LoadCarsAsync();
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private async void OnDeleteClicked(object sender, EventArgs e)
    {
        try
        {
            if (_selectedCarId == 0)
            {
                StatusLabel.Text = "Válassz ki autót";
                return;
            }

            await _service.DeleteCarAsync(ApiBase, _selectedCarId);
            _selectedCarId = 0;
            StatusLabel.Text = "Autó törölve";
            await LoadCarsAsync();
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private static string GetRequiredPickerValue(Picker picker, string fieldName)
    {
        var value = picker.SelectedItem as string;

        if (string.IsNullOrWhiteSpace(value)) {
            throw new InvalidOperationException(fieldName + " kiválasztása kötelező.");
        }

        return value;
    }
}
