<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'collection_id', 'session_id', 'event_type',
        'engaged', 'time_on_page',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
