<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $markers = Marker::all();
        return view('maps.index', compact('markers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $marker = Marker::create($validated);

        return response()->json([
            'success' => true,
            'marker' => $marker
        ]);
    }

    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $marker->update($validated);

        return response()->json([
            'success' => true,
            'marker' => $marker
        ]);
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();
        return response()->json(['success' => true]);
    }

    public function getMarkers()
    {
        $markers = Marker::all();
        return response()->json($markers);
    }
} 