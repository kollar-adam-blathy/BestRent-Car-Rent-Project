using System.Collections.ObjectModel;
using BestRentMobile.Models;
using BestRentMobile.Services;

namespace BestRentMobile.Pages;

public partial class ReservationsPage : ContentPage
{
    private const string ApiBase = "http://127.0.0.1:8000/api";
    private readonly CarApiService _service = new CarApiService();
    private readonly ObservableCollection<CarItem> _cars = new ObservableCollection<CarItem>();
    private readonly ObservableCollection<ReservationItem> _reservations = new ObservableCollection<ReservationItem>();
    private readonly ObservableCollection<string> _locations = new ObservableCollection<string>();
    private readonly ObservableCollection<string> _statuses = new ObservableCollection<string>
    {
        "pending",
        "confirmed",
        "active",
        "cancelled",
        "completed",
    };

    private int _selectedReservationId;

    public ReservationsPage()
    {
        InitializeComponent();
        ReservationsCollection.ItemsSource = _reservations;
        ReservationCarPicker.ItemsSource = _cars;
        PickupLocationPicker.ItemsSource = _locations;
        DropoffLocationPicker.ItemsSource = _locations;
        ReservationStatusPicker.ItemsSource = _statuses;
        ReservationStatusPicker.SelectedIndex = 0;
        StartDatePicker.Date = DateTime.Today;
        EndDatePicker.Date = DateTime.Today.AddDays(1);
    }

    protected override async void OnAppearing()
    {
        base.OnAppearing();

        if (_reservations.Count == 0)
        {
            await LoadDataAsync();
        }
    }

    private async Task LoadDataAsync()
    {
        try
        {
            var carsTask = _service.GetCarsAsync(ApiBase);
            var reservationsTask = _service.GetReservationsAsync(ApiBase);
            var locationsTask = _service.GetReservationLocationsAsync(ApiBase);

            await Task.WhenAll(carsTask, reservationsTask, locationsTask);

            _cars.Clear();
            foreach (var car in carsTask.Result)
            {
                _cars.Add(car);
            }

            _reservations.Clear();
            foreach (var reservation in reservationsTask.Result)
            {
                _reservations.Add(reservation);
            }

            _locations.Clear();
            foreach (var location in locationsTask.Result)
            {
                _locations.Add(location);
            }

            if (_locations.Count > 0)
            {
                PickupLocationPicker.SelectedIndex = Math.Max(PickupLocationPicker.SelectedIndex, 0);
                DropoffLocationPicker.SelectedIndex = Math.Max(DropoffLocationPicker.SelectedIndex, 0);
            }

            StatusLabel.Text = "Bérlések betöltve: " + _reservations.Count;
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private async void OnLoadClicked(object sender, EventArgs e)
    {
        await LoadDataAsync();
    }

    private void OnReservationSelected(object sender, SelectionChangedEventArgs e)
    {
        var reservation = e.CurrentSelection.FirstOrDefault() as ReservationItem;

        if (reservation == null)
        {
            _selectedReservationId = 0;
            return;
        }

        _selectedReservationId = reservation.Id;
        ReservationUserIdEntry.Text = reservation.UserId.ToString();

        var selectedCar = _cars.FirstOrDefault(c => c.Id == reservation.CarId);
        if (selectedCar != null)
        {
            ReservationCarPicker.SelectedItem = selectedCar;
        }

        if (DateTime.TryParse(reservation.StartDate, out var startDate))
        {
            StartDatePicker.Date = startDate;
        }

        if (DateTime.TryParse(reservation.EndDate, out var endDate))
        {
            EndDatePicker.Date = endDate;
        }

        PickupLocationPicker.SelectedItem = reservation.PickupLocation;
        DropoffLocationPicker.SelectedItem = reservation.DropoffLocation;
        ReservationStatusPicker.SelectedItem = reservation.Status;
        ReservationNotesEditor.Text = reservation.Notes ?? "";
    }

    private async void OnUpdateClicked(object sender, EventArgs e)
    {
        try
        {
            if (_selectedReservationId == 0)
            {
                StatusLabel.Text = "Válassz ki bérlést";
                return;
            }

            var userId = ParseRequiredInt(ReservationUserIdEntry.Text, "Felhasználó ID");
            var car = ReservationCarPicker.SelectedItem as CarItem;
            var pickup = PickupLocationPicker.SelectedItem as string;
            var dropoff = DropoffLocationPicker.SelectedItem as string;
            var status = ReservationStatusPicker.SelectedItem as string;
            var startDate = StartDatePicker.Date ?? DateTime.Today;
            var endDate = EndDatePicker.Date ?? startDate;

            if (car == null || string.IsNullOrWhiteSpace(pickup) || string.IsNullOrWhiteSpace(dropoff) || string.IsNullOrWhiteSpace(status))
            {
                StatusLabel.Text = "Minden kötelező mezőt válassz ki.";
                return;
            }

            if (endDate < startDate)
            {
                StatusLabel.Text = "A befejezés dátuma nem lehet korábbi a kezdésnél.";
                return;
            }

            await _service.UpdateReservationAsync(
                ApiBase,
                _selectedReservationId,
                userId,
                car.Id,
                startDate,
                endDate,
                pickup,
                dropoff,
                status,
                ReservationNotesEditor.Text ?? "");

            StatusLabel.Text = "Bérlés módosítva";
            await LoadDataAsync();
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
            if (_selectedReservationId == 0)
            {
                StatusLabel.Text = "Válassz ki bérlést";
                return;
            }

            await _service.DeleteReservationAsync(ApiBase, _selectedReservationId);
            _selectedReservationId = 0;
            StatusLabel.Text = "Bérlés törölve";
            await LoadDataAsync();
        }
        catch (Exception ex)
        {
            StatusLabel.Text = ex.Message;
        }
    }

    private static int ParseRequiredInt(string? value, string fieldName)
    {
        if (!int.TryParse(value, out var parsed) || parsed <= 0)
        {
            throw new InvalidOperationException(fieldName + " hibás vagy hiányzik.");
        }

        return parsed;
    }
}
