<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    // private $dataSelect = [
    //     'id', 'user_id', 'category_id', 'title', 'slug', 'excerpt', 'image', 'created_at'
    // ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'scope' => 'string|nullable' //trending
        ]);

        $query = Post::with(['category', 'author', 'tags']);

        if ($request->scope == 'featured') {
            $query->where('featured', true);
        }

        return PostResource::collection(
            $query->latest()->paginate()
        );
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
        if (auth()->check()) {
            $data = $request->validate([
                'title' => 'required|unique:posts,title|max:255',
                'category_id' => 'required|integer|exists:categories,id',
                'content' => 'required|string',
                'excerpt' => 'string|max:255',
                'tags' => 'array|nullable',
                'is_public' => 'boolean',
                'meta_keywords' => 'string|nullable'
            ]);
            $data['meta_description'] = $data['excerpt'];
            $data['slug'] = str_slug($data['title']);
            $data['user_id'] = auth()->id();
            $newPost = $this->post->create(array_except($data, 'tags'));
            $tagIds = [];
            foreach ($data['tags'] as $tag) {
                $tag = trim($tag);
                if ($tag == '') {
                    continue;
                }
                $aTag = Tag::firstOrCreate(['name' => $tag], ['slug' => str_slug($tag)]);
                $tagIds[] = $aTag->id;
            }
            $newPost->tags()->sync($tagIds);
            return $newPost;
        }

        abort(500, 'Please login to do this action');
    }

    /**
     * Display the specified resource.
     * @param  String $postSlug
     * @return \App\Http\Resources\PostResource
     */
    public function show($postSlug)
    {
        $post = Post::withDraft()->whereSlug($postSlug)->firstOrFail();
        if ($post->is_public || auth()->id() === $post->user_id) {
            return new PostResource($post->load(['author', 'category', 'tags', 'likes']));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
