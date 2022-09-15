<div>
    <a wire:click="setActiveContact({{ $contact->id }})" class="list-group-item list-group-item-action @if($contact->id === $activeContact->id) bg-dark text-white @endif rounded-0">
        <div class="media">
            <div class="media-body">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <h6 class="mb-0">{{ $contact->firstname }} {{ $contact->lastname }}</h6>
                    @if($latestMessage)
                        <div>
                            <small class="small font-weight-bold">{{ \Carbon\Carbon::parse($latestMessage->created_at)->format('d M') }}</small>
                            @if($unreadFrom > 0)
                                <p class="small font-weight-bold bg-danger rounded-pill d-flex align-items-center justify-content-center text-white p-1">{{ $unreadFrom }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                @if($latestMessage)
                    <p class="font-italic text-muted mb-0 text-small">{{ Str::limit($latestMessage->message, 20) }}</p>
                @endif

            </div>
        </div>
    </a>
</div>
