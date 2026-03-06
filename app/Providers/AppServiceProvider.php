<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        // Memberikan akses jika pemilik asli ATAU jika tergabung dalam tim Workspace yang sama
        Gate::define('view', function (User $user, Link $link) {
            if ($user->id === $link->user_id) return true;
            if ($link->workspace_id && $user->workspaces()->where('workspace_id', $link->workspace_id)->exists()) return true;
            return false;
        });

        Gate::define('update', function (User $user, Link $link) {
            if ($user->id === $link->user_id) return true;
            if ($link->workspace_id && $user->workspaces()->where('workspace_id', $link->workspace_id)->exists()) return true;
            return false;
        });

        Gate::define('delete', function (User $user, Link $link) {
            if ($user->id === $link->user_id) return true;
            if ($link->workspace_id && $user->workspaces()->where('workspace_id', $link->workspace_id)->exists()) return true;
            return false;
        });
    }
}
