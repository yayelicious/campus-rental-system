<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Rental;
use App\Models\Report;
use App\Notifications\RentalRequestedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ViewItem extends Component
{
    use WithFileUploads;

    public $item;

    public $isEditing = false;

    public $name;

    public $description;

    public $price;

    public $condition;

    public $status;

    public $image;

    public $startDate = '';

    public $endDate = '';

    public $additionalNotes = '';

    public bool $showReportForm = false;

    public string $reportType = '';

    public string $reportReason = '';

    public string $reportDetails = '';

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'required|string',
        'condition' => 'required|string',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:available,rented,maintenance',
        'image' => 'nullable|image|max:25600',
    ];

    protected function syncFormFields(): void
    {
        $this->name = $this->item->name;
        $this->description = $this->item->description;
        $this->price = $this->item->price;
        $this->condition = $this->item->condition;
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
        abort_unless(Auth::check(), 403);

        if ($this->item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'condition' => $this->condition,
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

    public function requestRental(): void
    {
        abort_unless(Auth::check(), 403);

        if ($this->item->user_id === Auth::id()) {
            session()->flash('message', 'You cannot request your own item.');

            return;
        }

        if ($this->item->status !== 'available') {
            session()->flash('message', 'This item is currently unavailable.');

            return;
        }

        $validated = $this->validate([
            'startDate' => ['required', 'date', 'after_or_equal:today'],
            'endDate' => ['required', 'date', 'after:startDate'],
            'additionalNotes' => ['nullable', 'string', 'max:500'],
        ]);

        $existingRequest = $this->item->rentals()
            ->where('renter_id', Auth::id())
            ->whereIn('status', [Rental::STATUS_PENDING, Rental::STATUS_ACTIVE])
            ->exists();

        if ($existingRequest) {
            session()->flash('message', 'You already have an active or pending request for this item.');

            return;
        }

        $start = Carbon::parse($validated['startDate']);
        $end = Carbon::parse($validated['endDate']);
        $seconds = $start->diffInSeconds($end, false);
        $days = max(1, (int) ceil($seconds / 86400));
        $totalPrice = $days * (float) $this->item->price;

        $rental = $this->item->rentals()->create([
            'renter_id' => Auth::id(),
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $totalPrice,
            'payment_status' => Rental::PAYMENT_STATUS_OUTSTANDING,
            'status' => Rental::STATUS_PENDING,
        ]);

        $owner = $this->item->user;

        $owner->notify(new RentalRequestedNotification(
            itemId: $this->item->id,
            itemName: $this->item->name,
            renterId: Auth::id(),
            renterName: Auth::user()->name,
            startDate: $start->toDateString(),
            endDate: $end->toDateString(),
            totalPrice: $totalPrice,
            rentalId: $rental->id,
            additionalNotes: trim((string) $this->additionalNotes),
        ));

        $this->reset(['startDate', 'endDate', 'additionalNotes']);
        session()->flash('message', 'Rental request sent successfully!');
    }

    public function openReportForm(string $type): void
    {
        abort_unless(Auth::check(), 403);
        abort_unless(in_array($type, [Report::TYPE_ITEM, Report::TYPE_USER], true), 404);

        if ($this->item->user_id === Auth::id()) {
            session()->flash('message', 'You cannot report your own listing or account from this page.');

            return;
        }

        $this->resetValidation();
        $this->reportType = $type;
        $this->reportReason = '';
        $this->reportDetails = '';
        $this->showReportForm = true;
    }

    public function cancelReport(): void
    {
        $this->showReportForm = false;
        $this->reportType = '';
        $this->reportReason = '';
        $this->reportDetails = '';
        $this->resetValidation();
    }

    public function submitReport(): void
    {
        abort_unless(Auth::check(), 403);
        abort_unless(in_array($this->reportType, [Report::TYPE_ITEM, Report::TYPE_USER], true), 404);

        if ($this->item->user_id === Auth::id()) {
            abort(403, 'You cannot report yourself.');
        }

        $validated = $this->validate([
            'reportReason' => ['required', 'string', 'max:120'],
            'reportDetails' => ['nullable', 'string', 'max:1000'],
        ]);

        Report::query()->create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $this->item->user_id,
            'reported_item_id' => $this->reportType === Report::TYPE_ITEM ? $this->item->id : null,
            'type' => $this->reportType,
            'reason' => trim($validated['reportReason']),
            'details' => trim((string) $validated['reportDetails']) ?: null,
        ]);

        $this->cancelReport();
        session()->flash('message', 'Report submitted. An admin will verify it.');
    }

    public function render(): mixed
    {
        abort_unless(Auth::check(), 403);

        $isOwner = $this->item->user_id === Auth::id();
        $activeRental = $this->item->rentals->firstWhere('status', 'active');

        return view('livewire.view-item', [
            'isOwner' => $isOwner,
            'activeRental' => $activeRental,
        ]);
    }
}
