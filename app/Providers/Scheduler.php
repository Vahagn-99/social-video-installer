<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class Scheduler extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Schedule::command('telescope:prune')->daily();
    }
}
