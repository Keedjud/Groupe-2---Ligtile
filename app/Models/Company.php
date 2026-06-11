<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['address_id', 'name', 'nb_employee', 'participe_trophee'])]

class Company extends Model
{
    protected $appends = ['size_label'];

    protected $casts = ['participe_trophee' => 'boolean'];

    /**
     * Catégorie de taille d'entreprise basée sur le nombre d'employés.
     * Seuil clé : à partir de 1000 employés, le CTS organise une collecte
     * dédiée sur place ; en dessous, l'entreprise se voit réserver des
     * créneaux dans une collecte publique existante.
     */
    public function getSizeLabelAttribute(): string
    {
        return match (true) {
            $this->nb_employee >= 1000 => 'Grande entreprise',
            $this->nb_employee >= 500  => 'Moyenne entreprise',
            $this->nb_employee >= 100  => 'Petite entreprise',
            default                    => 'Très petite entreprise',
        };
    }

    public function address(): BelongsTo //clé étrangère dans ma table
    {
        return $this->belongsTo(Address::class);
    }

    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class);
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
