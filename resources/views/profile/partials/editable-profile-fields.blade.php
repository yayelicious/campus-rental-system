<div class="space-y-7">
    <div>
        <div class="mb-4 flex items-center gap-3">
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Personal</p>
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <x-label for="first_name" value="{{ __('First Name') }}" />
                <x-input id="first_name" type="text" class="mt-1 block w-full cursor-not-allowed bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400" wire:model="state.first_name" readonly autocomplete="given-name" />
            </div>

            <div>
                <x-label for="last_name" value="{{ __('Last Name') }}" />
                <x-input id="last_name" type="text" class="mt-1 block w-full cursor-not-allowed bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400" wire:model="state.last_name" readonly autocomplete="family-name" />
            </div>
        </div>

        <div class="mt-5">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full cursor-not-allowed bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400" wire:model="state.email" readonly autocomplete="username" />
        </div>
    </div>

    <div>
        <div class="mb-4 flex items-center gap-3">
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Contact</p>
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <x-label for="phone_number" value="{{ __('Phone Number 1') }}" />
                <x-input id="phone_number" type="text" class="mt-1 block w-full cursor-not-allowed bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400" wire:model="state.phone_number" readonly autocomplete="tel" />
            </div>

            <div>
                <x-label for="secondary_phone_number" value="{{ __('Phone Number 2') }}" />
                <x-input id="secondary_phone_number" type="text" class="mt-1 block w-full" wire:model="state.secondary_phone_number" autocomplete="tel" />
                <x-input-error for="state.secondary_phone_number" class="mt-2" />
            </div>
        </div>
    </div>

    <div>
        <div class="mb-4 flex items-center gap-3">
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Academic</p>
            <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1.35fr)_minmax(12rem,0.65fr)]">
            <div>
                <x-label for="course" value="{{ __('Program') }}" />
                <select id="course" wire:model="state.course" class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <option value="">Select Program</option>
                    @foreach (\App\Models\User::PROGRAMS as $program)
                        <option value="{{ $program }}">{{ $program }}</option>
                    @endforeach
                </select>
                <x-input-error for="state.course" class="mt-2" />
            </div>

            <div>
                <x-label for="year_level" value="{{ __('Year Level') }}" />
                <select id="year_level" wire:model="state.year_level" class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <option value="">Select Year Level</option>
                    @foreach (\App\Models\User::SCHOOL_LEVELS as $schoolLevel)
                        <option value="{{ $schoolLevel }}">{{ $schoolLevel }}</option>
                    @endforeach
                </select>
                <x-input-error for="state.year_level" class="mt-2" />
            </div>
        </div>
    </div>
</div>
