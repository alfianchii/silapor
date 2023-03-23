<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\{DashboardService, ChartService, GreetingService};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService, $chartService;

    // Constructor to add services
    public function __construct(DashboardService $dashboardService, ChartService $chartService)
    {
        $this->dashboardService = $dashboardService;
        $this->chartService = $chartService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Timezone
        $currentTime = Carbon::now();
        $hour = $currentTime->hour;

        if ($hour >= 5 && $hour <= 13) {
            $greeting = 'Selamat pagi';
        } elseif ($hour >= 14 && $hour <= 17) {
            $greeting = 'Selamat sore';
        } else {
            $greeting = 'Selamat malam';
        }

        // Complaints
        $complaints = Complaint::orderByDesc("created_at")->get() ?? [];

        // Officers
        $officers = Officer::all();

        // Students
        $students = Student::all();

        // Responses
        $responses = Response::orderByDesc("created_at")->get() ?? [];

        return view("dashboard.index", [
            "title" => "Dashboard",
            "greeting" => $greeting,
            "complaints" => $complaints,
            "officers" => $officers,
            "students" => $students,
            "responses" => $responses,
        ]);
    }

    public function chartData()
    {
        // Return the chart data (JSON response)
        return $this->chartService->responses(auth()->user());
    }
}
