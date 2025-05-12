<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Agency;
use App\Models\Plan;
class Statistic extends Model
{

    use HasFactory;
    protected $table = 'TStatistics';
    protected $primaryKey = 'StatisticId';
    public $timestamps = false;

    protected $fillable = [
        'AgencyId',
        'PlanId',
        'VolumeConsumed',
        'AmountSpent'
    ];

    /**
     * Relation avec l'agence concernée
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'AgencyId');
    }

    /**
     * Relation avec le plan concerné
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'PlanId');
    }
}
