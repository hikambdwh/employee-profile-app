<x-layout :title="$title">
    <div class="kanmo-page">
        <header class="border-b border-stone-200 bg-white">
            <div class="mx-auto flex max-w-4xl flex-col gap-4 px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-kanmo-500 font-extrabold text-white">K</div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900">Upload Employee Excel</h1>
                        <p class="mt-0.5 text-sm text-slate-500">Insert dan update berdasarkan Employee ID.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="kanmo-btn-secondary">Kembali ke Dashboard</a>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6">
            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <p class="font-bold">Import berhasil</p>
                    <p class="mt-1">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="font-bold">Import gagal</p>
                    <p class="mt-1">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="font-bold">File belum valid</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="kanmo-card overflow-hidden">
                <div class="border-b border-kanmo-100 bg-gradient-to-r from-kanmo-50 to-white px-6 py-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-kanmo-600">Employee data import</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-900">Pilih file Excel</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Sistem membaca sheet Employee Details, Employee ID, dan kolom Mandatory pada baris 3.
                    </p>
                </div>

                <form action="{{ route('employee.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                    @csrf

                    <div>
                        <label for="excel_file" class="kanmo-label">File employee</label>
                        <input
                            type="file"
                            id="excel_file"
                            name="excel_file"
                            accept=".xlsx,.xls"
                            required
                            class="block w-full cursor-pointer rounded-xl border border-stone-300 bg-stone-50 text-sm text-slate-700 file:mr-4 file:border-0 file:bg-kanmo-500 file:px-5 file:py-3 file:font-bold file:text-white hover:file:bg-kanmo-600 focus:outline-none focus:ring-4 focus:ring-kanmo-100"
                        >
                        <p class="kanmo-help">Format XLSX/XLS, maksimal 20 MB. Jangan mengubah nama sheet atau posisi baris Mandatory dan header.</p>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <h3 class="text-sm font-bold text-amber-900">Cara kerja import</h3>
                        <ul class="mt-2 list-disc space-y-1.5 pl-5 text-sm leading-6 text-amber-800">
                            <li>Employee ID yang sudah ada akan diperbarui.</li>
                            <li>Employee ID baru akan ditambahkan.</li>
                            <li>Kolom tanpa tulisan Mandatory tidak akan diubah.</li>
                            <li>Nilai kosong tidak menghapus data lama.</li>
                            <li>Jika ada konflik, seluruh proses dibatalkan.</li>
                        </ul>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="kanmo-btn-primary">Upload dan Import</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-layout>
