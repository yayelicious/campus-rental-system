<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Rental;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminDashboard extends Component
{
    /**
     * @var array<int, string>
     */
    public array $adminNotes = [];

    public function dismissReport(int $reportId): void
    {
        $report = $this->pendingReport($reportId);

        $this->completeReport($report, Report::ACTION_DISMISSED);
        session()->flash('message', 'Report dismissed after verification.');
    }

    public function issueWarning(int $reportId): void
    {
        $report = $this->pendingReport($reportId);
        $targetUser = $report->targetUser();

        if (! $targetUser) {
            session()->flash('message', 'No user is available for this warning.');

            return;
        }

        DB::transaction(function () use ($report, $targetUser): void {
            $targetUser->increment('warning_count');
            $this->completeReport($report, Report::ACTION_WARNING);
        });

        session()->flash('message', 'Warning issued and report marked reviewed.');
    }

    public function removeItem(int $reportId): void
    {
        $report = $this->pendingReport($reportId);

        if (! $report->reportedItem) {
            session()->flash('message', 'No item is available for removal.');

            return;
        }

        DB::transaction(function () use ($report): void {
            $report->reportedItem->delete();
            $this->completeReport($report, Report::ACTION_ITEM_REMOVED);
        });

        session()->flash('message', 'Item removed and report marked reviewed.');
    }

    public function removeAccount(int $reportId): void
    {
        $report = $this->pendingReport($reportId);
        $targetUser = $report->targetUser();

        if (! $targetUser) {
            session()->flash('message', 'No account is available for removal.');

            return;
        }

        if ($targetUser->isAdministrator() || (int) $targetUser->id === (int) Auth::id()) {
            session()->flash('message', 'Admin accounts cannot be removed from reports.');

            return;
        }

        DB::transaction(function () use ($report, $targetUser): void {
            $this->completeReport($report, Report::ACTION_ACCOUNT_REMOVED);
            $targetUser->delete();
        });

        session()->flash('message', 'Account removed and report marked reviewed.');
    }

    /**
     * @return array<string, mixed>
     */
    public function render()
    {
        $totalUsers = User::query()->count();
        $verifiedStudents = User::query()->where('is_verified_student', true)->count();
        $totalItems = Item::query()->count();
        $availableItems = Item::query()->available()->count();
        $totalRentals = Rental::query()->count();
        $activeRentals = Rental::query()->where('status', Rental::STATUS_ACTIVE)->count();
        $pendingRentals = Rental::query()->where('status', Rental::STATUS_PENDING)->count();
        $pendingReports = Report::query()
            ->where('status', Report::STATUS_PENDING)
            ->with(['reporter', 'reportedUser', 'reportedItem.user', 'reportedMessage.sender'])
            ->latest()
            ->limit(10)
            ->get();

        return view('livewire.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'verifiedStudents' => $verifiedStudents,
            'totalItems' => $totalItems,
            'availableItems' => $availableItems,
            'totalRentals' => $totalRentals,
            'activeRentals' => $activeRentals,
            'pendingRentals' => $pendingRentals,
            'pendingReports' => $pendingReports,
        ]);
    }

    private function pendingReport(int $reportId): Report
    {
        return Report::query()
            ->whereKey($reportId)
            ->where('status', Report::STATUS_PENDING)
            ->with(['reportedUser', 'reportedItem.user', 'reportedMessage.sender'])
            ->firstOrFail();
    }

    private function completeReport(Report $report, string $action): void
    {
        $report->update([
            'status' => Report::STATUS_REVIEWED,
            'reviewed_by' => Auth::id(),
            'admin_action' => $action,
            'admin_notes' => trim($this->adminNotes[$report->id] ?? '') ?: null,
            'reviewed_at' => now(),
        ]);

        unset($this->adminNotes[$report->id]);
    }
}
