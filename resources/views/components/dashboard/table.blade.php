<section class="mt-6">
    <div class="kanmo-card overflow-hidden">
        <div
            class="flex flex-col gap-5 border-b border-stone-200 px-5 py-5 lg:flex-row lg:items-center lg:justify-between lg:px-6">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-lg font-bold text-slate-900">Employee Profile Completion</h2>
                </div>
                <p class="mt-1.5 text-sm text-slate-500">Pantau kelengkapan data employee, OD, dan keseluruhan profil.
                </p>
            </div>

            <form action="{{ route('dashboard') }}" method="GET"
                class="flex w-full flex-col gap-2 sm:flex-row lg:max-w-xl" data-employee-search-form>
                <label for="employee-search" class="sr-only">
                    Search employee
                </label>

                <div class="relative flex-1">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0
                   flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3a6 6 0 104.472 10.003l3.262
                       3.263a1 1 0 001.414-1.414l-3.263-3.262A6
                       6 0 009 3zM5 9a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <input type="search" id="employee-search" name="search" value="{{ request('search') }}"
                        placeholder="Search NIP or employee name" autocomplete="off"
                        class="kanmo-input py-2.5 pl-11" data-employee-search-input>

                    {{-- Loading indicator --}}
                    <div class="pointer-events-none absolute inset-y-0 right-0
                   items-center pr-3.5"
                        data-employee-search-loading hidden>
                        <svg class="h-5 w-5 animate-spin text-kanmo-500" viewBox="0 0 24 24" fill="none"
                            aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>

                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373
                       0 0 5.373 0 12h4zm2
                       5.291A7.962 7.962 0 014
                       12H0c0 3.042 1.135 5.824
                       3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <button type="submit" class="kanmo-btn-primary">
                    Search
                </button>

                <a href="{{ route('dashboard') }}"
                    class="kanmo-btn-secondary
               {{ request()->filled('search') ? '' : 'hidden' }}"
                    data-employee-search-reset>
                    Reset
                </a>
            </form>
        </div>

        <p class="sr-only" aria-live="polite" aria-atomic="true" data-employee-search-status></p>

        <div data-employee-search-results aria-busy="false">
            @include('components.dashboard.table-results', [
                'employees' => $employees,
            ])
        </div>

        @if (request()->filled('search'))
            <div
                class="flex flex-col gap-1 border-b border-kanmo-100 bg-kanmo-50/70 px-5 py-3 text-sm sm:flex-row sm:items-center sm:justify-between lg:px-6">
                <p class="text-kanmo-800">Hasil pencarian untuk <span class="font-bold">“{{ request('search') }}”</span>
                </p>
                <p class="text-xs font-semibold text-kanmo-600">{{ number_format($employees->total(), 0, ',', '.') }}
                    data ditemukan</p>
            </div>
        @endif

        @php
            $progressMeta = static function (float $percentage): array {
                return match (true) {
                    $percentage >= 100 => [
                        'label' => 'Lengkap',
                        'text' => 'text-emerald-600',
                        'bar' => 'bg-emerald-500',
                        'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                    ],
                    $percentage >= 75 => [
                        'label' => 'Hampir lengkap',
                        'text' => 'text-kanmo-600',
                        'bar' => 'bg-kanmo-500',
                        'badge' => 'bg-kanmo-50 text-kanmo-700 ring-kanmo-600/20',
                    ],
                    $percentage >= 40 => [
                        'label' => 'Dalam proses',
                        'text' => 'text-amber-600',
                        'bar' => 'bg-amber-500',
                        'badge' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                    ],
                    default => [
                        'label' => 'Perlu dilengkapi',
                        'text' => 'text-rose-600',
                        'bar' => 'bg-rose-500',
                        'badge' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                    ],
                };
            };
        @endphp

    </div>
</section>
