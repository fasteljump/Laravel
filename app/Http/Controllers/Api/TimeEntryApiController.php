<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeEntryApiController extends Controller
{
    public function store(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'hours' => ['required', 'numeric', 'min:0.25'],
            'work_date' => ['required', 'date'],
            'comment' => ['nullable', 'string'],
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['hours'] = (float) $validated['hours'];
        $validated['user_id'] = $validated['user_id'] ?? $ticket->assignee_id;

        $entry = TimeEntry::create($validated);
        $entry->load('user');
        $ticket->load(['project', 'timeEntries']);

        return response()->json([
            'message' => 'Temps passé ajouté avec succès.',
            'data' => [
                'id' => $entry->id,
                'work_date' => $entry->work_date?->toDateString(),
                'work_date_label' => $entry->work_date?->format('d/m/Y'),
                'hours' => $entry->hours,
                'hours_label' => number_format($entry->hours, 1, ',', ' ') . 'h',
                'user_name' => $entry->user?->name ?? 'Non renseigné',
                'comment' => $entry->comment ?: '—',
                'delete_url' => route('tickets.time_entries.destroy', [$ticket, $entry]),
            ],
            'ticket' => [
                'spent_hours' => $ticket->spent_hours,
                'spent_hours_label' => number_format($ticket->spent_hours, 1, ',', ' ') . 'h',
                'remaining_hours' => $ticket->remaining_hours,
                'remaining_hours_label' => number_format($ticket->remaining_hours, 1, ',', ' ') . 'h',
                'billable_hours' => $ticket->billable_hours,
                'billable_hours_label' => number_format($ticket->billable_hours, 1, ',', ' ') . 'h',
            ],
            'project' => $ticket->project ? [
                'remaining_hours' => $ticket->project->remaining_hours,
                'remaining_hours_label' => number_format($ticket->project->remaining_hours, 1, ',', ' ') . 'h',
                'hours_to_bill' => $ticket->project->hours_to_bill,
                'hours_to_bill_label' => number_format($ticket->project->hours_to_bill, 1, ',', ' ') . 'h',
            ] : null,
        ], 201);
    }
}
