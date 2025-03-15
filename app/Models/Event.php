<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    // Définir les champs autorisés pour l'assignation de masse
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'date',
        'category',
        'max_participants',
    ];

    // Générer automatiquement le slug lors de la création
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->slug = Str::slug($event->title);
        });

        static::updating(function ($event) {
            $event->slug = Str::slug($event->title);
        });
    }
}
