// public/js/weather_widget.js

async function showWeather(city, elementId) {
    const url = `https://wttr.in/${city}?format=j1`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        const clima = data.current_condition[0];
        const temperatura = clima.temp_C;
        const viento = clima.windspeedKmph;
        const descripcion = clima.weatherDesc[0].value;

        document.getElementById(elementId).innerHTML =
            ` ${city}: ${temperatura}°C, ${descripcion}, viento ${viento} km/h`;
    } catch (error) {
        console.error("Error al obtener el clima:", error);
        document.getElementById(elementId).textContent = "Error al obtener clima";
    }
}

document.addEventListener('DOMContentLoaded', () => {
    showWeather('Bogota', 'clima-bogota');
    showWeather('Paris', 'clima-paris');
});
