<?php

namespace App\Http\Livewire\FitOptions;

use App\Models\Company;
use App\Models\FitOption;
use App\Models\VehicleModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class FitOptionsEditor extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $fitType = 'factory';
    public string $searchModel = '';
    public string $searchOptionName = '';
    public string $filterModelYear = '';
    public ?int $filterDealerId = null;
    public bool $showArchived = false;

    public array $openModels = [];
    public ?int $editingOptionId = null;
    public string $edit_option_name = '';
    public string $edit_model_year = '';
    public string $edit_price = '';
    public ?int $edit_dealer_id = null;
    public ?int $deletingOptionId = null;
    public string $activeGroupOptionName = '';
    public array $activeGroupItems = [];

    public function toggleModel(int $modelId) :void
    {
        if (in_array($modelId, $this->openModels)) {
            $this->openModels = array_diff($this->openModels, [$modelId]);
        } else {
            $this->openModels[] = $modelId;
        }
    }

    public function openEditModal(int $optionId): void
    {
        $option = FitOption::findOrFail($optionId);

        $this->editingOptionId = $option->id;
        $this->edit_option_name = $option->option_name;
        $this->edit_model_year = $option->model_year;
        $this->edit_price = (string) $option->option_price;
        $this->edit_dealer_id = $option->dealer;

        $this->dispatch('show-edit-modal');
    }

    public function saveEdit():void
    {
        $this->validate([
            'edit_option_name' => 'required|string|max:255',
            'edit_model_year' => 'required|string',
            'edit_price' => 'required|numeric'
        ]);

        if ($this->editingOptionId) {
            $option = FitOption::findOrFail($this->editingOptionId);
            $option->update([
                'option_name' => $this->edit_option_name,
                'model_year' => $this->edit_model_year,
                'option_price' => $this->edit_price,
                'dealer_id' => $this->edit_dealer_id
            ]);
        }

        $this->editingOptionId = null;
        $this->dispatch('hide-edit-modal');
    }

    public function confirmDelete(int $optionId):void
    {
        $this->deletingOptionId = $optionId;
        $this->dispatch('show-delete-modal');
    }

    public function deleteOption():void
    {
        if ($this->deletingOptionId){
            FitOption::destroy($this->deletingOptionId);
        }
        $this->deletingOptionId = null;
        $this->dispatch('hide_delete-modal');
    }

    public function manageOptionGroup(string $optionName, int $modelId): void
    {
        $this->activeGroupOptionName = $optionName;

        $this->activeGroupItems = FitOption::query()
            ->where('model', $modelId)
            ->where('option_name', $optionName)
            ->when($this->fitType === 'dealer', fn ($q) => $q->whereNotNull('dealer_id'))
            ->when($this->fitType === 'factory', fn ($q) => $q->whereNull('dealer_id'))
            ->get()
            ->toArray();

        $this->dispatch('show-group-modal');
    }

    #[On('fit-option-created')]
    public function refreshOptionsList(): void
    {
        // Livewire will automatically trigger render() and reload the options table
    }

    public function mergeDuplicates(string $optionName, int $modelId): void
    {
        DB::transaction(function () use ($optionName, $modelId) {
            // Fetch all fit options matching this name & model
            $options = FitOption::query()
                ->where('model', $modelId)
                ->where('option_name', $optionName)
                ->when($this->fitType === 'dealer', fn ($q) => $q->whereNotNull('dealer_id'))
                ->when($this->fitType === 'factory', fn ($q) => $q->whereNull('dealer_id'))
                ->get();

            // Group by exact key matching identical properties
            $grouped = $options->groupBy(function ($item) {
                return $item->model_year . '|' . (float) $item->option_price . '|' . $item->dealer;
            });

            $deletedTotal = 0;

            foreach ($grouped as $duplicateSet) {
                if ($duplicateSet->count() > 1) {
                    // Keep the first item as the master record
                    $master = $duplicateSet->first();
                    $duplicates = $duplicateSet->slice(1);
                    $duplicateIds = $duplicates->pluck('id')->toArray();

                    // If pivot records exist on vehicles, update them to point to $master->id
                    if (DB::getSchemaBuilder()->hasTable('vehicle_fit_option')) {
                        DB::table('vehicle_fit_option')
                            ->whereIn('fit_option_id', $duplicateIds)
                            ->update(['fit_option_id' => $master->id]);
                    }

                    // Delete redundant rows
                    FitOption::whereIn('id', $duplicateIds)->delete();
                    $deletedTotal += count($duplicateIds);
                }
            }

            // Refresh group items if group modal is active
            if ($this->activeGroupOptionName === $optionName) {
                $this->manageOptionGroup($optionName, $modelId);
            }

            session()->flash('message', "Successfully merged and removed {$deletedTotal} duplicate option(s).");
        });
    }

    public function render(): Factory|View|Application
    {
        $models = VehicleModel::query()->when($this->searchModel, fn($q) => $q->where('name', 'like', "%{$this->searchModel}%"))
            ->with(['fitOptions' => function ($query) {
            $query->when($this->fitType === 'dealer', fn ($q) => $q->whereNotNull('dealer_id'))
                ->when($this->fitType === 'factory', fn ($q) => $q->whereNull('dealer_id'))
                ->when($this->searchOptionName, fn ($q) => $q->where('option_name', 'like', "%{$this->searchOptionName}%"))
                ->when($this->filterModelYear, fn ($q) => $q->where('model_year', $this->filterModelYear))
                ->when($this->filterDealerId, fn ($q) => $q->where('dealer_id', $this->filterDealerId))
                ->when(! $this->showArchived, fn ($q) => $q->whereNull('archived_at'));
        }])
            ->withCount(['fitOptions' => function ($query) {
                $query->when($this->fitType === 'dealer', fn ($q) => $q->whereNotNull('dealer_id'))
                    ->when($this->fitType === 'factory', fn ($q) => $q->whereNull('dealer_id'))
                    ->when(! $this->showArchived, fn ($q) => $q->whereNull('archived_at'));
            }])
            ->orderByDesc('fit_options_count')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.fit-options.fit-options-editor', [
            'models' => $models,
            'dealers' => $this->fitType === 'dealer' ? Company::orderBy('company_name')->where('company_type', 'dealer')->get() : collect(),
        ]);
    }
}
