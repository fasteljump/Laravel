@extends('layouts.app')

@section('title', 'Détail d’un projet — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Détail projet</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'projects.show'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <nav class="breadcrumb" aria-label="Fil d'Ariane">
      <a class="back-link" href="{{ route('projects.index') }}">← Retour à la liste</a>
      <span class="sep">/</span>
      <span class="breadcrumb-current">Projet “{{ $project->name }}”</span>
    </nav>

    <section class="card ticket-head" aria-label="En-tête projet">
      <div>
        <h2 class="card-title">{{ $project->name }}</h2>
        <p class="card-muted">Client : {{ $project->client->name }} • Statut : {{ $project->status_label }}</p>
      </div>

      <div class="form_actions">
        <a class="btn_link" href="{{ route('projects.create') }}">Créer un projet</a>
        <a class="btn_primary" href="{{ route('projects.edit', $project) }}">Éditer</a>
      </div>
    </section>

    <section class="projects-grid" aria-label="Résumé projet">
      <article class="card project-card">
        <h3 class="card-title">Contrat</h3>
        <p class="muted">Type : {{ $project->contract_type === 'regie' ? 'Régie' : 'Forfait heures' }}</p>
        <div class="project-meta">
          <div>Heures incluses : {{ number_format($project->included_hours, 1, ',', ' ') }}h</div>
          <div>Consommé : {{ number_format($project->included_spent_hours, 1, ',', ' ') }}h</div>
          <div>Restant : {{ number_format($project->remaining_hours, 1, ',', ' ') }}h</div>
          <div>Heures à facturer : {{ number_format($project->hours_to_bill, 1, ',', ' ') }}h</div>
          <div class="progress" aria-label="Progression">
            <span style="width: {{ $project->consumption_progress }}%;"></span>
          </div>
        </div>
      </article>

      <article class="card project-card">
        <h3 class="card-title">Collaborateurs</h3>
        <ul class="muted" style="margin: 10px 0 0; padding-left: 18px;">
          @forelse($project->collaborator_names as $collaborator)
            <li>{{ $collaborator }}</li>
          @empty
            <li>Aucun collaborateur assigné pour le moment.</li>
          @endforelse
        </ul>
      </article>

      <article class="card project-card">
        <h3 class="card-title">Règles facturation</h3>
        <p class="muted" style="margin-top: 10px;">
          OK, la, quand les heures incluses sont épuisées :
          {{ $project->auto_billable_when_exceeded ? 'les dépassements deviennent automatiquement facturables.' : 'les dépassements restent à arbitrer manuellement.' }}
        </p>
      </article>
    </section>

    <section class="card" aria-label="Tickets du projet">
      <h3 class="card-title">Tickets liés au projet</h3>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th scope="col">Ticket</th>
              <th scope="col">Sujet</th>
              <th scope="col">Assigné</th>
              <th scope="col">Statut</th>
              <th scope="col">Type</th>
              <th scope="col">Temps passé</th>
            </tr>
          </thead>
          <tbody>
            @forelse($project->tickets as $ticket)
              <tr>
                <td class="mono">{{ $ticket->code }}</td>
                <td><a class="ticket-link" href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></td>
                <td>{{ $ticket->assignee?->name ?? 'Non assigné' }}</td>
                <td><span class="status status-{{ $ticket->status_class }}">{{ $ticket->status_label }}</span></td>
                <td>{{ $ticket->billing_label }}</td>
                <td>{{ number_format($ticket->spent_hours, 1, ',', ' ') }}h</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="muted">Aucun ticket lié à ce projet pour le moment.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="form_actions">
        <a class="btn_primary" href="{{ route('tickets.create') }}">Créer un ticket</a>
        <a class="btn_link" href="{{ route('tickets.index', ['project' => $project->id]) }}">Voir tous les tickets</a>
      </div>
    </section>
  </main>
</div>
@endsection
