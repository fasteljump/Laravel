<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'noe@mail.com'],
            ['name' => 'Noé', 'password' => Hash::make('123456')]
        );

        $engineer = User::query()->firstOrCreate(
            ['email' => 'ingenieur@mail.com'],
            ['name' => 'Ingénieur Team', 'password' => Hash::make('123456')]
        );

        $support = User::query()->firstOrCreate(
            ['email' => 'support@mail.com'],
            ['name' => 'Support Team', 'password' => Hash::make('123456')]
        );

        $design = User::query()->firstOrCreate(
            ['email' => 'design@mail.com'],
            ['name' => 'Design Team', 'password' => Hash::make('123456')]
        );

        $thales = Client::query()->firstOrCreate(['name' => 'Thales'], ['email' => 'contact@thales.test']);
        $roger = Client::query()->firstOrCreate(['name' => 'Roger'], ['email' => 'contact@roger.test']);
        $dji = Client::query()->firstOrCreate(['name' => 'DJI'], ['email' => 'contact@dji.test']);

        $backOffice = Project::query()->firstOrCreate(
            ['name' => 'Back-office'],
            [
                'client_id' => $thales->id,
                'description' => 'Projet interne pour gérer les tickets et la facturation.',
                'status' => 'actif',
                'contract_type' => 'forfait_heures',
                'included_hours' => 50,
                'hourly_rate' => 80,
                'auto_billable_when_exceeded' => true,
                'start_date' => now()->subMonths(3)->toDateString(),
                'end_date' => now()->addMonths(9)->toDateString(),
            ]
        );

        $siteVitrine = Project::query()->firstOrCreate(
            ['name' => 'Site vitrine'],
            [
                'client_id' => $roger->id,
                'description' => 'Refonte du site vitrine avec optimisation mobile.',
                'status' => 'actif',
                'contract_type' => 'forfait_heures',
                'included_hours' => 20,
                'hourly_rate' => 95,
                'auto_billable_when_exceeded' => true,
                'start_date' => now()->subMonths(1)->toDateString(),
                'end_date' => now()->addMonths(5)->toDateString(),
            ]
        );

        $api = Project::query()->firstOrCreate(
            ['name' => 'API / Intégrations'],
            [
                'client_id' => $dji->id,
                'description' => 'Connecteurs API et synchronisation des données.',
                'status' => 'pause',
                'contract_type' => 'regie',
                'included_hours' => 0,
                'hourly_rate' => 110,
                'auto_billable_when_exceeded' => true,
                'start_date' => now()->subMonths(2)->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString(),
            ]
        );

        $ticket1 = Ticket::query()->firstOrCreate(
            ['title' => "Problème sur l'affichage mobile"],
            [
                'project_id' => $backOffice->id,
                'assignee_id' => $engineer->id,
                'description' => 'Sur mobile (< 480px), le menu déborde et certains boutons deviennent impossibles à cliquer.',
                'status' => 'resolved',
                'priority' => 'high',
                'billing_type' => Ticket::BILLING_BILLABLE,
                'client_validation_status' => Ticket::VALIDATION_PENDING,
                'estimated_hours' => 2,
            ]
        );

        $ticket2 = Ticket::query()->firstOrCreate(
            ['title' => 'Menu qui déborde sur mobile'],
            [
                'project_id' => $backOffice->id,
                'assignee_id' => $support->id,
                'description' => "Le menu mobile dépasse de l'écran sur certaines tailles d'appareil.",
                'status' => 'progress',
                'priority' => 'medium',
                'billing_type' => Ticket::BILLING_INCLUDED,
                'estimated_hours' => 1.5,
            ]
        );

        $ticket3 = Ticket::query()->firstOrCreate(
            ['title' => 'Bug formulaire (email)'],
            [
                'project_id' => $siteVitrine->id,
                'assignee_id' => $design->id,
                'description' => 'Le champ email refuse des adresses pourtant valides.',
                'status' => 'open',
                'priority' => 'high',
                'billing_type' => Ticket::BILLING_INCLUDED,
                'estimated_hours' => 3,
            ]
        );

        $ticket4 = Ticket::query()->firstOrCreate(
            ['title' => 'Endpoint OAuth à sécuriser'],
            [
                'project_id' => $api->id,
                'assignee_id' => $admin->id,
                'description' => 'Vérifier la durée de vie des tokens et les scopes exposés.',
                'status' => 'closed',
                'priority' => 'low',
                'billing_type' => Ticket::BILLING_BILLABLE,
                'client_validation_status' => Ticket::VALIDATION_ACCEPTED,
                'client_validated_at' => now()->subDay(),
                'estimated_hours' => 5,
            ]
        );

        $entries = [
            [$ticket1->id, $engineer->id, 1.5, now()->subDays(12)->toDateString(), 'Correction du débordement sur iPhone SE.'],
            [$ticket2->id, $support->id, 1.0, now()->subDays(8)->toDateString(), 'Reproduction du bug et capture vidéo.'],
            [$ticket3->id, $design->id, 2.5, now()->subDays(3)->toDateString(), 'Analyse des messages côté front.'],
            [$ticket4->id, $admin->id, 4.0, now()->subDays(1)->toDateString(), 'Audit OAuth et mise à jour de la documentation.'],
        ];

        foreach ($entries as [$ticketId, $userId, $hours, $workDate, $comment]) {
            TimeEntry::query()->firstOrCreate(
                [
                    'ticket_id' => $ticketId,
                    'user_id' => $userId,
                    'hours' => $hours,
                    'work_date' => $workDate,
                ],
                ['comment' => $comment]
            );
        }
    }
}
