<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\CustomDatabaseChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(255);
        App::setLocale(Session::get('locale', config('app.locale')));
        
        NotificationFacade::extend('database', function ($app) {
            return new CustomDatabaseChannel();
        });

        // Checks multiple direct permissions
        Blade::directive('directCanany', function ($permissions) {
            return "<?php if(auth()->check() && collect($permissions)->contains(function(\$perm) { return auth()->user()->getDirectPermissions()->pluck('name')->contains(\$perm); })): ?>";
        });

        Blade::directive('enddirectCanany', function () {
            return '<?php endif; ?>';
        });

        // Single direct permission
        Blade::directive('directCan', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->getDirectPermissions()->pluck('name')->contains($permission)): ?>";
        });

        Blade::directive('enddirectCan', function () {
            return '<?php endif; ?>';
        });
    }
}
