<?php

namespace App\Livewire;

use App\Models\Rental;
use Livewire\Component;
use Livewire\WithPagination;

class MyRentals extends Component
{
    use WithPagination;

    public function render()
    {
        abort_unless(auth()->check(), 403);

        $rentals = Rental::where('renter_id', auth()->id())
            ->with('item.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.my-rentals', [
            'rentals' => $rentals,
        ])->layout('layouts.app');
    }
}
