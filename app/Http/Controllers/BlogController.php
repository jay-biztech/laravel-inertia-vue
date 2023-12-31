<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public $search = '';
    public $id = '';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $this->search = $input['search'] ?? '';
        $this->id = $input['id'] ?? '';

        if ($this->id) {
            $blog = Blog::find($this->id);
        }

        $blogs = Blog::query()
            ->when($this->search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();


        return Inertia::render(
            'Blogs/Index',
            [
                'totalCount' => Inertia::lazy(fn () => $this->totalCount()),
                'blogs' => fn() => $blogs,
                'blog' => Inertia::lazy(fn () => $blog)
            ]
        );
    }

    public function totalCount() 
    {
        sleep(2);

        return Blog::all()->count();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render(
            'Blogs/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);
        Blog::create([
            'title' => $request->title,
            'content' => $request->content
        ]);
        sleep(1);

        return redirect()->route('blogs.index')->with('message', 'Blog Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return Inertia::render(
            'Blogs/Edit',
            [
                'blog' => $blog
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save();
        sleep(1);

        return redirect()->route('blogs.index')->with('message', 'Blog Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        sleep(1);

        return redirect()->route('blogs.index')->with('message', 'Blog Delete Successfully');
    }
}