<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if (env('LARAVEL_SAIL')) {
            $app->make('config')->set(
                'database.connections.mysql.database',
                'testing'
            );
        }

        return $app;
    }
}
