<x-layout :title="$title">
    @php
        $employeeRequiredFields = $employeeRequiredFields
            ?? config('employee.employee_required_fields', []);

        $isEmployeeRequired = fn(string $field): bool =>
            in_array($field, $employeeRequiredFields, true);

        $user = $user ?? null;
    @endphp

    <div class="kanmo-page">
        <header class="border-b border-stone-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-kanmo-500 font-extrabold text-white shadow-[0_6px_16px_rgba(241,90,36,0.22)]">
                        K
                    </div>
                    <div>
                        <p class="text-sm font-extrabold tracking-[0.12em] text-kanmo-600">
                            KANMO <span class="font-medium text-slate-500">GROUP</span>
                        </p>
                        <p class="text-[11px] font-medium text-slate-500">Employee data completion form</p>
                    </div>
                </a>

                <a href="{{ route('dashboard') }}" class="kanmo-btn-ghost">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Dashboard
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-kanmo-600 via-kanmo-500 to-orange-400 px-6 py-7 text-white shadow-[0_22px_55px_rgba(241,90,36,0.18)] sm:px-8 sm:py-8">
                <div aria-hidden="true" class="absolute -right-16 -top-20 h-52 w-52 rounded-full border-[34px] border-white/10"></div>
                <div class="relative max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.17em] text-white/80">Employee data completion form</p>
                    <h1 class="mt-2 text-2xl font-extrabold sm:text-3xl">Complete Your Employee Profile
                    
                </div>
            </section>

            <div class="mt-6 space-y-3">
                @if (session('success'))
                    <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <div>
                            <p class="font-bold">Data Saved Successfully</p>
                            <p class="mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert">
                        <p class="font-bold">Something Went Wrong</p>
                        <p class="mt-0.5">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert">
                        <p class="font-bold">Some Data Is Not Valid</p>
                        <p class="mt-1">We have opened the section that needs attention. Please check the marked fields.</p>
                    </div>
                @endif
            </div>

            <form
                action="{{ route('employee.form.submit') }}"
                method="POST"
                class="mt-6 grid items-start gap-6 lg:grid-cols-[280px_minmax(0,1fr)]"
                data-employee-form
                novalidate
            >
                @csrf

                <aside class="kanmo-card p-4 lg:sticky lg:top-5">
                    <div class="border-b border-stone-100 px-2 pb-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-slate-900">Form Completion</p>
                            <span class="text-sm font-extrabold text-kanmo-600" data-form-progress-text>0%</span>
                        </div>
                        <div class="kanmo-progress-track mt-3 h-2" role="progressbar" aria-label="Form Completion / Progress Pengisian form" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="kanmo-progress-bar" style="width: 0%" data-form-progress-bar></div>
                        </div>
                        <p class="mt-2 text-xs leading-5 text-slate-500" data-form-progress-count>Calculating required fields...</p>
                    </div>

                    <nav class="mt-3 space-y-1" aria-label="Employee form steps">
                        @foreach ([
                            ['Identity', 'Employee ID & KTP'],
                            ['Personal Profile', 'Personal data'],
                            ['Contact & Address', 'Contact and residence'],
                            ['Family & Education', 'Family and education data'],
                            ['Tax & Review', 'NPWP and final review'],
                        ] as $index => [$stepTitle, $stepDescription])
                            <button
                                type="button"
                                class="kanmo-step-button"
                                data-step-button
                                data-step-index="{{ $index }}"
                                data-state="{{ $index === 0 ? 'active' : 'idle' }}"
                            >
                                <span class="kanmo-step-number">{{ $index + 1 }}</span>
                                <span class="min-w-0">
                                    <span class="kanmo-step-title block text-sm font-bold text-slate-700">{{ $stepTitle }}</span>
                                    <span class="mt-0.5 block truncate text-xs text-slate-500">{{ $stepDescription }}</span>
                                </span>
                            </button>
                        @endforeach
                    </nav>

                    <div class="mt-4 rounded-xl border border-kanmo-100 bg-kanmo-50 p-3">
                        <div class="flex gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-kanmo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12V16.5zm9-4.5a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs leading-5 text-kanmo-900/80">
                                Identity data is sensitive. Use a secure device and network.
                            </p>
                        </div>
                    </div>
                </aside>

                <div class="min-w-0">
                    <div class="mb-3 flex items-center justify-between px-1 text-xs font-semibold text-slate-500 lg:hidden">
                        <span data-current-step>Step 1 of 5 </span>
                        <span>Fields marked <span class="text-kanmo-600">*</span> are required<span class="text-kanmo-600">
                    </div>

                    {{-- STEP 1: IDENTITY --}}
                    <section class="kanmo-form-section scroll-mt-6" data-form-step data-active="true" aria-labelledby="identity-title">
                        <div class="kanmo-form-section-header">
                            <div class="kanmo-section-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0A17.9 17.9 0 0112 21.75a17.9 17.9 0 01-7.5-1.65z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Step 1 </p>
                                <h2 id="identity-title" class="mt-1 text-lg font-bold text-slate-900">Employee Identity</h2>
                                <p class="mt-1 text-sm text-slate-500">Enter your Employee ID and official identity number</p>
                            </div>
                        </div>

                        <div class="kanmo-form-section-body">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="employee_id" class="kanmo-label">Employee ID<span class="kanmo-required">*</span></label>
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <input
                                            type="text"
                                            id="employee_id"
                                            name="employee_id"
                                            value="{{ old('employee_id', $user->employee_id ?? '') }}"
                                            maxlength="20"
                                            required
                                            @readonly($user)
                                            class="kanmo-input {{ $errors->has('employee_id') ? 'kanmo-input-error' : '' }}"
                                            placeholder="Example: 123456"
                                        >
                                        <button type="button" class="kanmo-btn-secondary shrink-0" data-sync-button>
                                            <svg class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" data-sync-spinner aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span data-sync-label>Sync Data</span>
                                        </button>
                                    </div>
                                    <p class="kanmo-help">Click Sync Data to retrieve an existing employee record</p>
                                    @error('employee_id')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="ktp_number" class="kanmo-label">KTP Number<span class="kanmo-required">*</span></label>
                                    <input
                                        type="text"
                                        id="ktp_number"
                                        name="ktp_number"
                                        value="{{ old('ktp_number', $user->ktp_number ?? '') }}"
                                        maxlength="25"
                                        inputmode="numeric"
                                        required
                                        autocomplete="off"
                                        class="kanmo-input {{ $errors->has('ktp_number') ? 'kanmo-input-error' : '' }}"
                                        placeholder="Enter your 16-digit NIK"
                                    >
                                    <p class="kanmo-help">Make sure the number matches your valid KTP</p>
                                    @error('ktp_number')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 2: PERSONAL PROFILE --}}
                    <section class="kanmo-form-section scroll-mt-6" data-form-step data-active="false" aria-labelledby="profile-title">
                        <div class="kanmo-form-section-header">
                            <div class="kanmo-section-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125V18.75A2.25 2.25 0 0117.25 21H5.25A2.25 2.25 0 013 18.75V6.75A2.25 2.25 0 015.25 4.5h11.625" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Step 2</p>
                                <h2 id="profile-title" class="mt-1 text-lg font-bold text-slate-900">Personal Profile</h2>
                                <p class="mt-1 text-sm text-slate-500">Complete your personal information based on official documents</p>
                            </div>
                        </div>

                        <div class="kanmo-form-section-body">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                                <div>
                                    <label for="display_name" class="kanmo-label">Full Name<span class="kanmo-required">*</span></label>
                                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $user->display_name ?? '') }}" maxlength="100" required autocomplete="name" class="kanmo-input {{ $errors->has('display_name') ? 'kanmo-input-error' : '' }}" placeholder="Display Name">
                                    @error('display_name')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="gender" class="kanmo-label">Gender<span class="kanmo-required">*</span></label>
                                    <select id="gender" name="gender" required class="kanmo-select {{ $errors->has('gender') ? 'kanmo-input-error' : '' }}">
                                        <option value="">Select gender</option>
                                        <option value="Male" @selected(old('gender', $user->gender ?? '') === 'Male')>Male</option>
                                        <option value="Female" @selected(old('gender', $user->gender ?? '') === 'Female')>Female</option>
                                    </select>
                                    @error('gender')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="birth_place" class="kanmo-label">Place of Birth<span class="kanmo-required">*</span></label>
                                    <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $user->birth_place ?? '') }}" maxlength="100" required class="kanmo-input {{ $errors->has('birth_place') ? 'kanmo-input-error' : '' }}" placeholder="Example: Jakarta">
                                    @error('birth_place')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="date_of_birth" class="kanmo-label">Date of Birth<span class="kanmo-required">*</span></label>
                                    <input
                                        type="date"
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth', $user && $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                        required
                                        class="kanmo-input {{ $errors->has('date_of_birth') ? 'kanmo-input-error' : '' }}"
                                    >
                                    @error('date_of_birth')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="religion" class="kanmo-label">Religion<span class="kanmo-required">*</span></label>
                                    <select id="religion" name="religion" required class="kanmo-select {{ $errors->has('religion') ? 'kanmo-input-error' : '' }}">
                                        <option value="">Select religion</option>
                                        @foreach (['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Catholicism', 'Sikhism', 'Other'] as $religion)
                                            <option value="{{ $religion }}" @selected(old('religion', $user->religion ?? '') === $religion)>
                                                {{ $religion === 'Other' ? 'Other / Lainnya' : $religion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('religion')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="marital_status" class="kanmo-label">Marital Status <span class="kanmo-required">*</span></label>
                                    <select id="marital_status" name="marital_status" required class="kanmo-select {{ $errors->has('marital_status') ? 'kanmo-input-error' : '' }}">
                                        <option value="">Select status</option>
                                        <option value="Single" @selected(old('marital_status', $user->marital_status ?? '') === 'Single')>Single</option>
                                        <option value="Married" @selected(old('marital_status', $user->marital_status ?? '') === 'Married')>Married</option>
                                        <option value="Divorced" @selected(old('marital_status', $user->marital_status ?? '') === 'Divorced')>Divorced</option>
                                        <option value="Widowed" @selected(old('marital_status', $user->marital_status ?? '') === 'Widowed')>Widowed</option>
                                    </select>
                                    @error('marital_status')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="blood_group" class="kanmo-label">
                                        Blood Group
                                        @if ($isEmployeeRequired('blood_group'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <select id="blood_group" name="blood_group" @required($isEmployeeRequired('blood_group')) class="kanmo-select {{ $errors->has('blood_group') ? 'kanmo-input-error' : '' }}">
                                        <option value="">Select blood group</option>
                                        @foreach (['A+', 'B+', 'AB+', 'O+', 'A-', 'AB-', 'B-', 'O-'] as $bloodGroup)
                                            <option value="{{ $bloodGroup }}" @selected(old('blood_group', $user->blood_group ?? '') === $bloodGroup)>{{ $bloodGroup }}</option>
                                        @endforeach
                                    </select>
                                    @error('blood_group')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="nationality" class="kanmo-label">Nationality<span class="kanmo-required">*</span></label>
                                    <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality ?? 'Indonesia') }}" maxlength="50" required class="kanmo-input {{ $errors->has('nationality') ? 'kanmo-input-error' : '' }}" placeholder="Example: Indonesia">
                                    @error('nationality')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 3: CONTACT AND ADDRESS --}}
                    <section class="kanmo-form-section scroll-mt-6" data-form-step data-active="false" aria-labelledby="contact-title">
                        <div class="kanmo-form-section-header">
                            <div class="kanmo-section-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615A2.25 2.25 0 012.25 6.993V6.75" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Step 3</p>
                                <h2 id="contact-title" class="mt-1 text-lg font-bold text-slate-900">Contact & Address</h2>
                                <p class="mt-1 text-sm text-slate-500">Use active contact details so you can be reached when needed</p>
                            </div>
                        </div>

                        <div class="kanmo-form-section-body">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="primary_email" class="kanmo-label">Primary Email<span class="kanmo-required">*</span></label>
                                    <input type="email" id="primary_email" name="primary_email" value="{{ old('primary_email', $user->primary_email ?? '') }}" maxlength="191" required autocomplete="email" class="kanmo-input {{ $errors->has('primary_email') ? 'kanmo-input-error' : '' }}" placeholder="name@email.com">
                                    @error('primary_email')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="primary_contact_number" class="kanmo-label">Primary Contact Number<span class="kanmo-required">*</span></label>
                                    <input type="tel" id="primary_contact_number" name="primary_contact_number" value="{{ old('primary_contact_number', $user->primary_contact_number ?? '') }}" maxlength="30" inputmode="tel" required autocomplete="tel" class="kanmo-input {{ $errors->has('primary_contact_number') ? 'kanmo-input-error' : '' }}" placeholder="Example: 081234567890">
                                    @error('primary_contact_number')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="emergency_full_name" class="kanmo-label">
                                        Emergency Contact Name
                                        @if ($isEmployeeRequired('emergency_full_name'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="emergency_full_name" name="emergency_full_name" value="{{ old('emergency_full_name', $user->emergency_full_name ?? '') }}" maxlength="100" @required($isEmployeeRequired('emergency_full_name')) class="kanmo-input {{ $errors->has('emergency_full_name') ? 'kanmo-input-error' : '' }}" placeholder="Full name">
                                    @error('emergency_full_name')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="emergency_contact_no" class="kanmo-label">
                                        Emergency Contact Number
                                        @if ($isEmployeeRequired('emergency_contact_no'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="tel" id="emergency_contact_no" name="emergency_contact_no" value="{{ old('emergency_contact_no', $user->emergency_contact_no ?? '') }}" maxlength="30" inputmode="tel" @required($isEmployeeRequired('emergency_contact_no')) class="kanmo-input {{ $errors->has('emergency_contact_no') ? 'kanmo-input-error' : '' }}" placeholder="Example: 081234567890">
                                    @error('emergency_contact_no')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="current_address" class="kanmo-label">Current Address<span class="kanmo-required">*</span></label>
                                    <textarea id="current_address" name="current_address" rows="3" required autocomplete="street-address" class="kanmo-textarea {{ $errors->has('current_address') ? 'kanmo-input-error' : '' }}" placeholder="Street, house number, RT/RW, village, district / Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan">{{ old('current_address', $user->current_address ?? '') }}</textarea>
                                    @error('current_address')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="current_provinsi" class="kanmo-label">
                                        Current Province
                                        @if ($isEmployeeRequired('current_provinsi'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="current_provinsi" name="current_provinsi" value="{{ old('current_provinsi', $user->current_provinsi ?? '') }}" maxlength="100" @required($isEmployeeRequired('current_provinsi')) class="kanmo-input {{ $errors->has('current_provinsi') ? 'kanmo-input-error' : '' }}" placeholder="Example: DKI Jakarta">
                                    @error('current_provinsi')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="current_kotamadya_kabupaten" class="kanmo-label">
                                        City / Regency
                                        @if ($isEmployeeRequired('current_kotamadya_kabupaten'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="current_kotamadya_kabupaten" name="current_kotamadya_kabupaten" value="{{ old('current_kotamadya_kabupaten', $user->current_kotamadya_kabupaten ?? '') }}" maxlength="100" @required($isEmployeeRequired('current_kotamadya_kabupaten')) class="kanmo-input {{ $errors->has('current_kotamadya_kabupaten') ? 'kanmo-input-error' : '' }}" placeholder="Example: Jakarta Pusat">
                                    @error('current_kotamadya_kabupaten')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-3 flex cursor-pointer items-start gap-3 rounded-xl border border-stone-200 bg-stone-50 p-3 transition hover:border-kanmo-200 hover:bg-kanmo-50/40">
                                        <input type="checkbox" class="mt-0.5 h-4 w-4 rounded border-stone-300 text-kanmo-500 focus:ring-kanmo-300" data-copy-address>
                                        <span>
                                            <span class="block text-sm font-semibold text-slate-700">KTP address is the same as current address</span>
                                            <span class="mt-0.5 block text-xs text-slate-500">Check to copy the address automatically</span>
                                        </span>
                                    </label>

                                    <label for="ktp_address" class="kanmo-label">
                                        KTP Address
                                        @if ($isEmployeeRequired('ktp_address'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <textarea id="ktp_address" name="ktp_address" rows="3" @required($isEmployeeRequired('ktp_address')) class="kanmo-textarea {{ $errors->has('ktp_address') ? 'kanmo-input-error' : '' }}" placeholder="Enter your KTP address">{{ old('ktp_address', $user->ktp_address ?? '') }}</textarea>
                                    @error('ktp_address')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 4: FAMILY / EDUCATION --}}
                    <section class="kanmo-form-section scroll-mt-6" data-form-step data-active="false" aria-labelledby="education-title">
                        <div class="kanmo-form-section-header">
                            <div class="kanmo-section-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25L4.5 10.5 12 6.75l7.5 3.75L12 14.25z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 11.625v4.125c0 1.243 2.35 2.25 5.25 2.25s5.25-1.007 5.25-2.25v-4.125M19.5 10.5v5.25" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Step 4 / Langkah 4</p>
                                <h2 id="education-title" class="mt-1 text-lg font-bold text-slate-900">Family & Education</h2>
                                <p class="mt-1 text-sm text-slate-500">Provide your latest education and family information</p>
                            </div>
                        </div>

                        <div class="kanmo-form-section-body">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="mother_full_name" class="kanmo-label">
                                        Mother’s Full Name
                                        @if ($isEmployeeRequired('mother_full_name'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="mother_full_name" name="mother_full_name" value="{{ old('mother_full_name', $user->mother_full_name ?? '') }}" maxlength="100" @required($isEmployeeRequired('mother_full_name')) class="kanmo-input {{ $errors->has('mother_full_name') ? 'kanmo-input-error' : '' }}" placeholder="Mother’s Full Name">
                                    @error('mother_full_name')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="education_level" class="kanmo-label">
                                        Education Level
                                        @if ($isEmployeeRequired('education_level'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <select id="education_level" name="education_level" @required($isEmployeeRequired('education_level')) class="kanmo-select {{ $errors->has('education_level') ? 'kanmo-input-error' : '' }}">
                                        <option value="">Select education level</option>
                                        @foreach (['SMA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $level)
                                            <option value="{{ $level }}" @selected(old('education_level', $user->education_level ?? '') === $level)>{{ $level }}</option>
                                        @endforeach
                                    </select>
                                    @error('education_level')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="major" class="kanmo-label">
                                        Major
                                        @if ($isEmployeeRequired('major'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="major" name="major" value="{{ old('major', $user->major ?? '') }}" maxlength="100" @required($isEmployeeRequired('major')) class="kanmo-input {{ $errors->has('major') ? 'kanmo-input-error' : '' }}" placeholder="Example: Management">
                                    @error('major')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="institution_name" class="kanmo-label">
                                        Institution Name
                                        @if ($isEmployeeRequired('institution_name'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="institution_name" name="institution_name" value="{{ old('institution_name', $user->institution_name ?? '') }}" maxlength="150" @required($isEmployeeRequired('institution_name')) class="kanmo-input {{ $errors->has('institution_name') ? 'kanmo-input-error' : '' }}" placeholder="School or university name">
                                    @error('institution_name')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- STEP 5: TAX AND CONFIRMATION --}}
                    <section class="kanmo-form-section scroll-mt-6" data-form-step data-active="false" aria-labelledby="tax-title">
                        <div class="kanmo-form-section-header">
                            <div class="kanmo-section-icon">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.96 11.96 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751A11.96 11.96 0 0112 2.714z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Step 5</p>
                                <h2 id="tax-title" class="mt-1 text-lg font-bold text-slate-900">Tax & Review</h2>
                                <p class="mt-1 text-sm text-slate-500">Review your information before saving.</p>
                            </div>
                        </div>

                        <div class="kanmo-form-section-body">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="tax_number" class="kanmo-label">
                                        Tax Number
                                        @if ($isEmployeeRequired('tax_number'))<span class="kanmo-required">*</span>@endif
                                    </label>
                                    <input type="text" id="tax_number" name="tax_number" value="{{ old('tax_number', $user->tax_number ?? '') }}" maxlength="30" inputmode="numeric" autocomplete="off" @required($isEmployeeRequired('tax_number')) class="kanmo-input {{ $errors->has('tax_number') ? 'kanmo-input-error' : '' }}" placeholder="Enter your NPWP number">
                                    @error('tax_number')<p class="kanmo-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="mt-6 rounded-2xl border border-kanmo-100 bg-gradient-to-r from-kanmo-50 to-orange-50/40 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-kanmo-600 shadow-sm ring-1 ring-kanmo-100">
                                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-kanmo-900">Please Confirm Your Data</p>
                                        <p class="mt-1 text-xs leading-5 text-kanmo-900/70">
                                            After saving, this information will be used for employee administration.
                                            Review your identity number, contact details, and address
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="sticky bottom-0 z-20 mt-5 rounded-2xl border border-stone-200 bg-white/95 p-3 shadow-[0_-10px_30px_rgba(28,25,23,0.08)] backdrop-blur sm:p-4">
                        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ url()->previous() }}" class="kanmo-btn-ghost cursor-pointer">Cancel</a>

                            <div class="flex flex-col gap-2 sm:flex-row">
                                <button type="button" class="kanmo-btn-secondary hidden" data-previous-step>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                    </svg>
                                    Previous
                                </button>

                                <button type="button" class="kanmo-btn-primary" data-next-step>
                                    Next
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>

                                <button type="submit" class="kanmo-btn-primary hidden" data-submit-form>
                                    <svg class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" data-submit-spinner aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span data-submit-label>Save Employee Data</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</x-layout>