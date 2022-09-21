<div>
     <div class="row">
          <div class="col-sm-7">
               <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                         <h6 class="m-0 font-weight-bold text-l-blue">All Messages</h6>
                    </div>
                    <div class="px-4 py-5 messages-box bg-white">
                         <!-- Sender Message-->
                         <div class="media w-50 mb-3">
                              <div class="media-body ml-3">
                                   <div class="bg-light rounded py-2 px-3 mb-2">
                                        <p class="text-small mb-0 text-muted">Test which is a new approach all solutions</p>
                                   </div>
                                   <p class="small text-muted">12:00 PM | Aug 13</p>
                              </div>
                         </div>

                         <!-- Receiver Message-->
                         <div class="media w-50 ml-auto mb-3">
                              <div class="media-body">
                                   <div class="bg-primary rounded py-2 px-3 mb-2">
                                        <p class="text-small mb-0 text-white">Test which is a new approach to have all solutions</p>
                                   </div>
                                   <p class="small text-muted">12:00 PM | Aug 13</p>
                              </div>
                         </div>

                    </div>
                    <!-- Typing area -->
                    <form action="#" class="bg-light">
                         <div class="input-group">
                              <input type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
                              <div class="input-group-append">
                                   <button id="button-addon2" type="submit" class="btn btn-link"> <i class="fa-solid fa-paper-plane"></i></button>
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
                                   <a class="list-group-item list-group-item-action rounded-0">
                                        <div class="media">
                                             <div class="media-body">
                                                  <div class="d-flex align-items-center justify-content-between mb-1">
                                                       <h6 class="mb-0">{{ $contact->firstname }} {{ $contact->lastname }}</h6>
                                                       <small class="small font-weight-bold">25 Dec</small>
                                                  </div>
                                                  <p class="font-italic text-muted mb-0 text-small">consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
                                             </div>
                                        </div>
                                   </a>
                              @empty
                              @endforelse
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
