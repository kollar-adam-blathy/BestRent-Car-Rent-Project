using System.Text.Json.Serialization;

namespace BestRentMobile.Models;

public class ReservationsResponse
{
    [JsonPropertyName("reservations")]
    public List<ReservationItem> Reservations { get; set; } = new List<ReservationItem>();
}

public class LocationsResponse
{
    [JsonPropertyName("locations")]
    public List<string> Locations { get; set; } = new List<string>();
}
