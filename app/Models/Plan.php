<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Agency;
use App\Models\Subscription;
use App\Models\Statistic;

class Plan extends Model
{

    use HasFactory;

    protected $table = 'TPlans';
    protected $primaryKey = 'PlanId';
    public $timestamps = false;

    protected $fillable = [
        'PlanName',
        'PlanDescription',
        'PlanPrice',
        'PlanTotal',
        'PlanSpeed',
        'PlanStatus',
    ];

     /**
     * Relation avec les agences utilisant ce plan
     */
    public function agencies(): HasMany
    {
        return $this->hasMany(Agency::class, 'PlanId');
    }

    /**
     * Relation avec les abonnements pour ce plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'PlanId');
    }

    /**
     * Relation avec les statistiques pour ce plan
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class, 'PlanId');
    }
}
