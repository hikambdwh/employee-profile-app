<?php

namespace App\Http\Controllers;

use App\Models\employee_details;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
         * Statistik dashboard tetap menghitung seluruh employee,
         * tidak terpengaruh oleh pencarian tabel.
         */
        $totalEmployees = employee_details::count();

        $completedEmployees = employee_details::query()
            ->employeeDataComplete()
            ->count();

        $pendingEmployees = max(
            $totalEmployees - $completedEmployees,
            0
        );

        $completionPercentage = $totalEmployees > 0
            ? round(
                ($completedEmployees / $totalEmployees) * 100,
                2
            )
            : 0;

        $hrIncompleteEmployees = employee_details::query()
            ->hrIncomplete()
            ->count();

        $hrCompleteEmployees = max(
            $totalEmployees - $hrIncompleteEmployees,
            0
        );

        $fullyCompleteEmployees = employee_details::query()
            ->hrComplete()
            ->employeeDataComplete()
            ->count();

        $fullyIncompleteEmployees = max(
            $totalEmployees - $fullyCompleteEmployees,
            0
        );

        $fullCompletionPercentage = $totalEmployees > 0
            ? round(
                ($fullyCompleteEmployees / $totalEmployees) * 100,
                2
            )
            : 0;

        /*
         * Mengambil keyword dari URL:
         * /?search=keyword
         */
        $search = trim(
            (string) $request->query('search', '')
        );

        /*
         * Query khusus tabel employee.
         */
        $employeeQuery = employee_details::query();

        if ($search !== '') {
            /*
             * Escape karakter wildcard agar tanda % dan _
             * tidak dianggap sebagai wildcard SQL.
             */
            $escapedSearch = addcslashes(
                $search,
                '\\%_'
            );

            $keyword = "%{$escapedSearch}%";

            $employeeQuery->where(function ($query) use ($keyword) {
                $query
                    ->where('employee_id', 'like', $keyword)
                    ->orWhere('display_name', 'like', $keyword);
            });
        }

        $allEmployees = $employeeQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        * Live search request hanya mengembalikan
        * bagian hasil tabel dan pagination.
        */
        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'components.dashboard.table-results',
                    [
                        'employees' => $allEmployees,
                    ]
                )->render(),

                'total' => $allEmployees->total(),
                'search' => $search,
            ]);
        }

        return view('pages.dashboard', [
            'title' => 'Employee Dashboard',

            'totalEmployees' => $totalEmployees,
            'completedEmployees' => $completedEmployees,
            'pendingEmployees' => $pendingEmployees,
            'completionPercentage' => $completionPercentage,

            'hrIncompleteEmployees' => $hrIncompleteEmployees,

            'fullyCompleteEmployees' => $fullyCompleteEmployees,
            'fullyIncompleteEmployees' => $fullyIncompleteEmployees,
            'fullCompletionPercentage' => $fullCompletionPercentage,

            'employees' => $allEmployees,
            'search' => $search,
        ]);
    }
}
