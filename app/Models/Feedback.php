<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    /**
     * Always eager-load the related user and replies' users to avoid lazy-loading
     * violations when lazy loading is disabled in the app configuration.
     */
    protected $with = ['user', 'replies.user'];

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_avatar',
        'rating',
        'content',
        'parent_id',
        'is_admin_reply',
        'likes_count',
        'dislikes_count',
    ];

    /**
     * Get the user who owns the feedback.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent feedback.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Feedback::class, 'parent_id');
    }

    /**
     * Get the nested replies.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Feedback::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get all reactions for this feedback.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(FeedbackReaction::class, 'feedback_id');
    }
}
