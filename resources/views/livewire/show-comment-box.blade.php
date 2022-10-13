<div>
    <div class="card shadow my-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold">Comments on {{ ucwords($commentable_type) }} #{{ $commentable_id }}</h6>
        </div>
        <div class="card-body p-0">
            @forelse( $this->comments as $comment)
                <div class="card my-4 mx-3">
                    <div class="card-header d-flex align-items-center flex-row">
                        <div class="comment-meta flex-grow-1">
                            <h5 class="card-title m-0">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</h5>
                            @if( $comment->user->company)
                                <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->company->company_name }}</h6>
                            @endif
                        </div>
                        @can('admin')
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteComment({{ $comment->id }})">Delete</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <p class="card-text">{!! $comment->content !!}</p>
                    </div>
                    <div class="card-footer text-muted">
                        {{ $comment->created_at->diffForHumans() }} ({{ $comment->created_at->format('d/m/Y') }})
                    </div>
                </div>
            @empty
                <div class="card mx-3 my-4">
                    <div class="card-header">
                        <h5 class="card-title m-0">There are no comments on {{ ucwords($commentable_type) }} #{{ $commentable_id }}</h5>
                    </div>
                </div>
            @endforelse
            @can('admin')
                @forelse($private_comments as $comment)
                    <div class="card text-white bg-dark my-4 mx-3">
                        <div class="card-header d-flex align-items-center flex-row">
                            <div class="comment-meta flex-grow-1">
                                <h5 class="card-title m-0">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</h5>
                                @if($comment->user->company)
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->company->company_name }}</h6>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-danger" wire:click="deleteComment({{ $comment->id }})">Delete</button>
                        </div>
                        <div class="card-body">
                            {!! $comment->content !!}
                        </div>
                        <div class="card-footer">
                            {{ $comment->created_at->diffForHumans() }} ({{ $comment->created_at->format('d/m/Y') }})
                        </div>
                    </div>
                @empty
                    <div class="card bg-dark text-white my-4 mx-3">
                        <div class="card-header ">
                            <h5 class="card-title m-0">There are no private comments on {{ ucwords($commentable_type) }} #{{$commentable_id}}</h5>
                        </div>
                    </div>
                @endforelse
            @endcan
        </div>
    </div>
</div>
