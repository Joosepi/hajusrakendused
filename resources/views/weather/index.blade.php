@extends('layouts.app')

@section('title', 'Weather')

@section('content')
<div class="container py-4">
    <div class="card bg-dark border-secondary weather-card">
        <div class="card-body">
            <h2 class="card-title text-light mb-4">Weather Information</h2>
            
            <!-- Search and Favorites -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group weather-search">
                        <input type="text" 
                               id="cityInput" 
                               class="form-control bg-darker text-light" 
                               placeholder="Enter city name" 
                               value="Tallinn">
                        <button class="btn btn-primary" id="getWeather">
                            <i class="fas fa-search me-2"></i>Get Weather
                        </button>
                        @auth
                        <button class="btn btn-outline-primary ms-2" id="toggleFavorite">
                            <i class="fas fa-star"></i>
                        </button>
                        @endauth
                    </div>
                </div>
                @auth
                <div class="col-md-6">
                    <div class="favorite-cities">
                        @foreach($favoriteCities as $city)
                        <button class="btn btn-sm btn-outline-light me-2 mb-2 favorite-city">
                            {{ $city->city }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endauth
            </div>

            <!-- Weather Alerts -->
            <div id="weatherAlerts" class="mb-4"></div>

            <!-- Current Weather -->
            <div id="weatherInfo" class="weather-container mb-4">
                <div class="current-weather">
                    <h3 class="city-name text-light mb-4">
                        <span id="cityName">Tallinn</span>
                        <button class="btn btn-sm btn-primary ms-3" id="liveData">Live Data</button>
                    </h3>
                    
                    <div class="weather-grid">
                        <div class="weather-item">
                            <div class="weather-icon">
                                <i class="fas fa-temperature-high"></i>
                            </div>
                            <div class="weather-data">
                                <span class="label">Temperature</span>
                                <span class="value" id="temperature">--°C</span>
                            </div>
                        </div>

                        <div class="weather-item">
                            <div class="weather-icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div class="weather-data">
                                <span class="label">Humidity</span>
                                <span class="value" id="humidity">--%</span>
                            </div>
                        </div>

                        <div class="weather-item">
                            <div class="weather-icon">
                                <i class="fas fa-wind"></i>
                            </div>
                            <div class="weather-data">
                                <span class="label">Wind Speed</span>
                                <span class="value" id="windSpeed">-- m/s</span>
                            </div>
                        </div>

                        <div class="weather-item">
                            <div class="weather-icon">
                                <i class="fas fa-cloud"></i>
                            </div>
                            <div class="weather-data">
                                <span class="label">Description</span>
                                <span class="value" id="description">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather Forecast -->
            <div class="forecast-section mb-4">
                <h4 class="text-light mb-3">5-Day Forecast</h4>
                <div id="forecast" class="forecast-grid"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .weather-card {
        background: rgba(17, 17, 17, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(10px);
    }

    .bg-darker {
        background-color: rgba(26, 26, 26, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .weather-search .form-control {
        border-color: rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1rem;
    }

    .weather-search .form-control:focus {
        box-shadow: 0 0 0 2px rgba(var(--accent-rgb), 0.25);
    }

    .weather-container {
        padding: 1.5rem;
        border-radius: 12px;
        background-color: rgba(30, 30, 30, 0.5);
    }

    .current-weather {
        padding: 1.5rem;
    }

    .weather-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .weather-item {
        background: rgba(42, 42, 42, 0.7);
        padding: 1.75rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .weather-item:hover {
        transform: translateY(-5px);
        background: rgba(51, 51, 51, 0.8);
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .weather-icon {
        font-size: 2.25rem;
        color: var(--accent-color);
        margin-right: 1.25rem;
        width: 45px;
        text-align: center;
        opacity: 0.9;
    }

    .weather-data {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .weather-data .label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .weather-data .value {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.35rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .city-name {
        display: flex;
        align-items: center;
        font-size: 2rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    #liveData {
        font-size: 0.9rem;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    #liveData.btn-success {
        background: rgba(40, 167, 69, 0.9);
    }

    .btn-primary {
        background: var(--accent-color);
        border: none;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--accent-color-hover);
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .weather-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .weather-item {
            padding: 1.25rem;
        }

        .city-name {
            font-size: 1.5rem;
        }
    }

    .forecast-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .forecast-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
    }

    .forecast-date {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .forecast-temp {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .forecast-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .weather-alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-high-temperature {
        background: rgba(255, 59, 48, 0.2);
        border: 1px solid rgba(255, 59, 48, 0.3);
    }

    .alert-low-temperature {
        background: rgba(0, 122, 255, 0.2);
        border: 1px solid rgba(0, 122, 255, 0.3);
    }

    .alert-high-wind {
        background: rgba(255, 149, 0, 0.2);
        border: 1px solid rgba(255, 149, 0, 0.3);
    }

    .favorite-cities {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const getWeatherBtn = document.getElementById('getWeather');
    const cityInput = document.getElementById('cityInput');
    const liveDataBtn = document.getElementById('liveData');
    let weatherUpdateInterval;

    function updateWeatherDisplay(data) {
        document.getElementById('cityName').textContent = cityInput.value;
        document.getElementById('temperature').textContent = `${data.current.temperature}°C`;
        document.getElementById('humidity').textContent = `${data.current.humidity}%`;
        document.getElementById('windSpeed').textContent = `${data.current.windSpeed} m/s`;
        document.getElementById('description').textContent = data.current.description;

        const forecastHtml = data.forecast.map(day => `
            <div class="forecast-card">
                <div class="forecast-date">${new Date(day.date * 1000).toLocaleDateString()}</div>
                <div class="forecast-temp">${day.temp}°C</div>
                <div class="forecast-desc">${day.description}</div>
            </div>
        `).join('');
        document.getElementById('forecast').innerHTML = forecastHtml;

        const alertsHtml = data.alerts.map(alert => `
            <div class="weather-alert alert-${alert.type}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${alert.message}</span>
            </div>
        `).join('');
        document.getElementById('weatherAlerts').innerHTML = alertsHtml;
    }

    function fetchWeather() {
        fetch(`/weather/data?city=${encodeURIComponent(cityInput.value)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                updateWeatherDisplay(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch weather data');
            });
    }

    getWeatherBtn.addEventListener('click', fetchWeather);

    cityInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            fetchWeather();
        }
    });

    liveDataBtn.addEventListener('click', function() {
        if (weatherUpdateInterval) {
            clearInterval(weatherUpdateInterval);
            weatherUpdateInterval = null;
            this.textContent = 'Live Data';
            this.classList.remove('btn-success');
            this.classList.add('btn-primary');
        } else {
            fetchWeather();
            weatherUpdateInterval = setInterval(fetchWeather, 60000); // Update every minute
            this.textContent = 'Live (On)';
            this.classList.remove('btn-primary');
            this.classList.add('btn-success');
        }
    });

    // Initial weather fetch
    fetchWeather();

    // Add favorite city functionality
    const toggleFavoriteBtn = document.getElementById('toggleFavorite');
    if (toggleFavoriteBtn) {
        toggleFavoriteBtn.addEventListener('click', function() {
            fetch('/weather/favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ city: cityInput.value })
            })
            .then(response => response.json())
            .then(data => {
                // Update UI accordingly
                location.reload();
            });
        });
    }

    // Make favorite cities clickable
    document.querySelectorAll('.favorite-city').forEach(btn => {
        btn.addEventListener('click', function() {
            cityInput.value = this.textContent.trim();
            fetchWeather();
        });
    });
});
</script>
@endpush
@endsection 