<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketValidationController extends Controller
{
    public function accept(Request $request, Ticket $ticket): RedirectResponse
    {
        if ($ticket->billing_type !== Ticket::BILLING_BILLABLE) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Ce ticket est déjà inclus dans le contrat.');
        }

        $ticket->update([
            'client_validation_status' => Ticket::VALIDATION_ACCEPTED,
            'client_validation_comment' => trim((string) $request->input('validation_comment', '')) ?: null,
            'client_validated_at' => now(),
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'La facturation du ticket a été validée par le client.');
    }

    public function refuse(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'validation_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $ticket->update([
            'client_validation_status' => Ticket::VALIDATION_REFUSED,
            'client_validation_comment' => trim((string) ($validated['validation_comment'] ?? '')) ?: 'Refus du client sans commentaire.',
            'client_validated_at' => now(),
            'status' => 'progress',
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'La facturation du ticket a été refusée par le client.');
    }
}
