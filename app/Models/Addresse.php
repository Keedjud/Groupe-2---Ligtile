<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresse extends Model
{

    protected $fillable = [
        'postal_code',
        'city',
        'street',
        'number',
    ];




public function company(): HasMany
{
    return $this->hasMany(Company::class);
 }
}
