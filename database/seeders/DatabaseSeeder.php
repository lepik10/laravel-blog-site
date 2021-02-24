<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do you want to refresh the database?')) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        Cache::flush();

        $this->call([UsersTableSeeder::class, PostsTableSeeder::class, CommentsTableSeeder::class, TagsTableSeeder::class, PostTagTableSeeder::class]);
    }
}
