<div>

     <li class="nav-item dropdown no-arrow mx-3">
          <button
                  type="button"
                  class="btn btn-outline-primary text-white position-relative nav-link dropdown-toggle"
                  id="alertsDropdown"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  wire:click="toggleMenu"
          >
               <i class="fa-solid fa-bell"></i>
               @if ($unread->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                         {{ $unread->count() > 9 ? '9+' : $unread->count() }}
                         <span class="visually-hidden">unread notifications</span>
                    </span>
               @endif
          </button>
          <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in-dropdown @if($show) show @endif" aria-labelledby="alertsDropdown">
               <h6 class="dropdown-header">
                    Notifications Center
               </h6>

               @forelse($unread->take(4) as $notification)
                    <div class="dropdown-item d-flex align-items-center text-gray-500">
                         <div class="me-3">
                              <div class="icon-circle blue-background text-white">
                                   @if ($notification->data['type'] === 'vehicle')
                                        <i class="fa-solid fa-car"></i>
                                   @else
                                        <i class="fa-solid fa-flag"></i>
                                   @endif
                              </div>
                         </div>
                         <div>
                              <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                              <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                         </div>
                    </div>
               @empty
                    <p class="text-center text-gray-500 px-4">No Unread Notifications</p>
               @endforelse
               <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.read') }}">Mark all as read</a>
               <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications') }}">View All Notifications</a>
          </div>
     </li>
</div>
