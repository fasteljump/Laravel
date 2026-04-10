@extends('layouts.app')

@section('title', 'Éditer un projet — Gestion Tickets')

@section('content')
<header>
  <div class="menu_du_haut">
    <h1 class="Tickets">Éditer un projet</h1>
    <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
  </div>
</header>

<div class="layout">
  @include('partials.sidebar', ['active' => 'projects.edit'])

  <main class="page_main main_tickets">
    @include('partials.flash')

    <nav class="breadcrumb" aria-label="Fil d'Ariane">
      <a class="back-link" href="{{ route('projects.show', $project) }}">← Retour au projet</a>
      <span class="sep">/</span>
      <span class="breadcrumb-current">Édition</span>
    </nav>

    <section class="card">
      <h2 class="card-title">Modifier “{{ $project->name }}”</h2>
    </section>

    <section class="card">
      <form action="{{ route('projects.update', $project) }}" method="post">
        @csrf
        @method('PUT')

        <fieldset class="form_block">
          <legend class="form_legend">Informations du projet</legend>

          <div class="form_grid">
            <div class="field">
              <label for="nom_projet">Nom du projet</label>
              <input id="nom_projet" name="name" type="text" value="{{ old('name', $project->name) }}">
              @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
              <label for="client">Client</label>
              <select id="client" name="client_id" required>
                @foreach($clients as $client)
                  <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id) == $client->id)>{{ $client->name }}</option>
                @endforeach
              </select>
              @error('client_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field form_grid_full">
              <label for="description">Description</label>
              <textarea id="description" name="description">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="field">
              <label for="date_debut">Date de début</label>
              <input id="date_debut" name="start_date" type="date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
            </div>

            <div class="field">
              <label for="date_fin">Date de fin</label>
              <input id="date_fin" name="end_date" type="date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}">
            </div>

            <div class="field">
              <label for="statut_projet">Statut</label>
              <select id="statut_projet" name="status">
                @foreach(\App\Models\Project::STATUS_LABELS as $status => $label)
                  <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $label }}</option>
                @endforeach
              </select>
            </div>

            <div class="field">
              <label for="type_contrat">Type de contrat</label>
              <select id="type_contrat" name="contract_type">
                <option value="forfait_heures" @selected(old('contract_type', $project->contract_type) === 'forfait_heures')>Forfait (enveloppe d'heures)</option>
                <option value="regie" @selected(old('contract_type', $project->contract_type) === 'regie')>Régie (facturation au temps)</option>
              </select>
            </div>
          </div>
        </fieldset>

        <fieldset class="form_block">
          <legend class="form_legend">Contrat</legend>

          <div class="form_grid">
            <div class="field contract-hours">
              <label for="heures_incluses">Heures incluses</label>
              <input id="heures_incluses" name="included_hours" type="number" value="{{ old('included_hours', $project->included_hours) }}" min="0" step="0.5">
              @error('included_hours') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
              <label for="taux_horaire">Taux horaire (supp.)</label>
              <input id="taux_horaire" name="hourly_rate" type="number" value="{{ old('hourly_rate', $project->hourly_rate) }}" min="0" step="0.5">
              @error('hourly_rate') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field form_grid_full">
              <label for="auto_facturable">Quand les heures sont épuisées…</label>
              <select id="auto_facturable" name="auto_billable_when_exceeded">
                <option value="1" @selected(old('auto_billable_when_exceeded', $project->auto_billable_when_exceeded) == 1)>Marquer automatiquement “facturable”</option>
                <option value="0" @selected(old('auto_billable_when_exceeded', $project->auto_billable_when_exceeded) == 0)>Laisser le choix au collaborateur</option>
              </select>
            </div>
          </div>
        </fieldset>

        <div class="form_actions">
          <button class="btn_primary" type="submit">Enregistrer</button>
          <a class="btn_link" href="{{ route('projects.show', $project) }}">Annuler</a>
        </div>
      </form>
    </section>
  </main>
</div>
@endsection
