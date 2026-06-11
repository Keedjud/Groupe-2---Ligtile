<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'company_id', 'user_id',
    'contact_email', 'contact_phone',
    'venue_street', 'venue_number', 'venue_postal_code', 'venue_city',
    'start_date', 'end_date', 'capacity',
    'primary_color',
    'logo_url', 'onedoc_url', 'kit_url', 'public_token',
    'link_generated_at', 'kit_sent_at',
])]
class Collection extends Model
{
    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'link_generated_at' => 'datetime',
        'kit_sent_at'       => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quizEvents(): HasMany
    {
        return $this->hasMany(QuizEvent::class);
    }

    public function pageEvents(): HasMany
    {
        return $this->hasMany(PageEvent::class);
    }
}
