<div>
     <form wire:submit.prevent="executeCsvUpload" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card shadow mb-4">
               <!-- Card Header -->
               <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-l-blue">Import CSV</h6>
                    <a href="{{ asset('user-uploads/csv-uploads/csv_order_upload_template.csv') }}" download class="btn btn-sm btn-info"><i class="fa-solid fa-download"></i> Download CSV Template</a>
               </div>
               <!-- Card Body -->
               <div class="card-body">
                    <div class="row mb-5">
                         <div class="col-md-12">
                              <p>
                                   Use the below form to upload, edit or delete vehicles in bulk.
                              </p>
                              <p>
                                   You can download a template file using the button above.
                              </p>
                         </div>
                    </div>
                    <div class="form-group row">
                         <label class="col-md-2 col-form-label" for="upload_type">Type of upload</label>
                         <div class="col-md-6">
                              <select wire:model="upload_type" name="upload_type" class="form-control">
                                   <option value="">Select an upload type</option>
                                   <option value="ford_create">Ford Report</option>
                                   <option value="create">Create Vehicles</option>
                                   <option value="create_rf_stock">Create Ring Fenced Stock</option>
                                   <option value="delete">Delete Vehicles</option>
                              </select>
                              @error('upload_type')
                              <div class="input-group-prepend">
                                   <div class="alert alert-danger my-3">{!! $message !!} </div>
                              </div>
                              @enderror
                         </div>
                    </div>

                    <div class="form-group row">
                         <label class="col-md-2 col-form-label" for="file">Upload Document<br>
                              <small>Allowed file types - CSV<br>Max file size - 1MB</small>
                         </label>
                         <div class="col-md-6">

                              <input wire:model="upload" type="file" accept=".csv" name="file" id="file" />
                              @error('file')
                              <div class="input-group-prepend">
                                   <div class="alert alert-danger my-3">{!! $message !!} </div>
                              </div>
                              @enderror
                         </div>
                    </div>
                    @if($upload_type === 'create')
                    <div class="form-group row">
                         <label class="col-md-2 col-form-label" for="show_in_ford_pipeline">Show in Ford Pipeline?</label>
                         <div class="col-md-6">
                              <select wire:model="showInFordPipeline" name="show_in_ford_pipeline" class="form-control">
                                   <option value="0">No</option>
                                   <option value="1">Yes</option>
                              </select>
                              @error('show_in_ford_pipeline')
                              <div class="input-group-prepend">
                                   <div class="alert alert-danger my-3">{!! $message !!} </div>
                              </div>
                              @enderror
                         </div>
                    </div>
                    @endif
                    @if($upload_type === 'create_rf_stock')
                         <div class="form-group row">
                              <label class="col-md-2 col-form-label" for="show_in_ford_pipeline">Which Broker?</label>
                              <div class="col-md-6 select2">
                                   <select wire:model="broker" name="broker" class="form-control">
                                        @foreach($brokers as $broker)
                                             <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                                        @endforeach
                                   </select>
                                   @error('broker')
                                   <div class="input-group-prepend">
                                        <div class="alert alert-danger my-3">{!! $message !!} </div>
                                   </div>
                                   @enderror
                              </div>
                         </div>
                    @endif
               </div>
               <!-- Card Footer -->
               <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">Upload CSV</button>
               </div>
          </div>
     </form>
</div>
