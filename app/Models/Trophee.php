<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'year'])]
class Trophee extends Model
{
    public function companies(): BelongsToMany
{ //va chercher des trophee au travers de la table compnay_trophee
    return $this->belongsToMany(Company::class, 'company_trophee')
                ->withPivot('rank')
                ->withTimestamps();
}

}
