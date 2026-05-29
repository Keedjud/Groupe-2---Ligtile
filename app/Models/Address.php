<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['postal_code', 'city', 'street', 'number'])]
 //fillable s'applique à la classe enitère
class Address extends Model
{
    public function companies(): HasMany //plusieurs compannies pourraient avoir la même adresse
    {
        return $this->hasMany(Company::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
