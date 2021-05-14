<div class="form-group row">
    <label class="col-md-2 col-form-label" for="comments">Comments</label>
    <div class="col-md-6">
        <textarea wire:model="comments" name="comments" id="comments" class="form-control" rows="4"></textarea>
    </div>
</div>
<div class="row">
    <label class="col-md-2 col-form-label" for="file">Upload Document(s)<br>
        <small>Allowed file types - JPEG, PNG, PDF, DOC & DOCX</small>
    </label>
    <div class="col-md-6">
        @for($i = 0; $i < $fields; $i++)
            <input wire:model="attachments"
                   type="file"
                   accept=".pdf, applicaion/pdf, image/png, .png, image/jpg, .jpg, image/jpeg, .jpeg, .doc, .docx, application/msword"
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