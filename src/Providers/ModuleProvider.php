<?php namespace Edutalk\Base\Elfinder\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Edutalk\Base\Elfinder\Http\Middleware\BootstrapModuleMiddleware;

class ModuleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Load views*/
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'edutalk-elfinder');
        /*Load translations*/
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'edutalk-elfinder');

        $this->publishes([
            __DIR__ . '/../../resources/views' => config('view.paths')[0] . '/vendor/edutalk-elfinder',
        ], 'views');
        $this->publishes([
            __DIR__ . '/../../resources/lang' => base_path('resources/lang/vendor/edutalk-elfinder'),
        ], 'lang');
        $this->publishes([
            __DIR__ . '/../../config' => base_path('config'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../../resources/public' => public_path(),
        ], 'edutalk-public-assets');

        app()->booted(function () {
            $this->app->register(BootstrapModuleServiceProvider::class);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        load_module_helpers(__DIR__);

        $this->mergeConfigFrom(__DIR__ . '/../../config/edutalk-elfinder.php', 'edutalk-elfinder');

        $this->app->register(RouteServiceProvider::class);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', BootstrapModuleMiddleware::class);
    }
}
