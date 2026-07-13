<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share notifications data with the admin layout
        if (\Illuminate\Support\Facades\Schema::hasTable('notifikasi')) {
            \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
                $unreadNotifications = \App\Models\Notifikasi::where('status_baca', false)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                $unreadCount = \App\Models\Notifikasi::where('status_baca', false)->count();
                
                $view->with([
                    'navbarNotifications' => $unreadNotifications,
                    'navbarNotificationsCount' => $unreadCount
                ]);
            });
        }
    }
}
