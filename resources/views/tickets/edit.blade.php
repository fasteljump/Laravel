@extends('layouts.app')

@section('title', 'Éditer un ticket — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Éditer un ticket</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'tickets.index'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <nav class="breadcrumb" aria-label="Fil d'Ariane">
      <a class="back-link" href="{{ route('tickets.show', $ticket) }}">← Retour au ticket</a>
      <span class="sep">/</span>
      <span class="breadcrumb-current">Édition</span>
    </nav>

    <section class="card">
      <h2 class="card-title">Modifier “{{ $ticket->title }}”</h2>
      <div class="form-message is-visible" aria-live="polite">Le temps passé se gère dans le détail du ticket.</div>

      <form action="{{ route('tickets.update', $ticket) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form_grid">
          <div class="field">
            <label for="project">Projet</label>
            <select id="project" name="project_id" required>
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
            <input id="title" name="title" type="text" required value="{{ old('title', $ticket->title) }}">
            @error('title') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field form_grid_full">
            <label for="desc">Description</label>
            <textarea id="desc" name="description">{{ old('description', $ticket->description) }}</textarea>
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
            <input id="estimate" name="estimated_hours" type="number" min="0" step="0.5" value="{{ old('estimated_hours', $ticket->estimated_hours) }}">
            @error('estimated_hours') <p class="field-error">{{ $message }}</p> @enderror
          </div>

          <div class="field">
            <label>Temps passé actuel</label>
            <input type="text" value="{{ number_format($ticket->spent_hours, 1, ',', ' ') }} h" disabled>
            <p class="card-muted">Ajoute ou retire du temps depuis la page détail.</p>
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
          <button class="btn_primary" type="submit">Enregistrer</button>
          <a class="btn_link" href="{{ route('tickets.show', $ticket) }}">Annuler</a>
        </div>
      </form>
    </section>
  </main>
</div>
@endsection
