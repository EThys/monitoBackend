<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Agency;
class Alert extends Model
{

    use HasFactory;

    protected $table = 'TAlerts';
    protected $primaryKey = 'AlertId';
    public $timestamps = false;
    protected $fillable = [
        'AgencyId',
        'AlertType',
        'AlertMessage',
        'IsResolved'
    ];

     /**
     * Relation avec l'agence concernée
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'AgencyId');
    }
}
