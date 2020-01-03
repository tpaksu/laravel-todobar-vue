<?php

namespace TPaksu\TodoBar;

use Illuminate\Support\ServiceProvider;
use TPaksu\TodoBar\Storage\DataStorageInterface;

class TodoBarServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/config/todobar.php', "todobar");
        $this->publishes([
            __DIR__ . '/config/todobar.php' => config_path('todobar.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('todobar.enabled', false) === true) {
            $this->app->make('TPaksu\TodoBar\Controllers\TodoBarController');

            $this->app->singleton(DataStorageInterface::class, function () {
                $storage = config("todobar.storage.engine", Storage\JSONStorage::class);
                $config = config("todobar.storage.params", ["file" => "items.json"]);
                if (class_exists($storage) && in_array(DataStorageInterface::class, class_implements($storage))) {
                    return new $storage($config);
                }
                throw new \Exception("unrecognized storage engine");
                return new Storage\JSONStorage(["file" => "items.json"]);
            });

            $this->app["router"]->pushMiddlewareToGroup("web", "\TPaksu\TodoBar\Middleware\TodoBarMiddleware");
        }
    }
}
