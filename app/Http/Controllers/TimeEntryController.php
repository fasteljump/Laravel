<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    public function store(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'hours' => ['required', 'numeric', 'min:0.25'],
            'work_date' => ['required', 'date'],
            'comment' => ['nullable', 'string'],
        ]);

        $validated['ticket_id'] = $ticket->id;
        TimeEntry::create($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Temps passé ajouté avec succès.');
    }

    public function destroy(Ticket $ticket, TimeEntry $timeEntry): RedirectResponse
    {
        if ($timeEntry->ticket_id !== $ticket->id) {
            abort(404);
        }

        $timeEntry->delete();

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Entrée de temps supprimée avec succès.');
    }
}
