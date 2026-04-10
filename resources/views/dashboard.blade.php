@extends('layouts.app')

@section('title', 'Tableau de bord — Gestion Tickets')

@section('content')
<header>
  <nav class="menu" aria-label="Barre du haut">
    <img class="image2" src="{{ asset('images/image1.png') }}" alt="Logo de l'application">
    <h1 class="tableau_de_bord">Tableau de bord</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </nav>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'dashboard'])

  <main class="tableau_main" id="contenu">
    <section class="card">
      <h2 class="card-title">Vue d’ensemble</h2>
    </section>

    <section class="ticket_ouvert" aria-label="Statistiques tickets">
      <article class="kpi card">
        <h3>Tickets ouverts</h3>
        <p class="kpi-number">{{ $stats['open'] }}</p>
        <p class="card-muted">à traiter</p>
      </article>

      <article class="kpi card">
        <h3>En cours</h3>
        <p class="kpi-number">{{ $stats['progress'] }}</p>
        <p class="card-muted">assignés</p>
      </article>

      <article class="kpi card">
        <h3>Résolus</h3>
        <p class="kpi-number">{{ $stats['resolved'] }}</p>
        <p class="card-muted">prêts à être archivés</p>
      </article>

      <article class="kpi card">
        <h3>Heures à facturer</h3>
        <p class="kpi-number">{{ number_format($stats['billable_hours'], 1, ',', ' ') }}h</p>
        <p class="card-muted">sur tous les projets</p>
      </article>
    </section>

    @if($featuredProject)
      <section class="card" aria-label="Projet mis en avant">
        <div class="page-head-row">
          <h2 class="card-title">Projet suivi : {{ $featuredProject->name }}</h2>
          <a class="btn_primary" href="{{ route('projects.show', $featuredProject) }}">Voir le projet</a>
        </div>
        <div class="project-meta" style="margin-top: 12px;">
          <div>Client : {{ $featuredProject->client->name }}</div>
          <div>Heures incluses : {{ number_format($featuredProject->included_hours, 1, ',', ' ') }}h</div>
          <div>Heures restantes : {{ number_format($featuredProject->remaining_hours, 1, ',', ' ') }}h</div>
          <div>Heures à facturer : {{ number_format($featuredProject->hours_to_bill, 1, ',', ' ') }}h</div>
          <div class="progress" aria-label="Progression heures consommées">
            <span style="width: {{ $featuredProject->consumption_progress }}%;"></span>
          </div>
        </div>
      </section>
    @endif

    <section class="card" aria-label="Tickets récents">
      <h2 class="card-title">Tickets récents</h2>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th scope="col">Ticket</th>
              <th scope="col">Sujet</th>
              <th scope="col">Projet</th>
              <th scope="col">Priorité</th>
              <th scope="col">Statut</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentTickets as $ticket)
              <tr>
                <td class="mono">{{ $ticket->code }}</td>
                <td><a class="ticket-link" href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></td>
                <td>{{ $ticket->project?->name }}</td>
                <td><span class="prio prio-{{ $ticket->priority_class }}">{{ $ticket->priority_label }}</span></td>
                <td><span class="status status-{{ $ticket->status_class }}">{{ $ticket->status_label }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="muted">Aucun ticket en base pour le moment.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
@endsection
