@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<main class="authentification">
    <div class="bloc1">

      <section class="section1" aria-labelledby="titre1">
        <div class="ligne1">
          <img class="image1" src="{{ asset('images/image1.png') }}" alt="Logo de l'application">
          <h1 id="titre1" class="titre1">Bienvenue !</h1>
        </div>

        <ul>
          <li>
            <ul>
              <li>Page de connexion</li>
              <li>Tableau de bord</li>
              <li>Liste des projets</li>
              <li>Liste des tickets</li>
              <li>Détail d’un ticket</li>
              <li>Formulaire de création de ticket</li>
              <li>Pages supplémentaires : inscription, mot de passe oublié, profil, paramètres…</li>
            </ul>
          </li>
        </ul>
      </section>

      <section class="formulaire" aria-labelledby="titre2">
        <h2 id="titre2" class="formulaire_titre2">Connexion</h2>
        <p class="Aide">Renseigne ton email et ton mot de passe.</p>

        <div id="loginMessage" class="form-message" aria-live="polite"></div>

        <form id="loginForm" action="{{ route('dashboard') }}" method="get" novalidate>

          <div class="champs_mail">
            <label class="label" for="email">Email</label>
            <input class="input" id="email" name="email" type="email" autocomplete="email" placeholder="noe@mail.com" required>
            <p class="field-error" id="error-email"></p>
          </div>

          <div class="champs_password">
            <label class="label" for="password">Mot de passe</label>
            <input class="input" id="password" name="password" type="password" autocomplete="current-password" placeholder="6 caractères minimum" required minlength="6">
            <p class="field-error" id="error-password"></p>
          </div>

          <div class="action_connexion">
            <button class="bouton_connexion" type="submit">Se connecter</button>

            <a class="demo_page_connexion" href="{{ route('dashboard') }}">
              Accéder au tableau de bord
            </a>

            <div class="liens_connexion">
              <a class="lien_secondaire" href="{{ route('forgot-password') }}">Mot de passe oublié ?</a>
              <span class="sep">•</span>
              <a class="lien_secondaire" href="{{ route('register') }}">Créer mon profil</a>
            </div>
          </div>

        </form>
      </section>

    </div>
  </main>
@endsection

@push('scripts')
<script src="{{ asset('js/login-form.js') }}" defer></script>
@endpush
