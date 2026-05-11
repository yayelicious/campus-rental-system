<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminMarketplace extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public ?int $pendingRemovalItemId = null;

    public string $pendingRemovalItemName = '';

    public string $removalReason = '';

    public string $removalDetails = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function confirmRemoveItem(int $itemId): void
    {
        $item = Item::query()->with('user')->findOrFail($itemId);

        $this->pendingRemovalItemId = $item->id;
        $this->pendingRemovalItemName = $item->name;
        $this->removalReason = '';
        $this->removalDetails = '';
        $this->resetValidation();
    }

    public function cancelRemoveItem(): void
    {
        $this->reset(['pendingRemovalItemId', 'pendingRemovalItemName', 'removalReason', 'removalDetails']);
        $this->resetValidation();
    }

    public function removeItem(): void
    {
        abort_unless(Auth::user()?->isAdministrator(), 403);

        $validated = $this->validate([
            'pendingRemovalItemId' => ['required', 'integer', 'exists:items,id'],
            'removalReason' => ['required', 'string', 'max:150'],
            'removalDetails' => ['nullable', 'string', 'max:1000'],
        ]);

        $item = Item::query()->findOrFail((int) $validated['pendingRemovalItemId']);

        DB::transaction(function () use ($item, $validated): void {
            $item->forceFill([
                'admin_removed_by' => Auth::id(),
                'admin_removal_reason' => trim($validated['removalReason']),
                'admin_removal_details' => trim((string) $validated['removalDetails']) ?: null,
                'admin_removed_at' => now(),
            ])->save();

            $item->delete();
        });

        $this->cancelRemoveItem();
        $this->resetPage();

        session()->flash('message', 'Item removed from the marketplace.');
    }

    public function render(): View
    {
        $itemsQuery = Item::query()
            ->with(['user', 'categoryRecord'])
            ->withCount('rentals')
            ->when($this->search !== '', function (Builder $query): void {
                $search = '%'.trim($this->search).'%';

                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereHas('user', fn (Builder $userQuery) => $userQuery->where('name', 'like', $search)->orWhere('email', 'like', $search));
                });
            })
            ->when(in_array($this->status, ['available', 'rented', 'maintenance'], true), fn (Builder $query) => $query->where('status', $this->status))
            ->latest();

        return view('livewire.admin-marketplace', [
            'items' => $itemsQuery->paginate(12),
        ]);
    }
}
