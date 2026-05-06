<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Marketplace</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Admin view of user-posted items.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950 dark:text-emerald-100">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row dark:border-slate-700 dark:bg-slate-900">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search item, owner, or email..." class="w-full rounded-lg border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
        <select wire:model.live="status" class="w-full rounded-lg border-slate-300 text-sm sm:w-48 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            <option value="">All Status</option>
            <option value="available">Available</option>
            <option value="rented">Rented</option>
            <option value="maintenance">Maintenance</option>
        </select>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($items as $item)
            <article wire:key="admin-item-{{ $item->id }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800">
                    @if ($item->imageUrl())
                        <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-sm font-semibold text-slate-400">No Image</div>
                    @endif
                </div>
                <div class="space-y-4 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="line-clamp-1 text-lg font-bold text-slate-900 dark:text-slate-100">{{ $item->name }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->user?->name ?? 'Deleted user' }} · {{ $item->categoryName() }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ ucfirst($item->status) }}</span>
                    </div>
                    <p class="line-clamp-2 text-sm text-slate-600 dark:text-slate-400">{{ $item->description ?: 'No description provided.' }}</p>
                    <div class="flex items-center justify-between gap-3 border-t border-slate-200 pt-3 text-sm dark:border-slate-700">
                        <span class="font-bold text-blue-700 dark:text-blue-300">&#8369;{{ number_format($item->price, 2) }}/day</span>
                        <span class="text-slate-500 dark:text-slate-400">{{ $item->rentals_count }} rentals</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('item.view', $item->id) }}" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">View</a>
                        <button wire:click="confirmRemoveItem({{ $item->id }})" class="flex-1 rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">Remove</button>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3 dark:border-slate-700 dark:text-slate-400">No items match the current filters.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $items->links() }}</div>

    @if ($pendingRemovalItemId)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="cancelRemoveItem"></div>
            <div class="relative w-full max-w-xl rounded-xl bg-white p-6 shadow-2xl dark:bg-slate-900">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Remove Item</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Document why "{{ $pendingRemovalItemName }}" is being removed.</p>
                <div class="mt-5 space-y-3">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Reason</label>
                        <input wire:model="removalReason" type="text" class="w-full rounded-lg border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @error('removalReason') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Details</label>
                        <textarea wire:model="removalDetails" rows="4" class="w-full rounded-lg border-slate-300 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>
                        @error('removalDetails') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelRemoveItem" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Cancel</button>
                    <button wire:click="removeItem" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Remove Item</button>
                </div>
            </div>
        </div>
    @endif
</div>
