@extends('layouts.app')

@section('title', 'Paramètres — Gestion Tickets')

@section('content')
<header>
    <div class="menu_du_haut">
      <h1 class="Tickets">Paramètres</h1>
      <a class="bouton_deconnexion" href="{{ route('login') }}">Déconnexion</a>
    </div>
  </header>

  <div class="layout">
    <aside class="sidebar">
      <nav class="sidebar_nav" aria-label="Navigation principale">
        <a href="{{ route('dashboard') }}">Tableau de bord</a>
        <a href="{{ route('projects.index') }}">Projets</a>
        <a href="{{ route('projects.create') }}">Créer un projet</a>
        <a href="{{ route('projects.show') }}">Détail projet</a>
        <a href="{{ route('projects.edit') }}">Éditer projet</a>
        <a href="{{ route('tickets.index') }}">Tickets</a>
        <a href="{{ route('tickets.create') }}">Créer un ticket</a>
        <a href="{{ route('profile') }}">Profil</a>
        <a href="{{ route('settings') }}" aria-current="page">Paramètres</a>
      </nav>
    </aside>

    <main class="page_main main_tickets">
      <section class="card">
        <h2 class="card-title">Paramètres</h2>
      </section>

      <section class="card">
        <h3 class="card-title">Préférences</h3>

        <form action="{{ route('settings') }}" method="post" style="margin-top: 12px;">
          @csrf
          <fieldset class="form_block">
            <legend class="form_legend">Notifications</legend>

            <div class="checks">
              <label class="check"><input type="checkbox" name="notif" value="email" checked> <span>Email</span></label>
              <label class="check"><input type="checkbox" name="notif" value="app"> <span>Dans l’application</span></label>
            </div>
          </fieldset>

          <fieldset class="form_block" style="margin-top: 12px;">
            <legend class="form_legend">Affichage</legend>

            <div class="form_grid">
              <div class="field">
                <label for="lang">Langue</label>
                <select id="lang" name="lang">
                  <option value="fr" selected>Français</option>
                  <option value="en">English</option>
                </select>
              </div>

              <div class="field">
                <label for="density">Mode</label>
                <select id="density" name="density">
                  <option value="comfortable" selected>Dark</option>
                  <option value="compact">Light</option>
                </select>
              </div>
            </div>
          </fieldset>

          <div class="form_actions">
            <button class="btn_primary" type="submit">Sauvegarder</button>
            <a class="btn_link" href="{{ route('dashboard') }}">Retour</a>
          </div>
        </form>
      </section>
    </main>
  </div>
@endsection
