@component('mail::message')
# OTR Requested

{{ $user->fullName }} from {{ $user->company?->company_name }} has requested an OTR for {{ $vehicle->niceName() }} with an ID #{{ $vehicle->id }}.

@component('mail::button', ['url' => 'mailto:' . $user->email ])
Respond here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
