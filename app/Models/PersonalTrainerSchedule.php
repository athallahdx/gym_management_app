<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonalTrainerSchedule extends Pivot
{
    protected $table = 'personal_trainer_schedules';

    protected $fillable = [
      'scheduled_at',
      'status',
      'check_in_time',
      'check_out_time',
      'training_log',
      'trainer_notes',
      'member_feedback',
      'personal_trainer_assignment_id',
    ];

    protected $casts = [
        'training_log' => 'array',
        'scheduled_at' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function scopeForTrainer($query, $trainerId)
    {
        return $query->whereHas('personalTrainerAssignment', function ($q) use ($trainerId) {
            $q->where('personal_trainer_id', $trainerId);
        });
    }


    public function personalTrainerAssignment(): BelongsTo
    {
        return $this->belongsTo(PersonalTrainerAssignment::class, 'personal_trainer_assignment_id');
    }
}
