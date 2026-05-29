<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['address_id', 'name', 'phone_number', 'email', 'nb_employee'])]

class Company extends Model
{

    public function address(): BelongsTo //clé étrangère dans ma table
    {
        return $this->belongsTo(Address::class);
    }

    public function collections(): HasMany //clé étrangère dans une autre classe
    {
        return $this->hasMany(Collection::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'company_label')
            ->withPivot('start_date','end_date')
            ->withTimestamps();
    }

    public function trophees(): BelongsToMany
{
    return $this->belongsToMany(Trophee::class, 'company_trophee')
                ->withPivot('rank')
                ->withTimestamps();
}

}
