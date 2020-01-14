<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class ViewServiceProvider extends ServiceProvider
{
    private $view;
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
        \Blade::directive('emailclean', function ($html) {
            return "<?php echo email_clean($html); ?>";
        });

        View::creator('admin.layouts.*', 'App\Http\View\Creators\AdminCreator');
    }
}
