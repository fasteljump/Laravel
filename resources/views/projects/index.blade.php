@extends('layouts.app')

@section('title', 'Liste des projets — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Projets</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'projects.index'])

  <main class="page_main main_tickets">
    <section class="card">
      <div class="page-head-row">
        <h2 class="card-title">Liste des projets</h2>
        <a class="btn_primary" href="{{ route('projects.create') }}">+ Nouveau projet</a>
      </div>
    </section>

    <section class="projects-grid" aria-label="Liste des projets">
      @forelse($projects as $project)
        <article class="card project-card">
          <h3 class="card-title">{{ $project->name }}</h3>
          <p class="muted">Client : {{ $project->client->name }}</p>

          <div class="project-meta">
            <div>
              Collaborateurs :
              @if($project->collaborator_names->isNotEmpty())
                {{ $project->collaborator_names->join(', ') }}
              @else
                Aucun pour le moment
              @endif
            </div>
            <div>Contrat : {{ number_format($project->included_hours, 1, ',', ' ') }}h • Consommé : {{ number_format($project->included_spent_hours, 1, ',', ' ') }}h • Restant : {{ number_format($project->remaining_hours, 1, ',', ' ') }}h</div>
            <div>Heures à facturer : {{ number_format($project->hours_to_bill, 1, ',', ' ') }}h</div>

            <div class="progress" aria-label="Progression heures consommées">
              <span style="width: {{ $project->consumption_progress }}%;"></span>
            </div>
          </div>

          <div class="form_actions">
            <a class="btn_link" href="{{ route('tickets.index', ['project' => $project->id]) }}">Voir les tickets</a>
            <a class="btn_primary" href="{{ route('projects.show', $project) }}">Détails</a>
          </div>
        </article>
      @empty
        <article class="card project-card">
          <h3 class="card-title">Aucun projet</h3>
          <p class="muted">Commence par créer ton premier projet.</p>
          <div class="form_actions">
            <a class="btn_primary" href="{{ route('projects.create') }}">Créer un projet</a>
          </div>
        </article>
      @endforelse
    </section>
  </main>
</div>
@endsection
