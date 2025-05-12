<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Agency;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $table="TUsers";
    protected $primaryKey = "UserId";
    public $timestamps = false;
    protected $fillable = ['UserName', 'UserEmail', 'UserPassword', 'UserRole', 'AgencyId'];

      /**
     * Relation avec l'agence à laquelle l'utilisateur appartient
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'AgencyId');
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->UserPassword;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
