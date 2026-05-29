<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]

class Label extends Model
{

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class,'company_label')
            ->withPivot('start_date','end_date')
            ->withTimestamps();
    }
}
