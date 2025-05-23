<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymClassSchedule extends Model
{
    protected $table = 'gym_class_schedules';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'slot',
        'gym_class_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];


    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class);
    }

    public function gymClassAttendances(): HasMany
    {
        return $this->hasMany(GymClassAttendance::class);
    }
}
