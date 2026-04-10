<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::with(['project.client', 'assignee', 'timeEntries']);

        if ($request->filled('project')) {
            $query->where('project_id', (int) $request->input('project'));
        }

        if ($request->filled('statut') && in_array($request->input('statut'), ['open', 'progress', 'resolved', 'closed'], true)) {
            $query->where('status', $request->input('statut'));
        }

        if ($request->filled('priority') && in_array($request->input('priority'), ['high', 'medium', 'low'], true)) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('code', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhereHas('project', fn ($project) => $project->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('assignee', fn ($user) => $user->where('name', 'like', '%' . $search . '%'));
            });
        }

        $sort = $request->input('tri', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('updated_at');
        } else {
            $query->orderByDesc('updated_at');
        }

        $tickets = $query->get();
        $projects = Project::orderBy('name')->get();

        return view('tickets.index', [
            'tickets' => $tickets,
            'projects' => $projects,
            'filters' => [
                'project' => $request->input('project', ''),
                'statut' => $request->input('statut', 'all'),
                'tri' => $sort,
                'priority' => $request->input('priority', 'all'),
                'q' => $request->input('q', ''),
            ],
        ]);
    }

    public function create(): View
    {
        $ticket = new Ticket([
            'status' => 'open',
            'priority' => 'medium',
            'billing_type' => Ticket::BILLING_INCLUDED,
        ]);

        return view('tickets.create', $this->formData($ticket));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request, true);
        $initialSpentHours = (float) ($validated['initial_spent_hours'] ?? 0);
        unset($validated['initial_spent_hours']);

        $ticket = Ticket::create($validated);

        if ($initialSpentHours > 0) {
            TimeEntry::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->assignee_id,
                'hours' => $initialSpentHours,
                'work_date' => now()->toDateString(),
                'comment' => 'Temps initial saisi à la création du ticket.',
            ]);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès.');
    }

    public function show(?Ticket $ticket = null): View
    {
        $ticket = $this->resolveTicket($ticket);
        $ticket->load(['project.client', 'assignee', 'timeEntries.user']);
        $users = User::orderBy('name')->get();

        return view('tickets.show', compact('ticket', 'users'));
    }

    public function edit(?Ticket $ticket = null): View
    {
        $ticket = $this->resolveTicket($ticket);

        return view('tickets.edit', $this->formData($ticket));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $this->validatedData($request, false);
        $ticket->update($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket mis à jour avec succès.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket supprimé avec succès.');
    }

    private function resolveTicket(?Ticket $ticket = null): Ticket
    {
        return $ticket ?? Ticket::query()->with(['project.client', 'assignee', 'timeEntries.user'])->latest()->firstOrFail();
    }

    private function formData(Ticket $ticket): array
    {
        return [
            'ticket' => $ticket,
            'projects' => Project::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'statuses' => Ticket::STATUS_LABELS,
            'priorities' => Ticket::PRIORITY_LABELS,
        ];
    }

    private function validatedData(Request $request, bool $withInitialSpent): array
    {
        $rules = [
            'project_id' => ['required', 'exists:projects,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['nullable', 'string', 'min:10'],
            'status' => ['required', 'in:open,progress,resolved,closed'],
            'priority' => ['required', 'in:high,medium,low'],
            'billing_type' => ['required', 'in:included,billable'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($withInitialSpent) {
            $rules['initial_spent_hours'] = ['nullable', 'numeric', 'min:0'];
        }

        $validated = $request->validate($rules);
        $validated['estimated_hours'] = isset($validated['estimated_hours']) ? (float) $validated['estimated_hours'] : null;

        return $validated;
    }
}
