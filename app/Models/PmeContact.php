<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmeContact extends Model
{
    protected $fillable = ['company_name', 'email', 'message'];
}
