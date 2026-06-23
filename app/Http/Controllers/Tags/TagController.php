<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::latest()->get();

        if (! $request->expectsJson()) {
            return view('tags.index', compact('tags'));
        }

        return response()->json([
            'tags' => $tags,
        ]);
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->validated());

        if (! $request->expectsJson()) {
            return redirect()
                ->route('tags.index')
                ->with('success', 'Tag created successfully');
        }

        return response()->json([
            'tag' => $tag,
        ], 201);
    }
}
