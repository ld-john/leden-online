<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentBox extends Component
{
    public $content;
    public $commentable_id;
    public $commentable_type;
    public $comments;
    public $private_comments;
    public $dealer_comment = false;
    public $privacy = false;

    public function mount(): void
    {
        $this->getComments();
    }
    public function render(): Factory|View|Application
    {
        return view('livewire.comment-box');
    }

    public function saveComment(): void
    {
        $comment = new Comment();

        $comment->content = $this->content;
        $comment->user_id = Auth::id();
        $comment->commentable_id = $this->commentable_id;
        $comment->commentable_type = $this->commentable_type;
        $comment->private = $this->privacy;
        $comment->dealer_comment = $this->dealer_comment;

        $comment->save();

        $this->getComments();

        $this->content = '';

        $this->dispatch('commentSaved');
    }

    public function deleteComment($id): void
    {
        $comment = Comment::find($id);
        $comment->delete();

        $this->getComments();
    }

    public function getComments(): void
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
}
