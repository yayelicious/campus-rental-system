<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminUserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $role = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function toggleStudentVerification(int $userId): void
    {
        abort_unless(Auth::user()?->isAdministrator(), 403);

        $user = User::query()->findOrFail($userId);
        $user->update(['is_verified_student' => ! $user->is_verified_student]);

        session()->flash('message', 'Student verification updated.');
    }

    public function render(): View
    {
        $usersQuery = User::query()
            ->withCount(['items', 'rentals'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = '%'.trim($this->search).'%';

                $query->where(fn (Builder $query) => $query
                    ->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('student_id', 'like', $search));
            })
            ->when($this->role === 'admins', fn (Builder $query) => $query->where('is_admin', true))
            ->when($this->role === 'students', fn (Builder $query) => $query->where('is_admin', false))
            ->latest();

        return view('livewire.admin-user-management', [
            'users' => $usersQuery->paginate(15),
        ]);
    }
}
