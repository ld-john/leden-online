<div class="row">
    <label class="col-md-2 col-form-label" for="file">Upload Document(s)<br>
        <small>Allowed file types - JPEG, PNG, PDF, DOC & DOCX</small>
    </label>
    <div class="col-md-6">
        @for($i = 0; $i < $fields; $i++)
            <input wire:model.live="attachments"
                   type="file"
                   class="form-control"
                   accept=".pdf, application/pdf, image/png, .png, image/jpg, .jpg, image/jpeg, .jpeg, .doc, .docx, application/msword"
                   name="file"
                   id="file"/>
        @endfor
    </div>
    <div class="form-group">
        <button class="btn btn-primary" wire:click.prevent="handleAddField">Add New File</button>
    </div>
</div>
<div wire:loading wire:target="attachments">Uploading...</div>
@error('attachments.*') <div class="alert alert-danger">{{ $message }}</div>@enderror
<ul>
    @foreach($attachments as $key => $attachment)
        <li>{{$attachment->getClientOriginalName()}} <button wire:click.prevent="removeAttachment({{$key}})">Delete</button></li>
    @endforeach
</ul>
{{-- FIN Number --}}
<div class="form-group row">
    <label for="fin_number" class="col-md-2 col-form-label">FIN</label>
    <div class="col-md-6">
        <input wire:model.live="fin_number" type="text" name="fin_number" id="fin_number"
               class="form-control" autocomplete="off" />
    </div>
</div>
{{-- Deal Number --}}
<div class="form-group row">
    <label for="deal_number" class="col-md-2 col-form-label">Deal Number</label>
    <div class="col-md-6">
        <input wire:model.live="deal_number" type="text" name="deal_number" id="deal_number"
               class="form-control" autocomplete="off" />
    </div>
</div>

{{-- Exception --}}
<div class="form-group row">
    <label for="exception" class="col-md-2 col-form-label">Exception to Leden Terms</label>
    <div class="col-md-6 form-check form-switch">
        <input wire:model.live="exclusion" class="form-check-input" type="checkbox" id="exception">
    </div>
</div>
