<?php

namespace App\Services\Category\Providers;

use App\Validators\CustomValidator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap migrations and factories for:
     * - `php artisan migrate` command.
     * - factory() helper.
     *
     * Previous usage:
     * php artisan migrate --path=src/Services/Category/database/migrations
     * Now:
     * php artisan migrate
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            realpath(__DIR__ . '/../database/migrations')
        ]);
        $this->app->validator->resolver(function ($category, $data, $rules, $messages, $attributes) {
            return new CustomValidator($category, $data, $rules, $messages, $attributes);
        });
    }

    /**
    * Register the Category service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(BroadcastServiceProvider::class);

        $this->registerResources();
    }

    /**
     * Register the Category service resource namespaces.
     *
     * @return void
     */
    protected function registerResources()
    {
        // Translation must be registered ahead of adding lang namespaces
        $this->app->register(TranslationServiceProvider::class);

        Lang::addNamespace('category', realpath(__DIR__.'/../resources/lang'));

        View::addNamespace('category', base_path('resources/views/vendor/category'));
        View::addNamespace('category', realpath(__DIR__.'/../resources/views'));
    }
}
