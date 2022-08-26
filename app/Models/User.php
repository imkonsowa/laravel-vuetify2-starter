<?php

namespace App\Models;

use App\Contracts\SearchableContract;
use App\Events\UserCreatedEvent;
use App\Support\Searchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements SearchableContract, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Searchable, \Illuminate\Auth\MustVerifyEmail;

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected array $searchable = [
        'name',
        'email',
    ];

    protected $dispatchesEvents = [
        'created' => UserCreatedEvent::class
    ];
}
