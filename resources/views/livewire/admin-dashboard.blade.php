<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Admin Dashboard</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Platform overview for Campus Rental moderation.</p>
        </div>
        <a href="{{ route('admin.reports') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-black dark:bg-slate-100 dark:text-slate-900">
            Review Reports
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Total Items</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalItems) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Active Rentals</p>
            <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($activeRentals) }}</p>
        </div>
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-5 shadow-sm dark:border-rose-900/60 dark:bg-rose-950">
            <p class="text-sm text-rose-700 dark:text-rose-200">Pending Reports</p>
            <p class="mt-2 text-3xl font-bold text-rose-900 dark:text-rose-100">{{ number_format($pendingReports) }}</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Available Items</p>
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($availableItems) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <p class="text-sm text-slate-500 dark:text-slate-400">Pending Requests</p>
            <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($pendingRentals) }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-4 lg:grid-cols-3">
        <a href="{{ route('admin.marketplace') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Marketplace</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Review posted items and remove policy-violating listings with documented reasons.</p>
        </a>
        <a href="{{ route('admin.users') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">User Management</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View account status, warnings, listings, and verification state.</p>
        </a>
        <a href="{{ route('admin.reports') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Reports & Complaints</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Resolve user reports and appeals from removed item owners.</p>
        </a>
    </div>
</div>
