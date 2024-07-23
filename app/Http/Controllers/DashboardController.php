<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function usersMonthlyChart(Request $request)
    {
        $year = $request->input('year');

        $query = User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month')
        )
            ->groupBy('year', 'month');

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        $usersMonthly = $query->get();

        return $usersMonthly;
    }


    public function dashboardItems(Request $request)
    {
        $users = $this->users();
        $usersMonthlyChart = $this->usersMonthlyChart($request);

        $data = [
            "users" => $users,
            "usersChart" => $usersMonthlyChart,
        ];

        return response()->json($data, 200);
    }
}
