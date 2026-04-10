<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Project extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        'actif' => 'Actif',
        'pause' => 'En pause',
        'termine' => 'Terminé',
        'archive' => 'Archivé',
    ];

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'status',
        'contract_type',
        'included_hours',
        'hourly_rate',
        'auto_billable_when_exceeded',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'included_hours' => 'float',
            'hourly_rate' => 'float',
            'auto_billable_when_exceeded' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getIncludedSpentHoursAttribute(): float
    {
        $this->loadMissing('tickets.timeEntries');

        $total = $this->tickets
            ->where('billing_type', Ticket::BILLING_INCLUDED)
            ->sum(fn (Ticket $ticket) => $ticket->spent_hours);

        return round((float) $total, 2);
    }

    public function getBillableSpentHoursAttribute(): float
    {
        $this->loadMissing('tickets.timeEntries');

        $total = $this->tickets
            ->where('billing_type', Ticket::BILLING_BILLABLE)
            ->sum(fn (Ticket $ticket) => $ticket->spent_hours);

        return round((float) $total, 2);
    }

    public function getRemainingHoursAttribute(): float
    {
        return round(max((float) $this->included_hours - $this->included_spent_hours, 0), 2);
    }

    public function getOverflowHoursAttribute(): float
    {
        return round(max($this->included_spent_hours - (float) $this->included_hours, 0), 2);
    }

    public function getHoursToBillAttribute(): float
    {
        $overflow = $this->auto_billable_when_exceeded ? $this->overflow_hours : 0;

        return round($this->billable_spent_hours + $overflow, 2);
    }

    public function getConsumptionProgressAttribute(): int
    {
        if ((float) $this->included_hours <= 0) {
            return 0;
        }

        return min((int) round(($this->included_spent_hours / (float) $this->included_hours) * 100), 100);
    }

    public function getCollaboratorNamesAttribute(): Collection
    {
        $this->loadMissing('tickets.assignee');

        return $this->tickets
            ->map(fn (Ticket $ticket) => $ticket->assignee?->name)
            ->filter()
            ->unique()
            ->values();
    }
}
