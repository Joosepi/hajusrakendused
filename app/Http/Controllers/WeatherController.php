<?php

namespace App\Http\Controllers;

use App\Models\WeatherHistory;
use App\Models\FavoriteCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $apiKey = 'b2f9f9e69dc5f9099843c6f482335e8d';

    public function index()
    {
        $favoriteCities = auth()->check() 
            ? FavoriteCity::where('user_id', auth()->id())->get() 
            : collect([]);
            
        return view('weather.index', compact('favoriteCities'));
    }

    public function getWeatherData(Request $request)
    {
        $city = $request->query('city', 'Tallinn');
        $cacheKey = 'weather_' . strtolower($city);
        
        return Cache::remember($cacheKey, 300, function () use ($city) {
            try {
                // Current weather
                $current = $this->getCurrentWeather($city);
                
                // Forecast data
                $forecast = $this->getForecast($city);
                
                // Check for alerts
                $alerts = $this->checkForAlerts($current);
                
                // Store in history if user is authenticated
                if (auth()->check()) {
                    $this->storeWeatherHistory($city, $current);
                }

                return response()->json([
                    'current' => $current,
                    'forecast' => $forecast,
                    'alerts' => $alerts
                ]);
            } catch (\Exception $e) {
                Log::error('Weather API error: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to fetch weather data'], 500);
            }
        });
    }

    protected function getCurrentWeather($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch current weather');
        }

        $data = $response->json();
        return [
            'temperature' => round($data['main']['temp']),
            'humidity' => $data['main']['humidity'],
            'windSpeed' => round($data['wind']['speed']),
            'description' => ucfirst($data['weather'][0]['description']),
            'main' => $data['weather'][0]['main']
        ];
    }

    protected function getForecast($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch forecast');
        }

        $data = $response->json();
        return collect($data['list'])
            ->take(5)
            ->map(function ($day) {
                return [
                    'date' => $day['dt'],
                    'temp' => round($day['main']['temp']),
                    'description' => ucfirst($day['weather'][0]['description']),
                    'icon' => $day['weather'][0]['icon']
                ];
            });
    }

    protected function checkForAlerts($weatherData)
    {
        $alerts = [];
        
        // Temperature alerts
        if ($weatherData['temperature'] > 30) {
            $alerts[] = ['type' => 'high_temperature', 'message' => 'Extreme heat warning'];
        }
        if ($weatherData['temperature'] < -15) {
            $alerts[] = ['type' => 'low_temperature', 'message' => 'Extreme cold warning'];
        }

        // Wind alerts
        if ($weatherData['windSpeed'] > 20) {
            $alerts[] = ['type' => 'high_wind', 'message' => 'Strong wind warning'];
        }

        return $alerts;
    }

    protected function storeWeatherHistory($city, $weatherData)
    {
        WeatherHistory::create([
            'user_id' => auth()->id(),
            'city' => $city,
            'temperature' => $weatherData['temperature'],
            'conditions' => $weatherData['main'],
            'recorded_at' => now()
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);
        
        $favoriteCity = FavoriteCity::where('user_id', auth()->id())
            ->where('city', $request->city)
            ->first();

        if ($favoriteCity) {
            $favoriteCity->delete();
            $message = 'City removed from favorites';
        } else {
            FavoriteCity::create([
                'user_id' => auth()->id(),
                'city' => $request->city
            ]);
            $message = 'City added to favorites';
        }

        return response()->json(['message' => $message]);
    }

    public function getHistory()
    {
        $history = WeatherHistory::where('user_id', auth()->id())
            ->orderBy('recorded_at', 'desc')
            ->take(10)
            ->get();
            
        return response()->json(['history' => $history]);
    }
} 