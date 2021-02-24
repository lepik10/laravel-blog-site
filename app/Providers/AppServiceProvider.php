<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Observers\PostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\Comment as CommentResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
//        Blade::include('components.badge', 'badge');
//        Blade::include('components.udated', 'udated');

        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);

        $this->app->singleton(Counter::class, function($app) {
            return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                5
            );
        });

        CommentResource::withoutWrapping();

//        $this->app->bind(
//            'App\Contracts\CounterContract',
//            Counter::class
//        );

        //$this->app->when(Counter::class)->needs('$timeout')->give(5);
    }
}
