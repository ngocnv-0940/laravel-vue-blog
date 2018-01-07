<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Category::truncate();
        Post::truncate();
        Tag::truncate();
        Comment::truncate();
        Like::truncate();
        DB::table('taggables')->truncate();

        factory(User::class)->create([
            'name' => 'Nguyen Van Ngoc',
            'username' => 'blaysku',
            'email' => 'ngocnguyencnttk35@gmail.com',
            'is_admin' => true,
            'password' => bcrypt('123123'),
        ]);
        factory(User::class, 10)->create();

        factory(Category::class, 3)->create()->each(function ($c) {
            $c->childs()->saveMany(factory(Category::class, 2)->make());
        });

        factory(Post::class, 100)->create()->each(function ($p) {
            $p->tags()->saveMany(factory(Tag::class, rand(2, 5))->make());
            $p->comments()->saveMany(factory(Comment::class, rand(0, 3))->make())->each(function ($cm) use ($p) {
                $p->comments()->saveMany(factory(Comment::class, rand(0, 2))->make(['parent_id' => $cm->id]));
                $cm->likes()->saveMany(factory(Like::class, rand(0,5))->make());
            });

            $p->likes()->saveMany(factory(Like::class, rand(0, 10))->make());
        });
    }
}
