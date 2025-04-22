<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = Marker::all();
        return view('maps.index', compact('markers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'description' => 'nullable|string'
            ]);

            $validated['added'] = now();
            $marker = Marker::create($validated);
            
            return response()->json([
                'success' => true,
                'marker' => $marker
            ]);
        } catch (\Exception $e) {
            \Log::error('Marker creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating marker'
            ], 500);
        }
    }

    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $validated['edited'] = now();
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
} 