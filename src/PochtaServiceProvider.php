<?php
/**
 * @author  Andrey Helldar <helldar@ai-rus.com>
 * @version 2016-12-26
 * @since   1.0
 */

namespace Helldar\Pochta;

use Illuminate\Support\ServiceProvider as ServiceProvider;

class PochtaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/pochta.php', 'pochta');
        $this->loadTranslationsFrom(__DIR__ . '/lang/en/pochta.php', 'pochta');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app['pochta'] = $this->app->share(function ($app) {
            return new Tracking();
        });
    }
}