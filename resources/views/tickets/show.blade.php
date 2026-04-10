@extends('layouts.app')

@section('title', 'Détail d’un ticket — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Détail du ticket</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'tickets.index'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <nav class="breadcrumb" aria-label="Fil d'Ariane">
      <a class="back-link" href="{{ route('tickets.index') }}">← Retour à la liste</a>
      <span class="sep">/</span>
      <span class="breadcrumb-current">Ticket {{ $ticket->code }}</span>
    </nav>

    <section class="card ticket-head" aria-label="En-tête du ticket">
      <div>
        <p class="mono" style="margin:0;">{{ $ticket->code }}</p>
        <h2 class="ticket-title">{{ $ticket->title }}</h2>

        <div class="ticket-badges" aria-label="Statut et priorité">
          <span class="status status-{{ $ticket->status_class }}">{{ $ticket->status_label }}</span>
          <span class="prio prio-{{ $ticket->priority_class }}">{{ $ticket->priority_label }}</span>
        </div>
      </div>

      <div class="form_actions">
        <a class="btn_link" href="{{ route('tickets.edit', $ticket) }}">Éditer</a>
        <form action="{{ route('tickets.destroy', $ticket) }}" method="post" onsubmit="return confirm('Supprimer ce ticket ?');">
          @csrf
          @method('DELETE')
          <button class="btn_primary" type="submit">Supprimer</button>
        </form>
      </div>
    </section>

    <section class="ticket-detail-layout" aria-label="Contenu du ticket">
      <div class="ticket-main">
        <section class="card">
          <h3 class="card-title">Description</h3>

          <p style="margin-top:12px;">
            {{ $ticket->description ?: 'Aucune description renseignée.' }}
          </p>
        </section>

        <section class="card">
          <div class="page-head-row">
            <h3 class="card-title">Suivi du temps</h3>
            <span id="ticketSpentBadge" class="card-muted">{{ number_format($ticket->spent_hours, 1, ',', ' ') }}h saisies</span>
          </div>

          <div class="table-wrap" style="margin-top:12px;">
            <table>
              <thead>
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Durée</th>
                  <th scope="col">Collaborateur</th>
                  <th scope="col">Commentaire</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody id="timeEntriesTableBody">
                @forelse($ticket->timeEntries as $entry)
                  <tr>
                    <td>{{ $entry->work_date?->format('d/m/Y') }}</td>
                    <td>{{ number_format($entry->hours, 1, ',', ' ') }}h</td>
                    <td>{{ $entry->user?->name ?? 'Non renseigné' }}</td>
                    <td class="muted">{{ $entry->comment ?: '—' }}</td>
                    <td>
                      <form action="{{ route('tickets.time_entries.destroy', [$ticket, $entry]) }}" method="post" onsubmit="return confirm('Supprimer cette entrée de temps ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn_link" type="submit">Supprimer</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr id="timeEntriesEmptyRow">
                    <td colspan="5" class="muted">Aucune entrée de temps pour le moment.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>

        <section class="card">
          <h3 class="card-title">Ajouter du temps passé</h3>
          <p class="card-muted">Chaque ajout met à jour automatiquement les heures restantes du projet et les heures à facturer.</p>

          <div id="timeEntryApiPanel" data-api-url="{{ route('api.time_entries.store', $ticket) }}">
            <div id="timeEntryApiMessage" class="form-message" aria-live="polite" style="margin-top: 12px;"></div>

          <form id="timeEntryApiForm" class="comment-form" action="{{ route('api.time_entries.store', $ticket) }}" method="post" style="display: grid; gap: 12px;">
            <div class="form_grid">
              <div class="field">
                <label for="work_date">Date</label>
                <input id="work_date" name="work_date" type="date" value="{{ old('work_date', now()->toDateString()) }}">
              </div>

              <div class="field">
                <label for="hours">Durée (h)</label>
                <input id="hours" name="hours" type="number" min="0.25" step="0.25" value="{{ old('hours') }}" placeholder="Ex : 1.5">
              </div>

              <div class="field">
                <label for="user_id">Collaborateur</label>
                <select id="user_id" name="user_id">
                  <option value="">Choisir</option>
                  @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $ticket->assignee_id) == $user->id)>{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="field form_grid_full">
                <label for="comment">Commentaire</label>
                <textarea id="comment" name="comment" placeholder="Ce qui a été fait...">{{ old('comment') }}</textarea>
              </div>
            </div>
            <button class="btn_primary" type="submit">Ajouter le temps</button>
          </form>
          </div>
        </section>
      </div>

      <aside class="ticket-side">
        <section class="card">
          <h3 class="card-title">Informations</h3>

          <dl class="meta">
            <div class="meta-row">
              <dt>Projet</dt>
              <dd><a class="ticket-link" href="{{ route('projects.show', $ticket->project) }}">{{ $ticket->project?->name }}</a></dd>
            </div>

            <div class="meta-row">
              <dt>Assigné</dt>
              <dd>{{ $ticket->assignee?->name ?? 'Non assigné' }}</dd>
            </div>

            <div class="meta-row">
              <dt>Type</dt>
              <dd>{{ $ticket->billing_label }}</dd>
            </div>

            <div class="meta-row">
              <dt>Validation client</dt>
              <dd>{{ $ticket->client_validation_label }}</dd>
            </div>

            <div class="meta-row">
              <dt>Temps total</dt>
              <dd id="ticketTotalMeta">{{ number_format($ticket->spent_hours, 1, ',', ' ') }}h / estimé {{ number_format((float) ($ticket->estimated_hours ?? 0), 1, ',', ' ') }}h</dd>
            </div>

            <div class="meta-row">
              <dt>Restant sur ticket</dt>
              <dd id="ticketRemainingMeta">{{ number_format($ticket->remaining_hours, 1, ',', ' ') }}h</dd>
            </div>

            <div class="meta-row">
              <dt>Heures à facturer (ticket)</dt>
              <dd id="ticketBillableMeta">{{ number_format($ticket->billable_hours, 1, ',', ' ') }}h</dd>
            </div>

            @if($ticket->project)
              <div class="meta-row">
                <dt>Heures restantes projet</dt>
                <dd id="projectRemainingMeta">{{ number_format($ticket->project->remaining_hours, 1, ',', ' ') }}h</dd>
              </div>

              <div class="meta-row">
                <dt>Heures à facturer projet</dt>
                <dd id="projectBillableMeta">{{ number_format($ticket->project->hours_to_bill, 1, ',', ' ') }}h</dd>
              </div>
            @endif
          </dl>
        </section>

        <section class="card">
          <h3 class="card-title">Validation client</h3>
          @if($ticket->needs_client_validation)
            <p class="card-muted">Un ticket facturable doit être accepté ou refusé par le client avant facturation.</p>
            <div class="meta-row" style="margin-top: 12px; display: block;">
              <dt style="margin-bottom: 8px;">État actuel</dt>
              <dd><span class="status status-{{ $ticket->client_validation_class }}">{{ $ticket->client_validation_label }}</span></dd>
            </div>

            @if($ticket->client_validation_comment)
              <p class="muted" style="margin-top: 12px;">Dernier commentaire client : {{ $ticket->client_validation_comment }}</p>
            @endif

            <div class="form_actions" style="margin-top: 16px; align-items: flex-start;">
              <form action="{{ route('tickets.validation.accept', $ticket) }}" method="post">
                @csrf
                <button class="btn_primary" type="submit">Valider la facturation</button>
              </form>

              <form action="{{ route('tickets.validation.refuse', $ticket) }}" method="post" style="display: grid; gap: 8px; min-width: 240px;">
                @csrf
                <textarea name="validation_comment" placeholder="Commentaire de refus (optionnel)">{{ old('validation_comment') }}</textarea>
                <button class="btn_link" type="submit">Refuser (avec commentaire)</button>
              </form>
            </div>
          @else
            <p class="card-muted">Ce ticket est inclus dans le contrat : aucune validation client n'est nécessaire.</p>
          @endif
        </section>

        <section class="card">
          <h3 class="card-title">Actions rapides</h3>
          <div class="form_actions">
            <a class="btn_link" href="{{ route('tickets.index') }}">Retour liste</a>
            <a class="btn_link" href="{{ route('tickets.create') }}">Créer un ticket</a>
          </div>
        </section>
      </aside>
    </section>
  </main>
</div>
@endsection


@php
    $ticketEstimatedHoursLabel = number_format((float) ($ticket->estimated_hours ?? 0), 1, ',', ' ') . 'h';
@endphp

@push('scripts')
<script>
window.csrfToken = @json(csrf_token());
window.ticketEstimatedHoursLabel = @json($ticketEstimatedHoursLabel);
</script>
<script src="{{ asset('js/time-entries-api.js') }}" defer></script>
@endpush
