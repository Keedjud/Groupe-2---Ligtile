<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'collection_id', 'session_id', 'event_type',
        'part', 'question_slug', 'answer_result',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
