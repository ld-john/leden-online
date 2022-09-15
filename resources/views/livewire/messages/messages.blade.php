<div>
     <div class="row">
          <div class="col-sm-7">
               <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                         <h6 class="m-0 font-weight-bold text-l-blue">All Messages</h6>
                    </div>
                    <div class="px-4 py-5 messages-box bg-white" id="messages">
                         @forelse($this->messages as $message)
                              @if($message->sender_id === Auth::user()->id)
                                   <!-- Sender Message-->
                                   <div class="media w-50 mb-3">
                                        <div class="media-body ms-3">
                                             <div class="bg-light rounded py-2 px-3 mb-2">
                                                  <p class="text-small mb-0 text-muted">{{$message->message}}</p>
                                             </div>
                                             <p class="small text-muted">{{ \Carbon\Carbon::parse($message->created_at)->format('g:i A | M d') }}</p>
                                        </div>
                                   </div>
                              @else
                                   <!-- Receiver Message-->
                                   <div class="media w-50 ms-auto mb-3">
                                        <div class="media-body">
                                             <div class="bg-primary rounded py-2 px-3 mb-2">
                                                  <p class="text-small mb-0 text-white">{{$message->message}}</p>
                                             </div>
                                             <p class="small text-muted">{{ \Carbon\Carbon::parse($message->created_at)->format('g:i A | M d') }}</p>
                                        </div>
                                   </div>
                              @endif
                         @empty
                              No Messages found
                         @endforelse
                    </div>
                    <!-- Typing area -->
                    <form class="bg-light">
                         <div class="input-group">
                              <input wire:model.lazy="messageContent" type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
                              <div class="input-group-text">
                                   <button wire:click.prevent="sendMessage" id="button-addon2" type="submit" class="btn btn-link"> <i class="fa-solid fa-paper-plane"></i></button>
                              </div>
                         </div>
                    </form>
               </div>
          </div>
          <div class="col">
               <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                         <h6 class="m-0 font-weight-bold text-l-blue">Contacts</h6>
                    </div>
                    <div class="contact-box">
                         <div class="list-group rounded-0">
                              @forelse($this->contacts as $contact)
                                   <livewire:messages.contact :user="$contact['id']" :active-contact="$activeContact" key="{{ 'child-component-' . now() . $contact['id'] }}" />
                              @empty
                              @endforelse
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
