<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    public function categories()
    {
        // create sitemap
        $sitemap = app('sitemap');

        // set cache
        $sitemap->setCache('laravel.sitemap-categories', 3600);

        // add items
        $categories = Category::select('slug', 'updated_at')->latest()->get();

        foreach ($categories as $cat) {
            $sitemap->add(url('category', $cat->slug), $cat->updated_at, '0.5', 'daily');
        }

        // show sitemap
        return $sitemap->render('xml');
    }

    public function tags()
    {
        // create sitemap
        $sitemap = app('sitemap');

        // set cache
        $sitemap->setCache('laravel.sitemap-tags', 3600);

        // add items
        $tags = Tag::select('slug', 'updated_at')->latest()->get();

        foreach ($tags as $tag) {
            $sitemap->add(url('tag', $tag->slug), $tag->updated_at, '0.5', 'weekly');
        }

        // show sitemap
        return $sitemap->render('xml');
    }

    public function users()
    {
        // create sitemap
        $sitemap = app('sitemap');

        // set cache
        $sitemap->setCache('laravel.sitemap-users', 3600);

        // add items
        $users = User::select('username', 'updated_at')->latest()->get();

        foreach ($users as $user) {
            $sitemap->add(url('user', $user->username), $user->updated_at, '0.5', 'weekly');
        }

        // show sitemap
        return $sitemap->render('xml');
    }

    public function posts()
    {
        // create sitemap
        $sitemap = app('sitemap');

        // set cache
        $sitemap->setCache('laravel.sitemap-posts', 3600);

        // add items
        $posts = Post::select('slug', 'image', 'title', 'updated_at', 'featured')->latest('updated_at')->get();

        $sitemap->add(url('/'), $posts->first()->updated_at, '1', 'daily');
        $sitemap->add(url('post'), $posts->first()->updated_at, '0.9', 'daily');
        $sitemap->add(url('post/featured'), $posts->where('featured', true)->first()->updated_at, '0.9', 'daily');

        foreach ($posts as $post) {
            $image = [];
            if (str_contains($post->image, Storage::url('/'))) {
                $image[] = [
                    'url' => $post->image,
                    'caption' => $post->title,
                ];
            }

            $sitemap->add(url('post', $post->slug), $post->updated_at, '0.8', 'monthly', $image);
        }

        // show sitemap
        return $sitemap->render('xml');
    }
}
