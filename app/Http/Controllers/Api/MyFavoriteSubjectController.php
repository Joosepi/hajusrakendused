<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyFavoriteSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MyFavoriteSubjectController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $cacheKey = 'favorite_subjects_' . $limit;
        
        $subjects = Cache::remember($cacheKey, 3600, function () use ($limit) {
            return MyFavoriteSubject::with('user')
                ->latest()
                ->take($limit)
                ->get();
        });
        
        return response()->json([
            'success' => true,
            'data' => $subjects,
            'meta' => [
                'total' => MyFavoriteSubject::count(),
                'limit' => $limit
            ]
        ]);
    }

    public function show($id)
    {
        $cacheKey = 'favorite_subject_' . $id;
        
        $subject = Cache::remember($cacheKey, 3600, function () use ($id) {
            return MyFavoriteSubject::with('user')->findOrFail($id);
        });
        
        return response()->json([
            'success' => true,
            'data' => $subject
        ]);
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

        // Clear relevant caches
        Cache::forget('favorite_subjects_*');
        
        return response()->json([
            'success' => true,
            'data' => $subject
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $subject = MyFavoriteSubject::findOrFail($id);
        
        // Check authorization
        if (auth()->id() !== $subject->user_id && !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre' => 'sometimes|string|max:255',
            'release_year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subject->image) {
                Storage::disk('public')->delete($subject->image);
            }
            $validated['image'] = $request->file('image')->store('subjects', 'public');
        }

        $subject->update($validated);

        // Clear relevant caches
        Cache::forget('favorite_subjects_*');
        Cache::forget('favorite_subject_' . $id);
        
        return response()->json([
            'success' => true,
            'data' => $subject
        ]);
    }

    public function destroy($id)
    {
        $subject = MyFavoriteSubject::findOrFail($id);
        
        // Check authorization
        if (auth()->id() !== $subject->user_id && !auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete image if exists
        if ($subject->image) {
            Storage::disk('public')->delete($subject->image);
        }

        $subject->delete();

        // Clear relevant caches
        Cache::forget('favorite_subjects_*');
        Cache::forget('favorite_subject_' . $id);
        
        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully'
        ]);
    }
}
