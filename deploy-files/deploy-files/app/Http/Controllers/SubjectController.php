<?php

namespace App\Http\Controllers;

use App\Models\MyFavoriteSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = MyFavoriteSubject::with('user')->latest()->get();

        // If request wants JSON or includes 'api' in URL
        if ($request->wantsJson() || str_contains($request->path(), 'api')) {
            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        }

        // Otherwise return the view
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
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
        
        MyFavoriteSubject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject added successfully!');
    }

    public function edit(MyFavoriteSubject $subject)
    {
        // Check if user can edit this subject
        if (!auth()->user()->is_admin && auth()->id() !== $subject->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, MyFavoriteSubject $subject)
    {
        // Check if user can update this subject
        if (!auth()->user()->is_admin && auth()->id() !== $subject->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subject->image) {
                Storage::disk('public')->delete($subject->image);
            }
            $validated['image'] = $request->file('image')->store('subjects', 'public');
        }

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy(MyFavoriteSubject $subject)
    {
        // Check if user can delete this subject
        if (!auth()->user()->is_admin && auth()->id() !== $subject->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($subject->image) {
            Storage::disk('public')->delete($subject->image);
        }

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }
}