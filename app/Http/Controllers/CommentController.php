<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Database\Query\oldest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    const MODEL_PATH = 'App\\Models\\';

    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'type' => [
                'string',
                'nullable',
                Rule::in(['post', 'video'])
            ],
            'slug' => 'string'
        ]);

        $model = self::MODEL_PATH . studly_case(array_get($data, 'type', 'Post'));
        $modelInstance = app($model)->whereSlug($data['slug'])->firstOrFail();

        return CommentResource::collection($modelInstance
            ->comments()
            ->doesntHave('parent')
            ->with(['childs.user', 'childs.likes', 'user', 'likes'])
            ->paginate(2));
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
            $array = $request->validate([
                'type' => [
                    'string',
                    'nullable',
                    Rule::in(['post', 'video'])
                ],
                'slug' => 'string',
                'message' => 'required|string',
                'parent_id' => 'nullable|integer|exists:comments,id'
            ]);

            $model = self::MODEL_PATH . studly_case(array_get($array, 'type', 'Post'));
            $modelInstance = app($model)->whereSlug($array['slug'])->firstOrFail();

            return new CommentResource($modelInstance
                ->comments()
                ->create(
                    array_merge(
                        array_only($array, ['parent_id', 'message']),
                        ['user_id' => auth()->id()])
                )
                ->load(['user'])
            );
        }
        abort(500, 'Please login to do this action');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $a = $comment->commentable;
        $comment->delete();

        return ['comments_count' => $a->comments_count];
    }
}
