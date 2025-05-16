<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Statistic;
use App\Models\Alert;



class Agency extends Model
{
    use HasFactory;
    public $table="TAgencies";
    protected $primaryKey = "AgencyId";
    public $timestamps = false;
    protected $fillable = [
        'AgencyName',
        'AgencyAddress',
        'AgencyPhone',
        'AgencyCity',
        'AgencyRegion',
        'PlanId',
        'AgencyStatus',
        'AgencyStartDate',
        'AgencyEndDate',
        'AgencyUsed',
        'AgencyDuration'
    ];

    /**
     * Relation avec le plan souscrit par l'agence
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'PlanId');
    }

    /**
     * Relation avec les utilisateurs de l'agence
     */
    public function users()
    {
        return $this->hasMany(User::class, 'AgencyId');
    }

    /**
     * Relation avec les abonnements de l'agence
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'AgencyId');
    }

    /**
     * Relation avec les statistiques de l'agence
     */
    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'AgencyId');
    }

    /**
     * Relation avec les alertes de l'agence
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class, 'AgencyId');
    }

    /**
     * Relation pour obtenir l'abonnement actif (si existant)
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'AgencyId')
            ->where('Status', 'active');
    }
}
