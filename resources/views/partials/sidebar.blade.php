<aside class="sidebar">
  <nav class="sidebar_nav" aria-label="Navigation principale">
    <a href="{{ route('dashboard') }}" @if(($active ?? '') === 'dashboard') aria-current="page" @endif>Tableau de bord</a>
    <a href="{{ route('projects.index') }}" @if(($active ?? '') === 'projects.index') aria-current="page" @endif>Projets</a>
    <a href="{{ route('projects.create') }}" @if(($active ?? '') === 'projects.create') aria-current="page" @endif>Créer un projet</a>
    <a href="{{ route('projects.show') }}" @if(($active ?? '') === 'projects.show') aria-current="page" @endif>Détail projet</a>
    <a href="{{ route('projects.edit') }}" @if(($active ?? '') === 'projects.edit') aria-current="page" @endif>Éditer projet</a>
    <a href="{{ route('tickets.index') }}" @if(($active ?? '') === 'tickets.index') aria-current="page" @endif>Tickets</a>
    <a href="{{ route('tickets.create') }}" @if(($active ?? '') === 'tickets.create') aria-current="page" @endif>Créer un ticket</a>
    <a href="{{ route('profile') }}" @if(($active ?? '') === 'profile') aria-current="page" @endif>Profil</a>
    <a href="{{ route('settings') }}" @if(($active ?? '') === 'settings') aria-current="page" @endif>Paramètres</a>
  </nav>
</aside>
