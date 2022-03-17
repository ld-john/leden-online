<?php

namespace App\Http\Livewire;

use App\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentBox extends Component
{
    public $content;
    public $order_id;
    public $comments;



    public function mount()
    {
        $this->getComments();
    }
    public function render()
    {
        return view('livewire.comment-box');
    }

    public function saveComment()
    {
        $comment = new Comment();

        $comment->content = $this->content;
        $comment->user_id = Auth::id();
        $comment->order_id = $this->order_id;

        $comment->save();

        $this->getComments();

        $this->content = '';

        $this->emit('commentSaved');

    }

    public function deleteComment( $id )
    {
        $comment = Comment::find( $id );
        $comment->delete();

        $this->getComments();

    }

    public function getComments()
    {
        $this->comments = Comment::where( 'order_id' , $this->order_id )->orderBy( 'created_at' , 'DESC')->get();
    }


}
