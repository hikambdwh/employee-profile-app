@php
    $cards = [
        [
            'label' => 'Total Employee',
            'value' => number_format($totalEmployees, 0, ',', '.'),
            'description' => 'Seluruh employee terdaftar',
            'accent' => 'bg-kanmo-500',
            'valueClass' => 'text-slate-900',
            'iconWrap' => 'bg-kanmo-50 text-kanmo-600 ring-kanmo-100',
            'icon' => 'users',
        ],
        [
            'label' => 'Sudah Lengkap',
            'value' => number_format($completedEmployees, 0, ',', '.'),
            'description' => 'Employee sudah melengkapi data',
            'accent' => 'bg-emerald-500',
            'valueClass' => 'text-emerald-600',
            'iconWrap' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
            'icon' => 'check',
        ],
        [
            'label' => 'Belum Lengkap',
            'value' => number_format($pendingEmployees, 0, ',', '.'),
            'description' => 'Perlu dilengkapi employee',
            'accent' => 'bg-amber-500',
            'valueClass' => 'text-amber-600',
            'iconWrap' => 'bg-amber-50 text-amber-600 ring-amber-100',
            'icon' => 'clock',
        ],
        [
            'label' => 'Employee Completion',
            'value' => number_format($completionPercentage, 2, ',', '.') . '%',
            'description' => 'Kelengkapan data employee',
            'accent' => 'bg-kanmo-400',
            'valueClass' => 'text-kanmo-600',
            'iconWrap' => 'bg-kanmo-50 text-kanmo-600 ring-kanmo-100',
            'icon' => 'percentage',
        ],
        [
            'label' => 'Data OD Belum Lengkap',
            'value' => number_format($hrIncompleteEmployees, 0, ',', '.'),
            'description' => 'Masih memerlukan tindakan OD',
            'accent' => 'bg-rose-500',
            'valueClass' => 'text-rose-600',
            'iconWrap' => 'bg-rose-50 text-rose-600 ring-rose-100',
            'icon' => 'alert',
        ],
    ];
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
    @foreach ($cards as $card)
        <article class="kanmo-card kanmo-card-interactive relative overflow-hidden p-5">
            <div class="absolute inset-x-0 top-0 h-1 {{ $card['accent'] }}"></div>

            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-3 text-3xl font-extrabold tracking-tight {{ $card['valueClass'] }}">{{ $card['value'] }}</p>
                    <p class="mt-2 text-xs leading-5 text-slate-500">{{ $card['description'] }}</p>
                </div>

                <div class="shrink-0 rounded-xl p-3 ring-1 {{ $card['iconWrap'] }}">
                    @if ($card['icon'] === 'users')
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H2v-2a4 4 0 014-4h3m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 1a3 3 0 10-4-2.83" />
                        </svg>
                    @elseif ($card['icon'] === 'check')
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @elseif ($card['icon'] === 'clock')
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif ($card['icon'] === 'percentage')
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 20L17 4M6 9a3 3 0 100-6 3 3 0 000 6zm12 12a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                    @else
                        <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.75m9-1.386c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9zM12 16.5h.008v.008H12V16.5z" />
                        </svg>
                    @endif
                </div>
            </div>
        </article>
    @endforeach
</div>
