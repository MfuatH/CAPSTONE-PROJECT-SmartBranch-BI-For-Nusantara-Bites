<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Store;

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
        Paginator::useTailwind();

        View::composer('*', function ($view) {
            $view->with('branches', Store::orderBy('location', 'asc')->get());
            
            if (session()->has('branch_id')) {
                $branch = Store::find(session('branch_id'));
                $view->with('selectedBranchName', $branch ? 'Cabang ' . $branch->location : 'Semua Cabang');
            } else {
                $view->with('selectedBranchName', 'Semua Cabang');
            }
        });
    }
}
