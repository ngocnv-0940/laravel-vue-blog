<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $popularTags = Tag::join('taggables as t', function ($join) {
            $join->on('t.tag_id', '=', 'tags.id')
                ->where('t.taggable_type', '=', Post::class);
        })
        ->join('posts as p', 'p.id', '=', 't.taggable_id')
        ->select('tags.*', \DB::raw('count(p.id) as posts_count'))
        ->groupBy('tags.id')
        ->orderBy('posts_count', 'desc')
        ->take(10)
        ->get();

        return TagResource::collection($popularTags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($tagSlug)
    {
        $tag = Tag::whereSlug($tagSlug)->withCount('posts')->firstOrFail();
        $posts = $tag->posts()->with(['author', 'category', 'tags'])->latest()->paginate();

        return [
            'tag' => new TagResource($tag),
            'posts' => $posts
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
