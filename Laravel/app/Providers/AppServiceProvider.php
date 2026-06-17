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
            $branches = Store::orderBy('location', 'asc')->get();
            $view->with('branches', $branches);

            $branchId = session('branch_id');
            $selectedBranch = $branchId ? Store::find($branchId) : null;

            if (! $selectedBranch && $branches->isNotEmpty()) {
                $selectedBranch = $branches->first();
                session(['branch_id' => $selectedBranch->id]);
            }

            $view->with('selectedBranchName', $selectedBranch ? 'Cabang ' . $selectedBranch->location : 'Semua Cabang');
        });
    }
}
