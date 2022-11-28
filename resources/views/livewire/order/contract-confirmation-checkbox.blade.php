<div class="d-grid gap-2 mt-2">
    @if($contract)
        <button wire:click="toggleContractConfirmation" data-toggle="tooltip" title="Contract Confirmation received" class="btn btn-success" @if(!in_array('manages-deliveries', $userPermissions)) disabled @endif><i class="fa-solid fa-file-signature"></i></button>
    @else
        <button wire:click="toggleContractConfirmation" data-toggle="tooltip" title="Contract Confirmation not received" class="btn btn-danger text-white" @if(!in_array('manages-deliveries', $userPermissions)) disabled @endif><i class="fa-solid fa-file-signature"></i></button>
    @endif
</div>
