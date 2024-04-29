<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->registerPolicies();
        // if (!$this->app->routesAreCached()) {
        //     Passport::routes();
        // }
        //Passport::routes(null, ['prefix' => 'api/oauth']);
           
        //Passport::tokensExpireIn(Carbon::now()->addMinutes(1));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        // Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::tokensCan([
            'primer-scope' => 'permite ver listado de drogas',
            'segundo-scope' => 'permite ver listado de precursores',
        ]);

        //para que cuando se este registrado como superadministrador permita ingresar a todos los modulos en el sistema
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SuperAdministrador') ? true : null;
        });
    
        
        //
    }
}
