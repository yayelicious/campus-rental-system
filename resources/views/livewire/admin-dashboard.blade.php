<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Admin Dashboard</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Platform overview for users, items, and rentals across Campus Rental.
        </p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950 dark:text-emerald-100">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Verified Students</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($verifiedStudents) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Items</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalItems) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Available Items</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($availableItems) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Rentals</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalRentals) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Active Rentals</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($activeRentals) }}</p>
        </div>
    </div>

    <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <p class="text-sm text-slate-500 dark:text-slate-400">Pending Requests</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($pendingRentals) }}</p>
    </div>

    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Pending Reports</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Verify reports before warning users or removing content.</p>
            </div>
            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-950 dark:text-rose-200">{{ $pendingReports->count() }} pending</span>
        </div>

        <div class="mt-5 space-y-4">
            @forelse ($pendingReports as $report)
                @php
                    $targetUser = $report->targetUser();
                @endphp
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white dark:bg-slate-100 dark:text-slate-900">{{ $report->type }}</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $report->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                <span class="font-semibold">Reporter:</span> {{ $report->reporter?->name ?? 'Deleted user' }}
                            </p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                <span class="font-semibold">Target user:</span> {{ $targetUser?->name ?? 'Unavailable' }}
                                @if ($targetUser)
                                    <span class="text-xs text-slate-500 dark:text-slate-400">({{ $targetUser->warning_count }} warning{{ $targetUser->warning_count === 1 ? '' : 's' }})</span>
                                @endif
                            </p>
                            @if ($report->reportedItem)
                                <p class="text-sm text-slate-700 dark:text-slate-300"><span class="font-semibold">Item:</span> {{ $report->reportedItem->name }}</p>
                            @endif
                            @if ($report->reportedMessage)
                                <p class="text-sm text-slate-700 dark:text-slate-300"><span class="font-semibold">Message:</span> "{{ $report->reportedMessage->body }}"</p>
                            @endif
                            <p class="text-sm text-slate-800 dark:text-slate-200"><span class="font-semibold">Reason:</span> {{ $report->reason }}</p>
                            @if ($report->details)
                                <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-400">{{ $report->details }}</p>
                            @endif
                        </div>

                        <div class="w-full shrink-0 space-y-3 lg:w-72">
                            <textarea wire:model="adminNotes.{{ $report->id }}" rows="2" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" placeholder="Admin notes"></textarea>
                            <div class="grid grid-cols-1 gap-2">
                                <button wire:click="issueWarning({{ $report->id }})" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                    Issue Warning
                                </button>
                                @if ($report->reportedItem)
                                    <button wire:click="removeItem({{ $report->id }})" class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-700">
                                        Remove Item
                                    </button>
                                @endif
                                @if ($targetUser)
                                    <button wire:click="removeAccount({{ $report->id }})" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                        Remove Account
                                    </button>
                                @endif
                                <button wire:click="dismissReport({{ $report->id }})" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    Dismiss
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No reports are waiting for verification.</p>
            @endforelse
        </div>
    </div>
</div>
