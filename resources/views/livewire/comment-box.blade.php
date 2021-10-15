<div>
    <h4 class="px-4 pt-3 pb-2">Add a new comment</h4>
    <div class="w-full md:w-full px-3 mb-2 mt-2">
        <textarea
                placeholder="Type Your Comment"
                wire:model="content"
                class="form-control mb-3"
        >
        </textarea>
        <button wire:click="saveComment" class="btn btn-primary">
            Save
        </button>
    </div>



    @foreach( $this->comments as $comment )
        <div class="card my-4 mx-3">
            <div class="card-header">
                <h5 class="card-title">{{ $comment->user->firstname }} {{$comment->user->lastname}}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->company->company_name }}</h6>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $comment->content }}</p>
            </div>
            <div class="card-footer text-muted">
                {{ $comment->created_at->diffForHumans() }} ({{$comment->created_at->format('d/m/y')}})
            </div>
        </div>
    @endforeach

</div>
