<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'company_name', 'contact_name', 'email',
        'phone', 'employees_count', 'street', 'postal_code', 'city',
    ];
}
