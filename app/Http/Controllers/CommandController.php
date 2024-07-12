<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{
    public function migration_with_seed(string $command)
    {
        switch ($command) {
            case 'fresh-seed':
                $cmd = 'migrate:fresh --seed';
                break;
            case 'migrate':
                $cmd = 'migrate';
                break;
            case 'migrate-seed':
                $cmd = 'migrate --seed';
                break;
            case 'seed':
                $cmd = 'db:seed';
                break;
            case 'wipe':
                $cmd = 'migrate:reset';
                break;
            case 'optimize':
                $cmd = 'optimize:clear';
                break;
            case 'storage-link';
                $cmd = 'storage:link';
                break;

            case 'migrate-refresh':
                $cmd = 'migrate:refresh';
                break;
            case 'clear-cache':
                $cmd = 'cache:clear';
                break;
        }


        Artisan::call($cmd);

        return response()->json('Opération réussie');
    }
}
