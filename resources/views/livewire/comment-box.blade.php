<div>
    <div class="card shadow mt-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Add a new comment</h6>
        </div>
        <div class="card-body">
            <div id="commentEditorContainer" wire:ignore>
                <div id="commentEditor"></div>
            </div>
            @can('admin')
            <div class="form-check">
                <input wire:model="privacy" id="private" class="form-check-input" type="checkbox">
                <label for="private" class="form-check-label">Mark Comment as Private</label>
            </div>
            @endcan
            <button wire:click="saveComment" class="btn btn-primary mt-3">
                Save
            </button>
        </div>

        @forelse( $this->comments as $comment )
            <div class="card my-4 mx-3">
                <div class="card-header d-flex flex-row">
                    <div class="comment-meta flex-grow-1">
                        <h5 class="card-title">{{ $comment->user->firstname }} {{$comment->user->lastname}}</h5>
                        @if ( $comment->user->company )
                            <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->company->company_name }}</h6>
                        @else
                            {{-- Do Nothing --}}
                        @endif

                    </div>
                    @can('admin')
                        <button wire:click="deleteComment( {{$comment->id }} )" class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>
                    @endcan
                </div>
                <div class="card-body">
                    <p class="card-text">{!!  $comment->content !!}</p>
                </div>
                <div class="card-footer text-muted">
                    {{ $comment->created_at->diffForHumans() }} ({{$comment->created_at->format('d/m/y')}})
                </div>
            </div>
        @empty
            <div class="card-header">
                <h5 class="my-4 card-title mx-3">There are no comments yet.</h5>
            </div>
        @endforelse
        @can('admin')
            @forelse($private_comments as $comment)
                <div class="card my-4 mx-3">
                    <div class="card-header text-white bg-dark d-flex flex-row">
                        <div class="comment-meta flex-grow-1">
                            <h5 class="card-title">{{ $comment->user->firstname }} {{$comment->user->lastname}}</h5>
                            @if ( $comment->user->company )
                                <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->company->company_name }}</h6>
                            @else
                                {{-- Do Nothing --}}
                            @endif

                        </div>
                        @can('admin')
                            <button wire:click="deleteComment( {{$comment->id }} )" class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <p class="card-text">{!!  $comment->content !!}</p>
                    </div>
                    <div class="card-footer text-white bg-dark">
                        {{ $comment->created_at->diffForHumans() }} ({{$comment->created_at->format('d/m/Y')}})
                    </div>
                </div>
            @empty
                <div class="card-header">
                    <h5 class="my-4 card-title mx-3">There are no private comments yet.</h5>
                </div>
            @endforelse
        @endcan
    </div>
</div>


@push('custom-styles')
    <link href="{{ asset('js/trumbowyg/ui/trumbowyg.min.css') }}" rel="stylesheet">
@endpush
@push('custom-scripts')
    <script src="{{ asset('js/trumbowyg/trumbowyg.min.js') }}"></script>

    <script>

        const commentEditor = $( '#commentEditor' );

        commentEditor.trumbowyg({
            btns: [['undo', 'redo'], ['strong', 'em', 'del'], ['link'], ['unorderedList', 'orderedList']],
            autogrowOnEnter: true,
            minimalLinks: true
        })
            .on( 'tbwchange' , function() {
                console.log( commentEditor.trumbowyg('html') );
                @this.content = commentEditor.trumbowyg('html');
            });

        document.addEventListener('livewire:load', () => {
            window.livewire.on('commentSaved', event => {
                commentEditor.trumbowyg('empty');
            })
        });

    </script>

@endpush
