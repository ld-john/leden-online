<div>
    <textarea
            placeholder="Message for you sir..."
            wire:model="content"
    >
    </textarea>

    <button wire:click="saveComment">
        Save
    </button>

    @foreach( $this->comments as $comment )
        <div>
            {{ $comment->content }}
        </div>
    @endforeach

</div>
