<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'company_id', 'user_id', 'address_id',
    'start_date', 'end_date', 'capacity',
    'primary_color', 'secondary_color',
    'logo_url', 'onedoc_url', 'kit_url', 'public_token',
])]
class Collection extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
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
