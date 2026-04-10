@extends('layouts.app')

@section('title', 'Liste des tickets — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Liste des tickets</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'tickets.index'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <section class="filtre" aria-label="Filtrer des tickets">
      <form class="filtre-bar" action="{{ route('tickets.index') }}" method="get">
        <fieldset class="filtre-left">
          <legend class="sr-only">Filtrer par statut</legend>

          <label class="pill">
            <input type="radio" name="statut" value="all" @checked(($filters['statut'] ?? 'all') === 'all')>
            <span class="pill-inner"><span class="icon">☰</span>All</span>
          </label>

          <label class="pill">
            <input type="radio" name="statut" value="open" @checked(($filters['statut'] ?? 'all') === 'open')>
            <span class="pill-inner"><span class="icon">○</span>Open</span>
          </label>

          <label class="pill">
            <input type="radio" name="statut" value="progress" @checked(($filters['statut'] ?? 'all') === 'progress')>
            <span class="pill-inner"><span class="icon">◷</span>In progress</span>
          </label>

          <label class="pill">
            <input type="radio" name="statut" value="resolved" @checked(($filters['statut'] ?? 'all') === 'resolved')>
            <span class="pill-inner"><span class="icon">✓</span>Resolved</span>
          </label>

          <label class="pill">
            <input type="radio" name="statut" value="closed" @checked(($filters['statut'] ?? 'all') === 'closed')>
            <span class="pill-inner"><span class="icon">⦿</span>Closed</span>
          </label>
        </fieldset>

        <div class="filtre-right">
          <label class="select-pill">
            <span class="icon">⇅</span>
            <select name="tri" aria-label="Tri">
              <option value="latest" @selected(($filters['tri'] ?? 'latest') === 'latest')>Latest first</option>
              <option value="oldest" @selected(($filters['tri'] ?? 'latest') === 'oldest')>Oldest first</option>
            </select>
          </label>

          <label class="select-pill">
            <span class="icon">⚑</span>
            <select name="priority" aria-label="Priorité">
              <option value="all" @selected(($filters['priority'] ?? 'all') === 'all')>Priority</option>
              <option value="high" @selected(($filters['priority'] ?? 'all') === 'high')>High</option>
              <option value="medium" @selected(($filters['priority'] ?? 'all') === 'medium')>Medium</option>
              <option value="low" @selected(($filters['priority'] ?? 'all') === 'low')>Low</option>
            </select>
          </label>

          <label class="select-pill">
            <span class="icon">⌂</span>
            <select name="project" aria-label="Projet">
              <option value="">Tous les projets</option>
              @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected(($filters['project'] ?? '') == $project->id)>{{ $project->name }}</option>
              @endforeach
            </select>
          </label>

          <div class="search">
            <label class="sr-only" for="q">Rechercher</label>
            <input id="q" name="q" type="search" placeholder="Search Tickets" value="{{ $filters['q'] ?? '' }}">
            <button class="search-btn" type="submit" aria-label="Rechercher">⌕</button>
          </div>
        </div>
      </form>
    </section>

    <section class="card" aria-label="Liste des tickets">
      <div class="page-head-row">
        <h2 class="card-title">Tickets ({{ $tickets->count() }})</h2>
        <a class="btn_primary" href="{{ route('tickets.create') }}">+ Nouveau ticket</a>
      </div>

      <div class="table-wrap" style="margin-top: 12px;">
        <table>
          <thead>
            <tr>
              <th scope="col">Ticket</th>
              <th scope="col">Sujet</th>
              <th scope="col">Assigné</th>
              <th scope="col">Statut</th>
              <th scope="col">Priorité</th>
              <th scope="col">Créé</th>
              <th scope="col">Mis à jour</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse($tickets as $ticket)
              <tr>
                <td class="mono">{{ $ticket->code }}</td>
                <td><a class="ticket-link" href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></td>
                <td>{{ $ticket->assignee?->name ?? 'Non assigné' }}</td>
                <td><span class="status status-{{ $ticket->status_class }}">{{ $ticket->status_label }}</span></td>
                <td><span class="prio prio-{{ $ticket->priority_class }}">{{ $ticket->priority_label }}</span></td>
                <td>{{ $ticket->created_at?->format('d/m/Y') }}</td>
                <td>{{ $ticket->updated_at?->format('d/m/Y') }}</td>
                <td>
                  <div class="form_actions" style="gap: 8px; justify-content: flex-start;">
                    <a class="btn_link" href="{{ route('tickets.edit', $ticket) }}">Éditer</a>
                    <form action="{{ route('tickets.destroy', $ticket) }}" method="post" onsubmit="return confirm('Supprimer ce ticket ?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn_link" type="submit">Supprimer</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="muted">Aucun ticket ne correspond à vos filtres.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>    </section>

    

  </main>
</div>
@endsection


