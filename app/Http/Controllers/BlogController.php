<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Remove this line as it's causing the error
        // $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Post::with(['user', 'comments', 'categories']);

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'categories' => 'array|exists:categories,id'
        ]);

        $post = auth()->user()->posts()->create([
            'title' => $validated['title'],
            'description' => $validated['content']
        ]);

        if (isset($validated['categories'])) {
            $post->categories()->attach($validated['categories']);
        }

        return redirect()->route('blog.show', $post)
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        return view('blog.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('blog.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post->update($validated);
        return redirect()->route('blog.show', $post)->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();
        return redirect()->route('blog.index')->with('success', 'Post deleted successfully!');
    }
} 