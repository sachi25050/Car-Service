<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JobCard;
use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_vehicles' => Vehicle::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'in_progress_jobs' => JobCard::where('status', 'in_progress')->count(),
            'pending_invoices' => Invoice::whereIn('status', ['draft', 'sent', 'partial'])->count(),
            'today_revenue' => Payment::whereDate('payment_date', today())->sum('amount'),
            'month_revenue' => Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
        ];

        $recent_appointments = Appointment::with(['customer', 'vehicle', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();

        $recent_job_cards = JobCard::with(['customer', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $upcoming_appointments = Appointment::with(['customer', 'vehicle', 'service'])
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recent_appointments', 'recent_job_cards', 'upcoming_appointments'));
    }
}
