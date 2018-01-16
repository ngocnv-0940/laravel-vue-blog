<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SitemapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->call('cache:clear');
            $sitemap = app('sitemap');
            $tables = ['posts', 'categories', 'tags', 'users'];

            foreach ($tables as $table) {
                if (DB::table($table)->exists()) {
                    $sitemap->addSitemap(route('sitemap.' . $table));
                }
            }

            $sitemap->store('sitemapindex', 'sitemap');
            $this->info('Sitemap created at ' . url('sitemap.xml'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
