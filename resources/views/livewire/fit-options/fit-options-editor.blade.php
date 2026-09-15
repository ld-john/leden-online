<div>
    {{-- Header and Top Controls --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">{{ ucfirst($fitType) }} Fit Options</h3>
            <p class="text-muted small mb-0">Organised by Vehicle Model</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="showArchive" wire:model.live="showArchived">
                <label class="form-check-label" for="showArchive">Show Archived</label>
            </div>
        </div>
    </div>
    {{-- Filter Bar --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Search Vehicle Model</label>
                    <input type="text" class="form-control" placeholder="e.g. Transit Custom..." wire:model.live.debounce.300ms="searchModel">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Search Option Name</label>
                    <input type="text" class="form-control" placeholder="e.g. Towbar, Rear Camera..." wire:model.live.debounce.300ms="searchOptionName">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Model Year</label>
                    <input type="text" class="form-control" placeholder="e.g. 2026.25" wire:model.live.debounce.300ms="filterModelYear">
                </div>
                @if($fitType === 'dealer')
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Filter Dealer</label>
                        <select class="form-select" wire:model.live="filterDealerId">
                            <option value="">All Dealers</option>
                            @foreach($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Accordion List --}}
    <div class="accordion" id="fitOptionsAccordion">
        @forelse($models as $model)
            @php $isOpen = in_array($model->id, $openModels); @endphp

            <div class="card shadow-sm mb-3">
                {{-- Accordion Header --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center cursor-pointer"
                     wire:click="toggleModel({{ $model->id }})"
                     style="cursor: pointer;">

                    <div class="d-flex align-items-center">
                        <i class="fa-solid {{ $isOpen ? 'fa-chevron-down' : 'fa-chevron-right' }} me-3 text-primary"></i>
                        <h5 class="mb-0 fw-bold">{{ $model->name }}</h5>
                        <span class="badge bg-secondary ms-3">
                            {{ $model->fit_options_count }} {{ Str::plural('Option', $model->fit_options_count) }}
                        </span>
                    </div>

                    <button
                            type="button"
                            wire:click="$dispatch('open-quick-add-fit-option', { modelId: {{ $model->id }} })"
                            class="btn btn-sm btn-outline-secondary"
                    >
                        <i class="fa-solid fa-plus me-1"></i> Add Option to {{ $model->name }}
                    </button>
                </div>

                {{-- Accordion Content (Visible when toggled) --}}
                @if($isOpen)
                    <div class="card-body border-top">

                        @php $groupedOptions = $model->grouped_fit_options; @endphp

                        {{-- Table for options --}}
                        @if($groupedOptions->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Option Name</th>
                                        <th>Model Year</th>
                                        @if($fitType === 'dealer')
                                            <th>Dealer</th>
                                        @endif
                                        <th>Price</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($groupedOptions as $group)
                                        <tr>

                                            <td>
                                                <span class="fw-bold d-block text-dark">{{ $group->option_name }}</span>
                                                <span class="text-muted extra-small">
                                                    {{ $group->total_count }} {{ Str::plural('variant', $group->total_count) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($group->model_years as $year)
                                                        <span class="badge bg-light text-dark border fw-normal">
                                                            {{ $year }}MY
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            @if($fitType === 'dealer')
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $group->dealer_names->join(', ') ?: 'N/A' }}
                                                    </small>
                                                </td>
                                            @endif
                                            <td>
                                                @if($group->has_price_range)
                                                    <span class="fw-bold text-success">
                                                        &pound;{{ number_format($group->min_price, 2) }} &ndash; &pound;{{ number_format($group->max_price, 2) }}
                                                    </span>
                                                @else
                                                    <span class="fw-bold text-success">
                                                        &pound;{{ number_format($group->min_price, 2) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                {{-- Merge Duplicates Quick Action --}}
                                                @if($group->has_duplicates)
                                                    <button
                                                            type="button"
                                                            wire:click="mergeDuplicates('{{ addslashes($group->option_name) }}', {{ $model->id }})"
                                                            wire:confirm="Are you sure you want to merge {{ $group->duplicate_count }} identical duplicate records for '{{ $group->option_name }}'?"
                                                            class="btn btn-sm btn-warning me-1"
                                                            title="Merge {{ $group->duplicate_count }} identical duplicate record(s)"
                                                    >
                                                        <i class="fa-solid fa-code-merge me-1"></i> Merge Duplicates ({{ $group->duplicate_count }})
                                                    </button>
                                                @endif
                                                <button
                                                        type="button"
                                                        wire:click="manageOptionGroup('{{ addslashes($group->option_name) }}', {{ $model->id }})"
                                                        class="btn btn-sm btn-outline-primary"
                                                >
                                                    <i class="fa-solid fa-layer-group me-1"></i> Manage ({{ $group->total_count }})
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                No {{ $fitType }} fit options found for {{ $model->name }}.
                            </div>
                        @endif

                    </div>
                @endif
            </div>
        @empty
            <div class="alert alert-info text-center py-4">
                No vehicle models found matching "<strong>{{ $searchModel }}</strong>".
            </div>
        @endforelse
    </div>

    {{-- Model Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted small">Showing {{ $models->firstItem() }} to {{ $models->lastItem() }} of {{ $models->total() }} vehicle models</p>
        <div>{{ $models->links() }}</div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editOptionModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Fit Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="saveEdit">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Option Name</label>
                            <input type="text" class="form-control" wire:model="edit_option_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model Year</label>
                            <input type="text" class="form-control" wire:model="edit_model_year" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (&pound;)</label>
                            <input type="text" class="form-control" wire:model="edit_price" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteOptionModal" tabindex="-1" aria-labelledby="deleteOptionModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteOptionModalLabel">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-4">
                    <p class="mb-2 fs-6 text-dark">
                        Are you sure you want to delete this {{ $fitType }} fit option?
                    </p>
                    <div class="alert alert-warning mb-0 small">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        <strong>Warning:</strong> This action is permanent and cannot be undone.
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger fw-bold" wire:click="deleteOption">
                        <i class="fa-solid fa-trash me-1"></i> Yes, Delete Option
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Group Management Modal --}}
    <div class="modal fade" id="manageGroupModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Manage <strong>{{ $activeGroupOptionName }}</strong>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Model Year</th>
                            <th>Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activeGroupItems as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td><span class="badge bg-secondary">{{ $item['model_year'] }}MY</span></td>
                                <td class="fw-bold text-success">&pound;{{ number_format($item['option_price'], 2) }}</td>
                                <td class="text-end">
                                    <button wire:click="openEditModal({{ $item['id'] }})" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $item['id'] }})" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const groupModalEl = document.getElementById('manageGroupModal');
            const groupModal = groupModalEl ? new bootstrap.Modal(groupModalEl) : null;

            Livewire.on('show-group-modal', () => groupModal?.show());
            Livewire.on('hide-group-modal', () => groupModal?.hide());
        });
    </script>

    <livewire:quick-add-fit-option :fit-type="$fitType" />

    {{-- Modal JS Listener --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Initialize Modal Instances
            const editModalEl = document.getElementById('editOptionModal');
            const deleteModalEl = document.getElementById('deleteOptionModal');

            const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
            const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

            // Edit Modal Events
            Livewire.on('show-edit-modal', () => editModal?.show());
            Livewire.on('hide-edit-modal', () => editModal?.hide());

            // Delete Modal Events
            Livewire.on('show-delete-modal', () => deleteModal?.show());
            Livewire.on('hide-delete-modal', () => deleteModal?.hide());
        });
    </script>
</div>
