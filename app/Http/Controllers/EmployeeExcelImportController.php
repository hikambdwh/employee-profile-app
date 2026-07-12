<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeDetailsWorkbookImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class EmployeeExcelImportController extends Controller
{
    public function create(): View
    {
        return view('pages.employee-import', [
            'title' => 'Upload Employee Excel',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:20480',
            ],
        ], [
            'excel_file.required' => 'File Excel wajib dipilih.',
            'excel_file.mimes' => 'File harus berformat XLSX atau XLS.',
            'excel_file.max' => 'Ukuran file maksimal 20 MB.',
        ]);

        $import = new EmployeeDetailsWorkbookImport();
        $file = $validated['excel_file'];

        try {
            /*
             * Seluruh import berada di dalam satu transaksi.
             * Jika satu baris gagal, perubahan dari batch sebelumnya ikut rollback.
             */
            DB::transaction(function () use ($import, $file) {
                Excel::import($import, $file);
            }, 3);
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            Log::error('Employee Excel import gagal.', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    app()->isLocal()
                        ? $exception->getMessage()
                        : 'Import gagal karena terjadi kesalahan database atau format file.'
                );
        }

        $summary = $import->summary();

        return redirect()
        ->route('employee.import.create')
        ->with(
            'success',
            sprintf(
                'Import berhasil. %d data baru, %d data diperbarui, dan %d data dilewati.',
                $summary['inserted'],
                $summary['updated'],
                $summary['skipped']
            )
        );
    }
}
