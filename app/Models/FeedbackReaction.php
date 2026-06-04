<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackReaction extends Model
{
    protected $table = 'feedback_reactions';

    protected $fillable = [
        'feedback_id',
        'user_id',
        'session_id',
        'type', // 'like' or 'dislike'
    ];

    /**
     * Get the feedback associated with the reaction.
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class, 'feedback_id');
    }

    /**
     * Get the user who made the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
