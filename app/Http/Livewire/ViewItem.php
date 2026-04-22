<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ViewItem extends Component
{
    use WithFileUploads;

    public $item;
    public $isEditing = false;
    public $name, $description, $price, $status, $image;

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:available,rented,maintenance',
        'image' => 'nullable|image|max:1024',
    ];

    protected function syncFormFields(): void
    {
        $this->name = $this->item->name;
        $this->description = $this->item->description;
        $this->price = $this->item->price;
        $this->status = $this->item->status;
        $this->image = null;
    }

    public function mount($id)
    {
        $this->item = Item::with('user', 'rentals.renter')->findOrFail($id);
        $this->syncFormFields();
    }

    public function toggleEdit()
    {
        $this->isEditing = ! $this->isEditing;

        if (! $this->isEditing) {
            $this->item->refresh()->load('user', 'rentals.renter');
            $this->syncFormFields();
            $this->resetValidation();
        }
    }

    public function updateItem()
    {
        abort_unless(auth()->check(), 403);

        if ($this->item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
        ];

        if ($this->image) {
            if ($this->item->image_path) {
                Storage::disk('public')->delete($this->item->image_path);
            }

            $data['image_path'] = $this->image->store('item-photos', 'public');
        }

        $this->item->update($data);
        $this->item->refresh()->load('user', 'rentals.renter');
        $this->syncFormFields();

        session()->flash('message', 'Item updated successfully!');
        $this->isEditing = false;
    }

    public function render()
    {
        abort_unless(auth()->check(), 403);

        $isOwner = $this->item->user_id === auth()->id();
        $activeRental = $this->item->rentals->firstWhere('status', 'active');

        return view('livewire.view-item', [
            'isOwner' => $isOwner,
            'activeRental' => $activeRental,
        ])->layout('layouts.app');
    }
}
