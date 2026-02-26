// public/js/weather_widget.js
async function showWeather(city, elementId) {
    const url = `https://wttr.in/${city}?format=j1`;
    
    // Configuramos un límite de tiempo (timeout) de 3 segundos
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 3000); 

    try {
        const response = await fetch(url, { signal: controller.signal });
        
        if (!response.ok) throw new Error('Respuesta de red no válida');
        
        const data = await response.json();

        const clima = data.current_condition[0];
        const temperatura = clima.temp_C;
        const viento = clima.windspeedKmph;
        const descripcion = clima.weatherDesc[0].value;

        document.getElementById(elementId).innerHTML =
            ` ${city}: ${temperatura}°C, ${descripcion}, viento ${viento} km/h`;
            
    } catch (error) {
        // Si el error fue por el tiempo límite, mostramos un mensaje más limpio
        if (error.name === 'AbortError') {
            console.warn(`Tiempo de espera agotado para ${city}`);
        } else {
            console.error("Error al obtener el clima:", error);
        }
        document.getElementById(elementId).textContent = "Clima no disponible";
    } finally {
        clearTimeout(timeoutId); // Limpiamos el reloj siempre al terminar
    }
}

document.addEventListener('DOMContentLoaded', () => {
    showWeather('Bogota', 'clima-bogota');
    showWeather('Paris', 'clima-paris');
});