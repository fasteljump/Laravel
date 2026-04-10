@extends('layouts.app')

@section('title', 'Créer mon profil')

@section('content')
<main class="authentification">
    <div class="card" style="width: min(640px, 100%);">
      <h1 class="card-title">Créer mon profil</h1>
      <div id="formMessage" class="form-message" aria-live="polite"></div>
      <form id="inscription" action="{{ route('login') }}" method="get" style="margin-top:16px;">
        <div class="form_grid">
          <div class="field">
            <label for="prenom">Prénom</label>
            <input id="prenom" name="prenom" type="text" >
            <p class="field-error" id="error-prenom"></p>
          </div>

          <div class="field">
            <label for="nom">Nom</label>
            <input id="nom" name="nom" type="text" >
            <p class="field-error" id="error-nom"></p>
          </div>

          <div class="field">
            <label for="email_signup">Email</label>
            <input id="email_signup" name="email" type="email" autocomplete="email" >
            <p class="field-error" id="error-email"></p>
          </div>

          <div class="field">
            <label for="role">Rôle</label>
            <select id="role" name="role" >
              <option value="">— Choisir —</option>
              <option value="client">Client</option>
              <option value="collaborateur">Collaborateur</option>
              <option value="admin">Administrateur</option>
            </select>
            <p class="field-error" id="error-role"></p>
          </div>

          <div class="field form_grid_full">
            <label for="password_signup">Mot de passe</label>
            <input id="password_signup" name="password" type="password"  minlength="6" placeholder="6 caractères minimum">
            <p class="field-error" id="error-mdp"></p>
          </div>

          <div class="field form_grid_full">
            <label for="password_confirm">Confirmer le mot de passe</label>
            <input id="password_confirm" name="password_confirm" type="password"  minlength="6">
            <p class="field-error" id="error-mdpp"></p>
          </div>
        </div>

        <div class="form_actions">
          <button class="btn_primary" type="submit">Créer mon profil</button>
          <a class="btn_link" href="{{ route('login') }}">Annuler</a>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
<script src="{{ asset('js/inscription.js') }}" defer></script>
@endpush
