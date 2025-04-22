<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeratedContent extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'status',
        'moderated_by',
        'reason',
        'action_taken'
    ];

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
} 