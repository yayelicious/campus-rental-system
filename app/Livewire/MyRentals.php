<?php

namespace App\Livewire;

use App\Models\Rental;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class MyRentals extends Component
{
    use WithPagination;

    public function render()
    {
        abort_unless(Auth::check(), 403);

        $rentals = Rental::where('renter_id', Auth::id())
            ->with('item.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.my-rentals', [
            'rentals' => $rentals,
        ]);
    }
}
