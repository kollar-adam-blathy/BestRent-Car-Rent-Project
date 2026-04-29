using System.Text.Json.Serialization;

namespace BestRentMobile.Models;

public class ReservationItem
{
    [JsonPropertyName("id")]
    public int Id { get; set; }

    [JsonPropertyName("user_id")]
    public int UserId { get; set; }

    [JsonPropertyName("car_id")]
    public int CarId { get; set; }

    [JsonPropertyName("start_date")]
    public string StartDate { get; set; } = "";

    [JsonPropertyName("end_date")]
    public string EndDate { get; set; } = "";

    [JsonPropertyName("pickup_location")]
    public string PickupLocation { get; set; } = "";

    [JsonPropertyName("dropoff_location")]
    public string DropoffLocation { get; set; } = "";

    [JsonPropertyName("total_price")]
    public string TotalPrice { get; set; } = "";

    [JsonPropertyName("status")]
    public string Status { get; set; } = "pending";

    [JsonPropertyName("notes")]
    public string? Notes { get; set; }

    [JsonPropertyName("car")]
    public ReservationCarInfo? Car { get; set; }

    public string Title => "#" + Id + " - " + (Car?.Brand ?? "") + " " + (Car?.Model ?? "");

    public string Subtitle => StartDate + " -> " + EndDate + " | " + Status + " | " + TotalPrice + " Ft";
}

public class ReservationCarInfo
{
    [JsonPropertyName("id")]
    public int Id { get; set; }

    [JsonPropertyName("brand")]
    public string Brand { get; set; } = "";

    [JsonPropertyName("model")]
    public string Model { get; set; } = "";
}
