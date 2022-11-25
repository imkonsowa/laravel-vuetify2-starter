<?php

namespace App\Models;

use App\Events\TicketCreatedEvent;
use App\Support\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [
        'id'
    ];

    public $searchable = [
        'title',
        'description'
    ];

    protected $dispatchesEvents = [
        'created' => TicketCreatedEvent::class,
    ];
}
