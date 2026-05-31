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
    protected $appends = ['size_label'];

    /**
     * Catégorie de taille d'entreprise basée sur le nombre d'employés.
     */
    public function getSizeLabelAttribute(): string
    {
        return match (true) {
            $this->nb_employee >= 250 => 'Grande entreprise',
            $this->nb_employee >= 50  => 'Moyenne entreprise',
            $this->nb_employee >= 10  => 'Petite entreprise',
            default                   => 'Très petite entreprise',
        };
    }

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
