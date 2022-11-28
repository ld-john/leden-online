<div class="d-inline-block">
     <i class="fas fa-trash text-white" wire:click="toggleDeleteModal"></i>
     <div class="modal @if($modalShow) show @endif">
          <div class="modal-dialog" role="document">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="deleteModalLabel">Delete Vehicle - {{ $vehicle->id }}</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDeleteModal">
                              <span aria-hidden="true">&times;</span>
                         </button>
                    </div>
                    <div class="modal-body">
                         Deleting {{ $vehicle->manufacturer->name ?? '' }} {{ $vehicle->model ?? '' }} @if($vehicle->orbit_number) - Orbit Number: {{ $vehicle->orbit_number }} @endif - Are you sure you wish to continue?
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">Close</button>
                         <button type="button" class="btn btn-danger" wire:click="deleteVehicle">Delete</button>
                    </div>
               </div>
          </div>
     </div>
</div>
