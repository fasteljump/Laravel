@extends('layouts.app')

@section('title', 'Profil utilisateur — Gestion Tickets')

@section('content')
<header>
    <div class="menu_du_haut">
      <h1 class="Tickets">Profil</h1>
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
        <a href="{{ route('profile') }}" aria-current="page">Profil</a>
        <a href="{{ route('settings') }}">Paramètres</a>
      </nav>
    </aside>

    <main class="page_main main_tickets">
      <section class="card">
        <h2 class="card-title">Mon profil</h2>
      </section>

      <section class="card">
        <h3 class="card-title">Informations</h3>

        <form action="{{ route('profile') }}" method="post" style="margin-top: 12px;">
          @csrf
          <div class="form_grid">
            <div class="field">
              <label for="prenom_p">Prénom</label>
              <input id="prenom_p" name="prenom" type="text" value="Noé">

            </div>

            <div class="field">
              <label for="nom_p">Nom</label>
              <input id="nom_p" name="nom" type="text" value="GOUY">
            </div>

            <div class="field">
              <label for="email_p">Email</label>
              <input id="email_p" name="email" type="email" value="noe@mail.com">
            </div>

            <div class="field">
              <label for="role_p">Rôle</label>
              <select id="role_p" name="role">
                <option value="collaborateur" selected>Collaborateur</option>
                <option value="client">Client</option>
                <option value="admin">Administrateur</option>
              </select>
            </div>

            <div class="field form_grid_full">
              <label for="bio">Info sur le user</label>
              <textarea id="bio" name="bio" placeholder="Une petite description…">Développeur front... ect...</textarea>
            </div>
          </div>

          <div class="form_actions">
            <button class="btn_primary" type="submit">Ajouter l'utilisateur</button>
            <a class="btn_link" href="{{ route('dashboard') }}">Retour</a>
          </div>
        </form>
      </section>
    </main>
  </div>
@endsection
