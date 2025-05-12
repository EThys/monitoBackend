<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Agency;
use App\Models\Plan;


class Subscription extends Model
{

    use HasFactory;

    protected $table = 'TSubscriptions';
    protected $primaryKey = 'SubscriptionId';
    public $timestamps = false;

    protected $fillable = [
        'AgencyId',
        'PlanId',
        'StartDate',
        'EndDate',
        'Status',
        'PaymentDetails'
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'AgencyId');
    }

    /**
     * Relation avec le plan souscrit
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'PlanId');
    }
}
