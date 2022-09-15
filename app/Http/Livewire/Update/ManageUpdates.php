<?php

namespace App\Http\Livewire\Update;

use App\Updates;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

class ManageUpdates extends Component
{
    use WithFileUploads;

    public $text;
    public $target = 'broker';
    public $updates;
    public $updates_bin;
    public $banners;
    public $banners_bin;
    public $type = 'update';
    public $heading;
    public $image;

    public function mount()
    {
        $this->updates = Updates::where('update_type', '=', 'update')->get();
        $this->updates_bin = Updates::where('update_type', '=', 'update')
            ->onlyTrashed()
            ->get();
        $this->banners = Updates::where('update_type', '=', 'promo')->get();
        $this->banners_bin = Updates::where('update_type', '=', 'promo')
            ->onlyTrashed()
            ->get();
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function saveUpdate()
    {
        $validate = $this->validate([
            'text' => 'required',
        ]);
        $update = new Updates();
        $update->update_text = $validate['text'];
        $update->dashboard = $this->target;
        $update->save();
        notify()->success('News Update Created Successfully', 'Update Created');
        return redirect(request()->header('Referer'));
    }

    public function saveBanner()
    {
        $validate = $this->validate([
            'heading' => 'required',
            'image' => 'required|image|dimensions:height=500,width=1600',
        ]);
        $validate['image'] = $this->image->store('banners');
        $update = new Updates();
        $update->header = $validate['heading'];
        $update->update_text = $this->text;
        $update->image = $validate['image'];
        $update->update_type = 'promo';
        $update->save();
        notify()->success(
            'Promotional Banner Created Successfully',
            'Update Created',
        );
        return redirect(request()->header('Referer'));
    }

    public function deleteUpdate(Updates $updates)
    {
        $updates->delete();
        notify()->success('Update moved to recycle bin', 'Updated Deleted');
        return redirect(request()->header('Referer'));
    }

    public function restoreUpdate($updates)
    {
        Updates::withTrashed()
            ->where('id', $updates)
            ->restore();
        notify()->success('Update has been restored', 'Update Restored');
        return redirect()->route('updates.create');
    }

    public function destroyUpdate($id)
    {
        $updates = Updates::withTrashed()
            ->where('id', $id)
            ->first();

        if ($updates->image) {
            if (Storage::exists($updates->image)) {
                Storage::delete($updates->image);
            }
        }

        $updates->forceDelete();
        notify()->success(
            'Update has been deleted permanently',
            'Update Deleted',
        );
        return redirect()->route('updates.create');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.update.manage-updates');
    }
}
