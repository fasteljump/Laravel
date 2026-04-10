<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with(['client', 'tickets.timeEntries', 'tickets.assignee'])
            ->orderBy('name')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $project = new Project([
            'status' => 'actif',
            'contract_type' => 'forfait_heures',
            'included_hours' => 0,
            'hourly_rate' => 0,
            'auto_billable_when_exceeded' => true,
        ]);

        $clients = Client::orderBy('name')->get();

        return view('projects.create', compact('project', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $project = Project::create($this->validatedData($request));

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Projet créé avec succès.');
    }

    public function show(?Project $project = null): View
    {
        $project = $this->resolveProject($project);
        $project->load(['client', 'tickets.assignee', 'tickets.timeEntries']);

        return view('projects.show', compact('project'));
    }

    public function edit(?Project $project = null): View
    {
        $project = $this->resolveProject($project);
        $clients = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validatedData($request));

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }

    private function resolveProject(?Project $project = null): Project
    {
        return $project ?? Project::query()->with(['client', 'tickets.assignee', 'tickets.timeEntries'])->orderBy('name')->firstOrFail();
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:actif,pause,termine,archive'],
            'contract_type' => ['required', 'in:forfait_heures,regie'],
            'included_hours' => ['nullable', 'numeric', 'min:0'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'auto_billable_when_exceeded' => ['nullable'],
        ]);

        $validated['included_hours'] = (float) ($validated['included_hours'] ?? 0);
        $validated['hourly_rate'] = (float) ($validated['hourly_rate'] ?? 0);
        $validated['auto_billable_when_exceeded'] = $request->boolean('auto_billable_when_exceeded');

        return $validated;
    }
}
