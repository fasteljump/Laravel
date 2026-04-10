# Gestion Tickets Laravel - CRUD Eloquent

## Ce qui a été ajouté
- vrais contrôleurs Laravel
- modèles Eloquent : Client, Project, Ticket, TimeEntry
- migrations SQLite
- seed de démonstration
- CRUD complet des tickets
- gestion du temps passé sur chaque ticket
- calcul automatique des heures restantes et des heures à facturer

## Commandes à lancer
```bash
php artisan config:clear
php artisan migrate --seed
php artisan serve
```

Puis ouvrir :
`http://127.0.0.1:8000`

## Identifiants de démo
- email : `noe@mail.com`
- mot de passe : `123456`

## Remarques
- la connexion affichée reste encore une page front de démonstration
- le vrai coeur métier est maintenant sur les tickets / projets / temps passé avec Eloquent
- si SQLite n'est pas activé en PHP sur la machine locale, il faut activer l'extension `pdo_sqlite`


## Ajout API REST

Une mini API REST a été ajoutée pour répondre à l'étape API du fil rouge.

Routes :
- `GET /api/tickets` : retourne les derniers tickets en JSON
- `POST /api/tickets` : crée un ticket en JSON

Exploitation côté front :
- la page `tickets/index.blade.php` charge `public/js/tickets-api.js`
- ce script utilise `fetch()` pour :
  - recharger une liste de tickets depuis l'API
  - créer un ticket via l'API sans recharger la page
