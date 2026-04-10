<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    public const BILLING_INCLUDED = 'included';
    public const BILLING_BILLABLE = 'billable';

    public const VALIDATION_PENDING = 'pending';
    public const VALIDATION_ACCEPTED = 'accepted';
    public const VALIDATION_REFUSED = 'refused';

    public const STATUS_LABELS = [
        'open' => 'Open',
        'progress' => 'In progress',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
    ];

    public const PRIORITY_LABELS = [
        'high' => 'High',
        'medium' => 'Medium',
        'low' => 'Low',
    ];

    protected $fillable = [
        'project_id',
        'assignee_id',
        'code',
        'title',
        'description',
        'status',
        'priority',
        'billing_type',
        'estimated_hours',
        'client_validation_status',
        'client_validation_comment',
        'client_validated_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_hours' => 'float',
            'client_validated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket): void {
            if (! $ticket->code) {
                $lastCode = static::query()->latest('id')->value('code');
                $lastNumber = $lastCode ? (int) preg_replace('/\D+/', '', (string) $lastCode) : 0;
                $ticket->code = 'ACC' . str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function getSpentHoursAttribute(): float
    {
        $this->loadMissing('timeEntries');

        return round((float) $this->timeEntries->sum('hours'), 2);
    }

    public function getRemainingHoursAttribute(): float
    {
        return round(max((float) ($this->estimated_hours ?? 0) - $this->spent_hours, 0), 2);
    }

    public function getBillableHoursAttribute(): float
    {
        return round($this->billing_type === self::BILLING_BILLABLE ? $this->spent_hours : 0, 2);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusClassAttribute(): string
    {
        return in_array($this->status, ['open', 'progress', 'resolved', 'closed'], true)
            ? $this->status
            : 'open';
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? ucfirst((string) $this->priority);
    }

    public function getPriorityClassAttribute(): string
    {
        return $this->priority === 'medium' ? 'med' : $this->priority;
    }

    public function getBillingLabelAttribute(): string
    {
        return $this->billing_type === self::BILLING_BILLABLE ? 'Facturable' : 'Inclus';
    }

    public function getClientValidationLabelAttribute(): string
    {
        return match ($this->client_validation_status) {
            self::VALIDATION_ACCEPTED => 'Validé par le client',
            self::VALIDATION_REFUSED => 'Refusé par le client',
            self::VALIDATION_PENDING => 'En attente client',
            default => $this->billing_type === self::BILLING_BILLABLE ? 'À envoyer au client' : 'Non nécessaire',
        };
    }

    public function getClientValidationClassAttribute(): string
    {
        return match ($this->client_validation_status) {
            self::VALIDATION_ACCEPTED => 'resolved',
            self::VALIDATION_REFUSED => 'open',
            self::VALIDATION_PENDING => 'progress',
            default => 'progress',
        };
    }

    public function getNeedsClientValidationAttribute(): bool
    {
        return $this->billing_type === self::BILLING_BILLABLE;
    }
}
