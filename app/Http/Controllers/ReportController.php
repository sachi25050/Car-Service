<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\JobCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth();
        $dateTo = $request->date_to ?? now()->endOfMonth();

        $revenue = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $byMethod = Payment::whereBetween('payment_date', [$dateFrom, $dateTo])
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('reports.revenue', compact('revenue', 'totalRevenue', 'byMethod', 'dateFrom', 'dateTo'));
    }

    public function appointments(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth();
        $dateTo = $request->date_to ?? now()->endOfMonth();

        $appointments = Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $totalAppointments = Appointment::whereBetween('appointment_date', [$dateFrom, $dateTo])->count();

        return view('reports.appointments', compact('appointments', 'totalAppointments', 'dateFrom', 'dateTo'));
    }

    public function services(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth();
        $dateTo = $request->date_to ?? now()->endOfMonth();

        $services = JobCardService::whereHas('jobCard', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            })
            ->select('service_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_revenue'))
            ->with('service')
            ->groupBy('service_id')
            ->orderByDesc('total_quantity')
            ->get();

        return view('reports.services', compact('services', 'dateFrom', 'dateTo'));
    }
}
