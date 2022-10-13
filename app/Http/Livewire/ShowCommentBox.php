<?php

namespace App\Http\Livewire;

use App\Comment;
use Livewire\Component;

class ShowCommentBox extends Component
{
    public $content;
    public $commentable_id;
    public $commentable_type;
    public $comments;
    public $private_comments;

    public function mount()
    {
        $this->getComments();
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        $this->getComments();
    }

    public function getComments()
    {
        $this->comments = Comment::where(
            'commentable_id',
            $this->commentable_id,
        )
            ->where('commentable_type', $this->commentable_type)
            ->where('private', 0)
            ->orderBy('created_at', 'DESC')
            ->get();
        $this->private_comments = Comment::where(
            'commentable_id',
            $this->commentable_id,
        )
            ->where('commentable_type', $this->commentable_type)
            ->where('private', 1)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
    public function render()
    {
        return view('livewire.show-comment-box');
    }
}
