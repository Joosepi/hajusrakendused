<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyFavoriteSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MyFavoriteSubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = MyFavoriteSubject::with('user')->latest()->get();
        
        // If the request wants JSON, return JSON response
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        }
        
        // Otherwise return the view
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('subjects', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = auth()->id();
        
        $subject = MyFavoriteSubject::create($validated);

        // Clear cache when new item is added
        Cache::forget('favorite_subjects');
        
        return response()->json([
            'success' => true,
            'data' => $subject
        ], 201);
    }
}
