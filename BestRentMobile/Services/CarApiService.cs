using System.Text;
using System.Text.Json;
using BestRentMobile.Models;

namespace BestRentMobile.Services;

public class CarApiService
{
    private readonly HttpClient _httpClient = new HttpClient();
    private static readonly JsonSerializerOptions JsonOptions = new JsonSerializerOptions
    {
        PropertyNameCaseInsensitive = true,
    };

    private static string BuildUrl(string apiBase, string endpoint)
    {
        return apiBase.TrimEnd('/') + endpoint;
    }

    public async Task<List<CarItem>> GetCarsAsync(string apiBase)
    {
        var response = await _httpClient.GetAsync(BuildUrl(apiBase, "/cars"));
        var text = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(text);
        }

        var data = JsonSerializer.Deserialize<CarsResponse>(text, JsonOptions);

        return data?.Cars ?? new List<CarItem>();
    }

    public async Task AddCarAsync(
        string apiBase,
        string brand,
        string model,
        string category,
        int year,
        string plateNumber,
        string color,
        string fuelType,
        string transmission,
        int seats,
        string dailyPrice,
        string status,
        string description)
    {
        var body = new
        {
            brand,
            model,
            category,
            year,
            plate_number = plateNumber,
            color = string.IsNullOrWhiteSpace(color) ? null : color,
            fuel_type = string.IsNullOrWhiteSpace(fuelType) ? null : fuelType,
            transmission = string.IsNullOrWhiteSpace(transmission) ? null : transmission,
            seats,
            daily_price = dailyPrice,
            status,
            description = string.IsNullOrWhiteSpace(description) ? null : description,
        };

        var text = JsonSerializer.Serialize(body);
        var content = new StringContent(text, Encoding.UTF8, "application/json");
        var response = await _httpClient.PostAsync(BuildUrl(apiBase, "/cars"), content);
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }

    public async Task AddCarAsync(string apiBase, string brand, string model, int year, string plateNumber, string dailyPrice)
    {
        await AddCarAsync(
            apiBase,
            brand,
            model,
            "Sedan",
            year,
            plateNumber,
            "",
            "Benzin",
            "Automata",
            5,
            dailyPrice,
            "available",
            "");
    }

    public async Task UpdateCarAsync(
        string apiBase,
        int id,
        string brand,
        string model,
        string category,
        int year,
        string plateNumber,
        string color,
        string fuelType,
        string transmission,
        int seats,
        string dailyPrice,
        string status,
        string description)
    {
        var body = new
        {
            brand,
            model,
            category,
            year,
            plate_number = plateNumber,
            color = string.IsNullOrWhiteSpace(color) ? null : color,
            fuel_type = string.IsNullOrWhiteSpace(fuelType) ? null : fuelType,
            transmission = string.IsNullOrWhiteSpace(transmission) ? null : transmission,
            seats,
            daily_price = dailyPrice,
            status,
            description = string.IsNullOrWhiteSpace(description) ? null : description,
        };

        var text = JsonSerializer.Serialize(body);
        var content = new StringContent(text, Encoding.UTF8, "application/json");
        var response = await _httpClient.PutAsync(BuildUrl(apiBase, "/cars/" + id), content);
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }

    public async Task UpdateCarAsync(string apiBase, int id, string brand, string model, int year, string plateNumber, string dailyPrice)
    {
        await UpdateCarAsync(
            apiBase,
            id,
            brand,
            model,
            "Sedan",
            year,
            plateNumber,
            "",
            "Benzin",
            "Automata",
            5,
            dailyPrice,
            "available",
            "");
    }

    public async Task DeleteCarAsync(string apiBase, int id)
    {
        var response = await _httpClient.DeleteAsync(BuildUrl(apiBase, "/cars/" + id));
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }

    public async Task<List<ReservationItem>> GetReservationsAsync(string apiBase)
    {
        var response = await _httpClient.GetAsync(BuildUrl(apiBase, "/reservations"));
        var text = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(text);
        }

        var data = JsonSerializer.Deserialize<ReservationsResponse>(text, JsonOptions);

        return data?.Reservations ?? new List<ReservationItem>();
    }

    public async Task<List<string>> GetReservationLocationsAsync(string apiBase)
    {
        var response = await _httpClient.GetAsync(BuildUrl(apiBase, "/reservations/locations"));
        var text = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(text);
        }

        var data = JsonSerializer.Deserialize<LocationsResponse>(text, JsonOptions);

        return data?.Locations ?? new List<string>();
    }

    public async Task AddReservationAsync(
        string apiBase,
        int userId,
        int carId,
        DateTime startDate,
        DateTime endDate,
        string pickupLocation,
        string dropoffLocation,
        string notes)
    {
        var body = new
        {
            user_id = userId,
            car_id = carId,
            start_date = startDate.ToString("yyyy-MM-dd"),
            end_date = endDate.ToString("yyyy-MM-dd"),
            pickup_location = pickupLocation,
            dropoff_location = dropoffLocation,
            notes,
        };

        var text = JsonSerializer.Serialize(body);
        var content = new StringContent(text, Encoding.UTF8, "application/json");
        var response = await _httpClient.PostAsync(BuildUrl(apiBase, "/reservations"), content);
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }

    public async Task UpdateReservationAsync(
        string apiBase,
        int reservationId,
        int userId,
        int carId,
        DateTime startDate,
        DateTime endDate,
        string pickupLocation,
        string dropoffLocation,
        string status,
        string notes)
    {
        var body = new
        {
            user_id = userId,
            car_id = carId,
            start_date = startDate.ToString("yyyy-MM-dd"),
            end_date = endDate.ToString("yyyy-MM-dd"),
            pickup_location = pickupLocation,
            dropoff_location = dropoffLocation,
            status,
            notes,
        };

        var request = new HttpRequestMessage(new HttpMethod("PATCH"), BuildUrl(apiBase, "/reservations/" + reservationId))
        {
            Content = new StringContent(JsonSerializer.Serialize(body), Encoding.UTF8, "application/json"),
        };

        var response = await _httpClient.SendAsync(request);
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }

    public async Task DeleteReservationAsync(string apiBase, int reservationId)
    {
        var response = await _httpClient.DeleteAsync(BuildUrl(apiBase, "/reservations/" + reservationId));
        var result = await response.Content.ReadAsStringAsync();

        if (!response.IsSuccessStatusCode)
        {
            throw new Exception(result);
        }
    }
}
