<?php

namespace Tests\Feature;

use App\Livewire\ViewItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Notifications\RentalRequestedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ViewItemRentalValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_rental_rejects_past_start_date(): void
    {
        [, $renter, $item] = $this->createItemScenario();

        $this->actingAs($renter);

        Livewire::test(ViewItem::class, ['id' => $item->id])
            ->set('startDate', now()->subDay()->toDateString())
            ->set('endDate', now()->addDay()->toDateString())
            ->call('requestRental')
            ->assertHasErrors(['startDate' => ['after_or_equal']]);
    }

    public function test_request_rental_rejects_end_date_before_start_date(): void
    {
        [, $renter, $item] = $this->createItemScenario();

        $this->actingAs($renter);

        Livewire::test(ViewItem::class, ['id' => $item->id])
            ->set('startDate', now()->addDays(3)->toDateString())
            ->set('endDate', now()->addDay()->toDateString())
            ->call('requestRental')
            ->assertHasErrors(['endDate' => ['after_or_equal']]);
    }

    public function test_request_rental_rejects_rental_period_longer_than_six_months(): void
    {
        [, $renter, $item] = $this->createItemScenario();

        $this->actingAs($renter);

        Livewire::test(ViewItem::class, ['id' => $item->id])
            ->set('startDate', now()->addDay()->toDateString())
            ->set('endDate', now()->addDay()->addMonthsNoOverflow(6)->addDay()->toDateString())
            ->call('requestRental')
            ->assertHasErrors(['endDate' => ['before_or_equal']])
            ->assertSee('Rentals can only be requested for up to 6 months.');
    }

    public function test_request_rental_date_inputs_disable_past_dates_and_limit_end_date(): void
    {
        [, $renter, $item] = $this->createItemScenario();
        $startDate = now()->addDays(2)->toDateString();
        $maximumEndDate = now()->addDays(2)->addMonthsNoOverflow(6)->toDateString();

        $this->actingAs($renter);

        Livewire::test(ViewItem::class, ['id' => $item->id])
            ->assertSee('min="'.now()->toDateString().'"', false)
            ->set('startDate', $startDate)
            ->assertSee('min="'.$startDate.'"', false)
            ->assertSee('max="'.$maximumEndDate.'"', false);
    }

    public function test_request_rental_shows_floating_confirmation_after_success(): void
    {
        Notification::fake();

        [, $renter, $item] = $this->createItemScenario();

        $this->actingAs($renter);

        Livewire::test(ViewItem::class, ['id' => $item->id])
            ->set('startDate', now()->addDay()->toDateString())
            ->set('endDate', now()->addDays(3)->toDateString())
            ->call('requestRental')
            ->assertSee('Rental request sent successfully!')
            ->assertSee('shadow-lg', false);

        Notification::assertSentTo($item->user, RentalRequestedNotification::class);
    }

    /**
     * @return array{0: User, 1: User, 2: Item}
     */
    private function createItemScenario(): array
    {
        $owner = User::factory()->create();
        $renter = User::factory()->create();

        $category = Category::query()->create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'icon' => 'chip',
            'is_active' => true,
        ]);

        $item = Item::query()->create([
            'user_id' => $owner->id,
            'name' => 'Portable Speaker',
            'description' => 'Bluetooth speaker',
            'price' => 50,
            'status' => 'available',
            'category_id' => $category->id,
        ]);

        return [$owner, $renter, $item];
    }
}
