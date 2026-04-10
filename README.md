# Gestion de Ticketing — Laravel

## Présentation du projet

Ce projet est une application web de gestion de ticketing réalisée dans le cadre du **TP fil rouge**.  
L’objectif est de simuler un outil utilisé par une société de services pour gérer :

- les clients
- les projets
- les tickets
- le temps passé
- la facturation liée aux contrats

L’application a été développée progressivement en suivant les différentes étapes du module :
HTML/CSS, JavaScript, PHP, base de données, Laravel, CRUD et API REST.

---

## Objectifs du projet

Cette application permet de :

- consulter les projets
- consulter les tickets
- créer, modifier et supprimer des tickets
- enregistrer le temps passé sur un ticket
- calculer automatiquement :
  - les heures restantes
  - les heures à facturer
- distinguer les tickets :
  - inclus dans le contrat
  - facturables
- valider ou refuser certains tickets facturables
- utiliser une route API avec `fetch()` côté JavaScript

---

## Languages

- **HTML5**
- **CSS3**
- **JavaScript natif**
- **PHP**
- **Laravel 12**
- **Eloquent ORM**
- **SQLite**
- **API REST**
- **Git / GitHub**

---

## Structure du projet

```text
app/
  Http/Controllers/
    Api/
    DashboardController.php
    PageController.php
    ProjectController.php
    TicketController.php
    TicketValidationController.php
    TimeEntryController.php

  Models/
    Client.php
    Project.php
    Ticket.php
    TimeEntry.php
    User.php

database/
  migrations/
  seeders/
  database.sqlite

public/
  css/
  js/
  images/

resources/
  views/
    auth/
    layouts/
    partials/
    projects/
    tickets/
    dashboard.blade.php
    profile.blade.php
    settings.blade.php

routes/
  web.php
  api.php
