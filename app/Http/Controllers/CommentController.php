<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function makeCommentsPolymorphic()
    {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $comment->update([
                'commentable_type' => 'order',
            ]);
        }
    }
}
