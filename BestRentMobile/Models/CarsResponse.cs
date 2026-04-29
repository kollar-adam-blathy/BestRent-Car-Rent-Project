using System.Text.Json.Serialization;

namespace BestRentMobile.Models;

public class CarsResponse
{
    [JsonPropertyName("cars")]
    public List<CarItem> Cars { get; set; } = new List<CarItem>();
}
