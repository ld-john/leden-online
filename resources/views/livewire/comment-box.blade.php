<div>
    <div class="px-3">
    <h4 class="px-4 pt-3 pb-2">Add a new comment</h4>


        <div id="commentEditorContainer" wire:ignore>
            <div id="commentEditor"></div>
        </div>
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
        <h5 class="my-4 mx-3">There are no comments yet.</h5>
    @endforelse

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
