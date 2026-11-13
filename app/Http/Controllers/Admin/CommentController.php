<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Kommentariýalaryň sanawy
     * Admin: hemmesi | User: diňe öz emläklerine ýazylanlar
     */
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Comment::with(['property', 'user', 'parent'])->latest();

        if ($user->isAdmin()) {
            // Admin ähli teswirleri görýär
            if ($request->filled('property_id')) {
                $query->where('property_id', $request->property_id);
            }
        } else {
            // Adaty user diňe öz emläklerine gelen teswirleri görýär
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $comments = $query->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Kommentariýany pozmak
     */
    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        // User diňe öz emläklerine gelen teswirleri pozup biler
        if (!$user->isAdmin() && $comment->property?->user_id !== $user->id) {
            return back()->with('error', 'Bu teswiri pozmaga ygtyýaryňyz ýok.');
        }

        $comment->delete();

        return back()->with('success', 'Teswir üstünlikli pozuldy.');
    }
}
