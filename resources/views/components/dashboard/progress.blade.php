<section class="kanmo-card mt-6 overflow-hidden">
    <div class="grid lg:grid-cols-[1fr_auto]">
        <div class="p-6 sm:p-7">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-kanmo-500"></span>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-kanmo-600">Overall progress</p>
                    </div>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 sm:text-xl">Kelengkapan Profil Employee</h2>
                    <p class="mt-1.5 max-w-3xl text-sm leading-6 text-slate-500">
                        {{ number_format($fullyCompleteEmployees, 0, ',', '.') }} dari {{ number_format($totalEmployees, 0, ',', '.') }} employee sudah memiliki data employee dan data OD yang lengkap.
                    </p>
                </div>
                <div class="shrink-0 sm:text-right">
                    <p class="text-3xl font-extrabold tracking-tight text-kanmo-600">{{ number_format($fullCompletionPercentage, 2, ',', '.') }}%</p>
                    <p class="mt-1 text-xs font-medium text-slate-500">profil lengkap</p>
                </div>
            </div>

            <div class="kanmo-progress-track mt-6 h-3" role="progressbar" aria-label="Progress kelengkapan profil employee" aria-valuenow="{{ $fullCompletionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                <div class="kanmo-progress-bar" style="width: {{ min($fullCompletionPercentage, 100) }}%"></div>
            </div>

            <div class="mt-3 flex flex-col gap-1 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <span>{{ number_format($fullyIncompleteEmployees, 0, ',', '.') }} profil masih perlu dilengkapi</span>
                <span class="font-medium text-slate-600">Data OD lengkap + data employee lengkap</span>
            </div>
        </div>

        <div class="flex items-center border-t border-kanmo-100 bg-gradient-to-br from-kanmo-50 to-orange-50 px-6 py-5 lg:w-64 lg:border-l lg:border-t-0">
            <div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-kanmo-600 shadow-sm ring-1 ring-kanmo-100">
                    <svg class="h-5.5 w-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="mt-3 text-sm font-bold text-kanmo-900">Dorong penyelesaian</p>
                <p class="mt-1 text-xs leading-5 text-kanmo-800/75">Prioritaskan employee dengan status “Perlu dilengkapi”.</p>
            </div>
        </div>
    </div>
</section>
