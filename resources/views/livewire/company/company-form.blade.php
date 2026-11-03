<form wire:submit="save" method="POST" enctype="multipart/form-data">
    <div class="card shadow mb-4">
        @csrf
        <!-- Vehicle Information -->
        <x-ui.section-header>Company Information</x-ui.section-header>
        <div class="card-body">
            <x-inputs.text label="Company Name" name="form.company_name" required />
            <x-inputs.select label="Company Type" name="form.company_type" :options="$companyTypes" required />
            <x-inputs.text label="Address 1" name="form.company_address1" required />
            <x-inputs.text label="Address 2" name="form.company_address2" />
            <x-inputs.text label="City" name="form.company_city" required />
            <x-inputs.text label="County" name="form.company_county" />
            <x-inputs.text label="Country" name="form.company_country" />
            <x-inputs.text label="Postcode" name="form.company_postcode" required />
            <x-inputs.text label="Company Email" name="form.company_email" required />
            <x-inputs.text label="Handover Email" name="form.handover_email" />
            <x-inputs.text label="Delivery Email" name="form.delivery_email" />
            <x-inputs.text label="Company Phone" name="form.company_phone"  />
            <x-inputs.boolean label="Fleet Procure Member" name="form.fleet_procure_member" />
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('company_manager') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Add Company</button>
        </div>
    </div>
</form>

