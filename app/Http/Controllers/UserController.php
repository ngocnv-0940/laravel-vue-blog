<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $media;
    private $user;

    public function __construct(Media $media)
    {
        $this->media = $media;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Request $request)
    {
        $request->validate([
            'scope' => 'string|nullable'
        ]);

        $posts = $user->posts()->with(['author', 'category', 'tags']);

        if ($request->get('scope') == 'drafts') {
            if (auth()->id() !== $user->id) {
                abort(403);
            }
            $posts = $posts->onlyDraft();
        }

        return [
            'posts' => $posts->latest()->paginate(),
            'user' => new UserResource($user),
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        abort_unless(auth()->check(), 403);
        $request->validate([
            'uploads' => 'required|array',
            'uploads.*' => 'image',
        ]);
        try {
            DB::beginTransaction();
            foreach ($request->uploads as $upload) {
                $uploaded[]['url'] = $this->media->upload($upload, 'user');
            }
            $this->user->media()->createMany($uploaded);
            DB::commit();
            return $uploaded;
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
        }
    }

    public function media(User $user)
    {
        return MediaResource::collection($user->media()->latest()->paginate(12));
    }

    public function notifications()
    {
        return new NotificationCollection($this->user->notifications()->paginate(10));
    }

    public function readNotifications(Request $request)
    {
        try {
            $request->validate([
                'notifications' => 'required|array',
            ]);

            $this->user->notifications()->whereIn('id', $request->notifications)->update(['read_at' => now()]);

            return ['status' => true];
        } catch (\Exception $e) {
            return $e->getMessage();
            return ['status' => false];
        }
    }
}
