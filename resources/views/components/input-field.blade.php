<div {{ $attributes->class(['form-group has-feedback']) }}>

    <input id="{{$fieldname}}" type="{{$fieldtype}}" placeholder="{{ $fieldplaceholder  }}" class="form-control @error($fieldname) is-invalid @enderror"
           name="{{$fieldname}}" value="{{ old($fieldname) }}" autocomplete="{{$fieldname}}">
    @if(isset($fieldicon))
        <span class="fas {{$fieldicon}} form-icon"></span>
    @endif
    @error($fieldname)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>