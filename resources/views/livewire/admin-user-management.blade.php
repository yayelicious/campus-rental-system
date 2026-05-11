<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">User Management</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Review users, warnings, and account activity.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950 dark:text-emerald-100">{{ session('message') }}</div>
    @endif

    <div class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row dark:border-slate-700 dark:bg-slate-900">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, email, or student ID..." class="w-full rounded-lg border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
        <select wire:model.live="role" class="w-full rounded-lg border-slate-300 text-sm sm:w-44 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            <option value="">All Users</option>
            <option value="students">Students</option>
            <option value="admins">Admins</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3">User</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Warnings</th>
                        <th class="px-5 py-3">Items</th>
                        <th class="px-5 py-3">Rentals</th>
                        <th class="px-5 py-3">Verified</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse ($users as $user)
                        <tr wire:key="admin-user-{{ $user->id }}">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-5 py-4">{{ $user->isAdministrator() ? 'Admin' : 'Student' }}</td>
                            <td class="px-5 py-4">{{ $user->warning_count }}</td>
                            <td class="px-5 py-4">{{ $user->items_count }}</td>
                            <td class="px-5 py-4">{{ $user->rentals_count }}</td>
                            <td class="px-5 py-4">
                                @if ($user->isAdministrator())
                                    <span class="text-xs font-semibold text-slate-500">Admin</span>
                                @else
                                    <button wire:click="toggleStudentVerification({{ $user->id }})" class="rounded-lg px-3 py-1.5 text-xs font-semibold {{ $user->is_verified_student ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $user->is_verified_student ? 'Verified' : 'Unverified' }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
