<?php

namespace App\Providers;

use App\Grants\GrantsTrait;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Message;
use App\Policies\CampaignPolicy;
use App\Policies\ContactPolicy;
use App\Policies\MessagePolicy;
use App\Utils\Macros\GeneralMacros;
use App\Utils\Macros\HttpMacro;
use App\Utils\Macros\ResponseMacro;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // // Register Macros
        (new ResponseMacro)->register();
        (new HttpMacro)->register();
        (new GeneralMacros)->register();


        $this->registerPolicies();
        $this->registerRateLimits();
        $this->registerConfigs();
    }

    // Register Rate Limiters
    private function registerRateLimits(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('authentication', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }

    public function registerPolicies()
    {
        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(Message::class, MessagePolicy::class);
    }

    private function registerConfigs(): void
    {
        DB::prohibitDestructiveCommands($this->app->environment('production'));
        Model::shouldBeStrict();
        Model::preventLazyLoading(! $this->app->environment('production'));
        URL::forceHttps($this->app->environment('production'));
    }
}
