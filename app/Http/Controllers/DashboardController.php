<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CrudService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $crudService;

    public function __construct()
    {
        $this->crudService = new CrudService(new User());
    }

    public function users()
    {
        $users = $this->crudService->index();
        return $users;
    }

    public function dashboardItems()
    {
        $users = $this->users();

        $data = [
            "users" => $users,
        ];

        return response()->json($data, 200);
    }
}
