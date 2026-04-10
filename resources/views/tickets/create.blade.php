@extends('layouts.app')

@section('title', 'Créer un ticket — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Créer un ticket</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'tickets.create'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <section class="card">
      <h2 class="card-title">Nouveau ticket</h2>


      <form action="{{ route('tickets.store') }}" method="post">
        @csrf
        <div class="form_grid">
          <div class="field">
            <label for="project">Projet</label>
            <select id="project" name="project_id" required>
              <option value="" disabled @selected(old('project_id') === null)>Choisir un projet…</option>
              @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id', $ticket->project_id) == $project->id)>{{ $project->name }}</option>
              @endforeach
            </select>
            @error('project_id') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field">
            <label for="priority">Priorité</label>
            <select id="priority" name="priority">
              @foreach($priorities as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $ticket->priority) === $value)>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="field form_grid_full">
            <label for="title">Titre</label>
            <input id="title" name="title" type="text" placeholder="Ex : Menu qui déborde sur mobile" required value="{{ old('title', $ticket->title) }}">
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field form_grid_full">
            <label for="desc">Description</label>
            <textarea id="desc" name="description" placeholder="Contexte, étapes, attendu / obtenu…">{{ old('description', $ticket->description) }}</textarea>
            @error('description') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field">
            <label for="status">Statut</label>
            <select id="status" name="status">
              @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $ticket->status) === $value)>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="field">
            <label for="assignee">Assigné à</label>
            <select id="assignee" name="assignee_id">
              <option value="">Non assigné</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('assignee_id', $ticket->assignee_id) == $user->id)>{{ $user->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="field">
            <label for="estimate">Temps estimé (h)</label>
            <input id="estimate" name="estimated_hours" type="number" min="0" step="0.5" placeholder="Ex : 2" value="{{ old('estimated_hours', $ticket->estimated_hours) }}">
            @error('estimated_hours') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field">
            <label for="spent">Temps passé initial (h)</label>
            <input id="spent" name="initial_spent_hours" type="number" min="0" step="0.5" placeholder="Ex : 0" value="{{ old('initial_spent_hours') }}">
            @error('initial_spent_hours') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field form_grid_full">
            <label for="billing">Type de ticket</label>
            <select id="billing" name="billing_type">
              <option value="included" @selected(old('billing_type', $ticket->billing_type) === 'included')>Inclus</option>
              <option value="billable" @selected(old('billing_type', $ticket->billing_type) === 'billable')>Facturable</option>
            </select>
          </div>
        </div>

        <div class="form_actions">
          <button class="btn_primary" type="submit">Créer</button>
          <a class="btn_link" href="{{ route('tickets.index') }}">Annuler</a>
        </div>
      </form>
    </section>
  </main>
</div>
@endsection
