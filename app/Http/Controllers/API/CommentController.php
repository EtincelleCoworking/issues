<?php

namespace App\Http\Controllers\API;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function add(Request $request, $issue_id)
    {
        $json = json_decode($request->getContent());

        $comment = new Comment();
        $comment->reporter_id = Auth::id();
        $comment->issue_id = $issue_id;
        $comment->content = $json->content;
        $comment->save();

        $comments = Comment::where('issue_id', $issue_id)
            ->with('reporter')
            ->orderBy('created_at', 'ASC')
            ->get();
        return response()->json(['status' => 'success', 'comments' => $comments]);
    }

}
