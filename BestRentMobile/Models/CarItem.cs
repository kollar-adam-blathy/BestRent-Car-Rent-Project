using System.Text.Json.Serialization;

namespace BestRentMobile.Models;

public class CarItem
{
    [JsonPropertyName("id")]
    public int Id { get; set; }

    [JsonPropertyName("brand")]
    public string Brand { get; set; } = "";

    [JsonPropertyName("model")]
    public string Model { get; set; } = "";

    [JsonPropertyName("category")]
    public string Category { get; set; } = "";

    [JsonPropertyName("year")]
    public int Year { get; set; }

    [JsonPropertyName("plate_number")]
    public string PlateNumber { get; set; } = "";

    [JsonPropertyName("color")]
    public string? Color { get; set; }

    [JsonPropertyName("fuel_type")]
    public string? FuelType { get; set; }

    [JsonPropertyName("transmission")]
    public string? Transmission { get; set; }

    [JsonPropertyName("seats")]
    public int Seats { get; set; }

    [JsonPropertyName("daily_price")]
    public string DailyPrice { get; set; } = "";

    [JsonPropertyName("status")]
    public string Status { get; set; } = "available";

    [JsonPropertyName("description")]
    public string? Description { get; set; }

    public string Title => Brand + " " + Model;
    public string Subtitle => "Év: " + Year + " | Rendszám: " + PlateNumber + " | Ár: " + DailyPrice + " | Státusz: " + Status;
}
