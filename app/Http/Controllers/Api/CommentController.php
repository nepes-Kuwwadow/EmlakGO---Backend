<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Property;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Emläge degişli teswirleri getirmek (replies bilen)
     */
    public function index(Property $property)
    {
        $comments = $property->comments()
            ->approved()
            ->topLevel()
            ->with([
                'user:id,name,role',
                'replies.user:id,name,role',
            ])
            ->latest()
            ->get();

        $avgRating = $property->comments()->approved()->topLevel()->avg('rating');
        $totalComments = $property->comments()->approved()->topLevel()->count();

        return response()->json([
            'comments' => $comments,
            'stats' => [
                'total'          => $totalComments,
                'average_rating' => $avgRating ? round($avgRating, 1) : 5.0,
            ],
        ]);
    }

    /**
     * Täze teswir ýa-da reply ýazmak
     */
    public function store(Request $request, Property $property)
    {
        $user = auth()->user();

        $rules = [
            'comment'   => 'required|string|min:1|max:1000',
            'rating'    => 'nullable|integer|min:1|max:5',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ];

        if (!$user) {
            $rules['name'] = 'required|string|min:2|max:100';
        }

        $validated = $request->validate($rules);

        $isReply = !empty($validated['parent_id']);

        $name   = $user ? $user->name : $validated['name'];
        $userId = $user ? $user->id : null;

        $comment = Comment::create([
            'property_id' => $property->id,
            'parent_id'   => $validated['parent_id'] ?? null,
            'user_id'     => $userId,
            'name'        => $name,
            'comment'     => $validated['comment'],
            'rating'      => $isReply ? null : ($validated['rating'] ?? 5),
            'is_approved' => true,
        ]);

        $comment->load('user:id,name,role');

        return response()->json([
            'message' => $isReply ? 'Jogabyňyz üstünlikli goşuldy!' : 'Teswiriňiz üstünlikli goşuldy!',
            'comment' => $comment,
            'is_reply' => $isReply,
        ], 201);
    }

    /**
     * Teswiri pozmak
     */
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Rugsat berilmedi.'], 401);
        }

        // Diňe teswiriň eýesi ýa-da admin pozup biler
        if ($user->id !== $comment->user_id && !$user->isAdmin()) {
            return response()->json(['message' => 'Bu teswiri pozmaga ygtyýaryňyz ýok.'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Teswir üstünlikli pozuldy.',
        ]);
    }
}
