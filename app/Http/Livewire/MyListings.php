<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class MyListings extends Component
{
    use WithPagination;

    public function deleteItem($itemId)
    {
        abort_unless(auth()->check(), 403);

        $item = Item::where('user_id', auth()->id())->findOrFail($itemId);

        $item->delete();

        $this->resetPage();

        session()->flash('message', 'Item deleted successfully.');
    }

    public function render()
    {
        abort_unless(auth()->check(), 403);

        $items = Item::where('user_id', auth()->id())
            ->withCount('rentals')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.my-listings', [
            'items' => $items,
        ])->layout('layouts.app');
    }
}
