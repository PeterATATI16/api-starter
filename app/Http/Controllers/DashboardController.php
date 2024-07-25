<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
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

    public function usersCurrentDay()
    {
        $today = now()->toDateString();

        $usersCurrentDay = User::whereDate('created_at', $today)
            ->get();

        return $usersCurrentDay;
    }


    public function usersCurrentMonth()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $usersCurrentMonth = User::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();

        return $usersCurrentMonth;
    }


    public function usersCurrentYear()
    {
        $currentYear = now()->year;

        $usersCurrentYear = User::whereYear('created_at', $currentYear)
            ->get();

        return $usersCurrentYear;
    }

    public function usersMonthlyChart()
    {
        $currentYear = now()->year;

        $usersMonthly = User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = collect(range(1, 12))->map(function ($month) use ($usersMonthly) {
            $data = $usersMonthly->firstWhere('month', $month);
            return [
                'month' => $month,
                'count' => $data ? $data->count : 0,
            ];
        });

        return $months;
    }

    public function dashboardItems()
    {
        $users = $this->users();
        $usersMonthlyChart = $this->usersMonthlyChart();
        $currentMonthCount = $this->usersCurrentMonth();
        $currentDayCount = $this->usersCurrentDay();
        $currentYearCount = $this->usersCurrentYear();

        $data = [
            "users" => $users,
            "usersChart" => $usersMonthlyChart,
            "currentMonth" => $currentMonthCount,
            "currentDay" => $currentDayCount,
            "currentYear" => $currentYearCount,
        ];

        return response()->json($data, 200);
    }

}
