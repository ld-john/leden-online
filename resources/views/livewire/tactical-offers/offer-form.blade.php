<form wire:submit="offerFormSubmit" method="POST" enctype="multipart/form-data">
    <div class="card shadow mb-4">
        <x-ui.section-header>Offer Information</x-ui.section-header>
        <div class="card-body">
            <x-inputs.text label="Name" name="offer_name" />
            <x-inputs.currency label="Offer Value" name="offer_value" />
            <x-inputs.select label="Make" name="make" :options="$manufacturers" add-url="{{ route('meta.make.index') }}" required />
            <x-inputs.select label="Model" name="model" :options="$vehicle_models" required />
            <x-inputs.date label="Start Date" name="offer_start_date" />
            <x-inputs.date label="End End" name="offer_end_date" />
        </div>
    </div>
    <div class="my-4 text-right">
        <button class="btn btn-primary" id="goButton" type="submit">Save Offer</button>
    </div>
</form>
