<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'due_date',
        'priority',
        'status'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Status progression rules
    public const STATUS_PROGRESSION = [
        'pending' => 'in_progress',
        'in_progress' => 'done',
    ];

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(string $newStatus): bool
    {
        if ($this->status === $newStatus) {
            return false;
        }

        return isset(self::STATUS_PROGRESSION[$this->status])
            && self::STATUS_PROGRESSION[$this->status] === $newStatus;
    }

    /**
     * Check if task can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->status === 'done';
    }
}
