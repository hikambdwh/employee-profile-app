@props([
    'companies' => collect(),
    'selectedCompanies' => [],
])

<div
    class="flex items-center p-4"
    data-company-filter
>
    <button
        id="company-filter-button"
        data-dropdown-toggle="company-filter-dropdown"
        class="kanmo-btn-primary"
        type="button"
    >
        <span data-company-filter-label>
            Filter by Company
        </span>

        <svg
            class="ml-2 h-4 w-4"
            aria-hidden="true"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
            />
        </svg>
    </button>

    <div
        id="company-filter-dropdown"
        class="z-20 hidden min-w-64 rounded-xl border
               border-kanmo-200 bg-white p-4 shadow-lg"
    >
        <div class="mb-3 flex items-center justify-between gap-4">
            <h6 class="text-sm font-bold text-gray-900">
                Company
            </h6>

            <button
                type="button"
                class="text-xs font-semibold cursor-pointer text-kanmo-600 hover:text-kanmo-700"
                data-company-filter-clear
            >
                Reset
            </button>
        </div>

        <ul
            class="max-h-72 space-y-3 overflow-y-auto text-sm"
            aria-labelledby="company-filter-button"
        >
            @forelse ($companies as $index => $company)
                @php
                    $companyValue = $company['value'];
                    $companyLabel = $company['label'];
                    $checkboxId = 'company-filter-' . $index;
                @endphp

                <li class="flex items-center">
                    <input
                        id="{{ $checkboxId }}"
                        type="checkbox"
                        value="{{ $companyValue }}"
                        data-company-filter-checkbox
                        @checked(
                            in_array(
                                $companyValue,
                                $selectedCompanies,
                                true
                            )
                        )
                        class="h-4 w-4 cursor-pointer rounded
                               border-gray-300 text-kanmo-600
                               focus:ring-kanmo-300"
                    >

                    <label
                        for="{{ $checkboxId }}"
                        class="ml-2 cursor-pointer text-sm
                               font-medium text-gray-900"
                    >
                        {{ $companyLabel }}
                    </label>
                </li>
            @empty
                <li class="text-sm text-slate-500">
                    Tidak ada data company.
                </li>
            @endforelse
        </ul>
    </div>
</div>