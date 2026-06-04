<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\FeedbackReaction;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    /**
     * Store a new community review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'is_admin_reply' => false,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá trải nghiệm!');
    }

    /**
     * Reply to an existing review (user reply).
     */
    public function reply(Request $request, Feedback $feedback)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'parent_id' => $feedback->id,
            'content' => $request->content,
            'is_admin_reply' => false,
        ]);

        return back()->with('success', 'Đã gửi câu trả lời của bạn!');
    }

    /**
     * React (like/dislike) to a review or reply via AJAX.
     */
    public function react(Request $request, Feedback $feedback)
    {
        $type = $request->input('type');
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid reaction type'], 400);
        }

        $userId = auth()->id();
        $sessionId = session()->getId();

        // Check if reaction exists
        $reaction = FeedbackReaction::where('feedback_id', $feedback->id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->first();

        DB::transaction(function () use ($feedback, $reaction, $type, $userId, $sessionId) {
            if ($reaction) {
                if ($reaction->type === $type) {
                    // Clicked same type again -> delete/remove reaction (Toggle off)
                    $reaction->delete();
                    if ($type === 'like') {
                        $feedback->decrement('likes_count');
                    } else {
                        $feedback->decrement('dislikes_count');
                    }
                } else {
                    // Clicked different type -> change reaction type
                    $reaction->update(['type' => $type]);
                    if ($type === 'like') {
                        $feedback->increment('likes_count');
                        $feedback->decrement('dislikes_count');
                    } else {
                        $feedback->increment('dislikes_count');
                        $feedback->decrement('likes_count');
                    }
                }
            } else {
                // Create new reaction
                FeedbackReaction::create([
                    'feedback_id' => $feedback->id,
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                    'type' => $type
                ]);
                if ($type === 'like') {
                    $feedback->increment('likes_count');
                } else {
                    $feedback->increment('dislikes_count');
                }
            }
        });

        // Ensure counts are non-negative
        $feedback->refresh();
        $feedback->likes_count = max(0, $feedback->likes_count);
        $feedback->dislikes_count = max(0, $feedback->dislikes_count);
        $feedback->save();

        return response()->json([
            'likes' => $feedback->likes_count,
            'dislikes' => $feedback->dislikes_count
        ]);
    }

    /**
     * Admin submits or updates a reply from Admin Dashboard.
     */
    public function adminReply(Request $request, Feedback $feedback)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        // Find existing admin reply
        $adminReply = Feedback::where('parent_id', $feedback->id)
            ->where('is_admin_reply', true)
            ->first();

        if ($adminReply) {
            $adminReply->update([
                'content' => $request->reply
            ]);
        } else {
            Feedback::create([
                'guest_name' => 'Admin HealthAI',
                'guest_avatar' => 'https://ui-avatars.com/api/?name=Admin+HealthAI&background=6366f1&color=fff',
                'parent_id' => $feedback->id,
                'content' => $request->reply,
                'is_admin_reply' => true
            ]);
        }

        // Create notification for the feedback owner
        if ($feedback->user_id) {
            Notification::create([
                'user_id' => $feedback->user_id,
                'type' => 'admin_reply',
                'title' => 'Admin đã phản hồi đánh giá của bạn',
                'message' => mb_substr($request->reply, 0, 100) . (mb_strlen($request->reply) > 100 ? '...' : ''),
                'link' => '/pricing',
                'feedback_id' => $feedback->id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Admin reacts (like/dislike) to a feedback - creates notification.
     */
    public function adminReact(Request $request, Feedback $feedback)
    {
        $type = $request->input('type');
        if (!in_array($type, ['like', 'dislike'])) {
            return response()->json(['error' => 'Invalid reaction type'], 400);
        }

        // Use a fixed admin identifier for reactions
        $adminSessionId = 'admin_' . session()->getId();

        $reaction = FeedbackReaction::where('feedback_id', $feedback->id)
            ->where('session_id', $adminSessionId)
            ->first();

        DB::transaction(function () use ($feedback, $reaction, $type, $adminSessionId) {
            if ($reaction) {
                if ($reaction->type === $type) {
                    $reaction->delete();
                    if ($type === 'like') {
                        $feedback->decrement('likes_count');
                    } else {
                        $feedback->decrement('dislikes_count');
                    }
                } else {
                    $reaction->update(['type' => $type]);
                    if ($type === 'like') {
                        $feedback->increment('likes_count');
                        $feedback->decrement('dislikes_count');
                    } else {
                        $feedback->increment('dislikes_count');
                        $feedback->decrement('likes_count');
                    }
                }
            } else {
                FeedbackReaction::create([
                    'feedback_id' => $feedback->id,
                    'user_id' => null,
                    'session_id' => $adminSessionId,
                    'type' => $type
                ]);
                if ($type === 'like') {
                    $feedback->increment('likes_count');
                } else {
                    $feedback->increment('dislikes_count');
                }
            }
        });

        $feedback->refresh();
        $feedback->likes_count = max(0, $feedback->likes_count);
        $feedback->dislikes_count = max(0, $feedback->dislikes_count);
        $feedback->save();

        // Create notification for the feedback owner
        if ($feedback->user_id && $type === 'like') {
            // Only notify on likes, not dislikes
            Notification::create([
                'user_id' => $feedback->user_id,
                'type' => 'admin_like',
                'title' => 'Admin đã thích đánh giá của bạn',
                'message' => '"' . mb_substr($feedback->content, 0, 80) . (mb_strlen($feedback->content) > 80 ? '...' : '') . '"',
                'link' => '/pricing',
                'feedback_id' => $feedback->id,
            ]);
        }

        return response()->json([
            'likes' => $feedback->likes_count,
            'dislikes' => $feedback->dislikes_count
        ]);
    }
}
