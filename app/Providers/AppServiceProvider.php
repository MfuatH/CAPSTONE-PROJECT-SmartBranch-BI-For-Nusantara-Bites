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

        View::composer('components.navbar', function ($view) {
            $branches = Store::orderBy('location')->get();
            $selectedBranchId = request()->query('branch_id');
            $selectedBranch = $selectedBranchId ? $branches->firstWhere('id', $selectedBranchId) : null;
            $selectedBranchName = $selectedBranch ? $selectedBranch->location : 'Semua Cabang';

            $view->with([
                'branches' => $branches,
                'selectedBranchName' => $selectedBranchName,
                'selectedBranchId' => $selectedBranchId,
            ]);
        });
    }
}
