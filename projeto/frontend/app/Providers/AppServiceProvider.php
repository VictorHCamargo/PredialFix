<?php

namespace App\Providers;

use App\Models\Chamado;
use App\Policies\ChamadoPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        // Registrar as policies
        $this->registerPolicies();
    }

    /**
     * Registrar as policies da aplicação
     */
    protected function registerPolicies(): void
    {
        \Illuminate\Support\Facades\Gate::policy(Chamado::class, ChamadoPolicy::class);
    }
}
