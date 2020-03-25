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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_unless(auth()->check(), 500, 'Please login to do this action');
        $data = $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255',
            'tags' => 'array|nullable',
            'is_public' => 'boolean',
            'image' => 'string|nullable',
            'meta_keywords' => 'string|nullable',
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

    /**
     * Display the specified resource.
     * @param  String $postSlug
     * @return \App\Http\Resources\PostResource
     */
    public function show(Post $post)
    {
        abort_unless($post->is_public || auth()->id() === $post->user_id, 403);
        return new PostResource($post->load(['author', 'category', 'tags', 'likes']));
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
        $this->authorize('manage', $post);
        $data = $request->validate([
            'title' => 'required|max:255|unique:posts,title,' . $post->id,
            'category_id' => 'required|integer|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255|nullable',
            'tags' => 'array|nullable',
            'is_public' => 'boolean',
            'image' => 'string|nullable',
            'meta_keywords' => 'string|nullable',
        ]);
        $data['meta_description'] = $data['excerpt'];
        $data['slug'] = str_slug($data['title']);
        $data['user_id'] = auth()->id();
        $post->update(array_except($data, 'tags'));
        $tagIds = [];
        foreach ($data['tags'] as $tag) {
            $tag = trim($tag);
            if ($tag == '') {
                continue;
            }
            $aTag = Tag::firstOrCreate(['name' => $tag], ['slug' => str_slug($tag)]);
            $tagIds[] = $aTag->id;
        }
        $post->tags()->sync($tagIds);

        return $post;
    }

    /**
     * Change resource status
     *
     * @param  Request $request
     * @return array
     */
    public function changeStatus(Request $request, Post $post)
    {
        $data = $request->validate([
            'id' => 'array|nullable',
            'value' => 'required|boolean',
        ]);

        if (isset($post)) {
            $this->authorize('manage', $post);
            $post->update(['is_public' => $data['value']]);
        } else {
            abort_unless(is_array($data['id']), 422);
            \DB::transaction(function () use ($data) {
                $this->post->withDraft()->whereIn('id', $data['id'])
                    ->update(['is_public' => $data['value']]);
            });
        }

        return ['status' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('manage', $post);

        return [ 'status' => $post->delete() ];
    }
}
