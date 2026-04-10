<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $featuredProject = Project::with(['client', 'tickets.timeEntries', 'tickets.assignee'])
            ->orderBy('name')
            ->first();

        $recentTickets = Ticket::with(['project', 'assignee', 'timeEntries'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'open' => Ticket::where('status', 'open')->count(),
            'progress' => Ticket::where('status', 'progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'billable_hours' => round(Project::with(['tickets.timeEntries'])->get()->sum->hours_to_bill, 2),
        ];

        return view('dashboard', compact('featuredProject', 'recentTickets', 'stats'));
    }
}
