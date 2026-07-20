<?php

namespace App\Http\Controllers;

use App\Models\employee_details;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        /*
     * Pastikan company selalu berupa array.
     */
        $companyParameter = $request->query(
            'company',
            []
        );

        if (!is_array($companyParameter)) {
            $companyParameter = [
                $companyParameter,
            ];
        }

        $selectedCompanies = collect(
            $companyParameter
        )
            ->filter(
                fn($company) =>
                is_string($company) &&
                    trim($company) !== ''
            )
            ->map(
                fn($company) =>
                trim($company)
            )
            ->unique()
            ->values();

        /*
     * Base query untuk company filter.
     *
     * Query ini akan digunakan oleh:
     * - Card
     * - Progress
     * - Tabel employee
     */
        $companyQuery = employee_details::query();

        if ($selectedCompanies->isNotEmpty()) {
            $includeUnregistered =
                $selectedCompanies->contains(
                    '__NULL__'
                );

            $registeredCompanies =
                $selectedCompanies
                ->reject(
                    fn($company) =>
                    $company === '__NULL__'
                )
                ->values();

            $companyQuery->where(
                function ($query) use (
                    $registeredCompanies,
                    $includeUnregistered
                ) {
                    if (
                        $registeredCompanies
                        ->isNotEmpty()
                    ) {
                        $query->whereIn(
                            'company',
                            $registeredCompanies->all()
                        );
                    }

                    if ($includeUnregistered) {
                        $unregisteredCondition =
                            function ($subQuery) {
                                $subQuery
                                    ->whereNull('company')
                                    ->orWhere('company', '');
                            };

                        if (
                            $registeredCompanies
                            ->isNotEmpty()
                        ) {
                            $query->orWhere(
                                $unregisteredCondition
                            );
                        } else {
                            $query->where(
                                $unregisteredCondition
                            );
                        }
                    }
                }
            );
        }

        /*
     * Statistik mengikuti company filter.
     */
        $totalEmployees =
            (clone $companyQuery)->count();

        $completedEmployees =
            (clone $companyQuery)
            ->employeeDataComplete()
            ->count();

        $pendingEmployees = max(
            $totalEmployees -
                $completedEmployees,
            0
        );

        $completionPercentage =
            $totalEmployees > 0
            ? round(
                (
                    $completedEmployees /
                    $totalEmployees
                ) * 100,
                2
            )
            : 0;

        $hrIncompleteEmployees =
            (clone $companyQuery)
            ->hrIncomplete()
            ->count();

        $fullyCompleteEmployees =
            (clone $companyQuery)
            ->hrComplete()
            ->employeeDataComplete()
            ->count();

        $fullyIncompleteEmployees = max(
            $totalEmployees -
                $fullyCompleteEmployees,
            0
        );

        $fullCompletionPercentage =
            $totalEmployees > 0
            ? round(
                (
                    $fullyCompleteEmployees /
                    $totalEmployees
                ) * 100,
                2
            )
            : 0;

        /*
     * Query tabel berasal dari company query.
     *
     * Search hanya memengaruhi tabel, sedangkan
     * company filter memengaruhi tabel dan statistik.
     */
        $employeeQuery =
            clone $companyQuery;

        if ($search !== '') {
            $escapedSearch = addcslashes(
                $search,
                '\\%_'
            );

            $keyword =
                "%{$escapedSearch}%";

            $employeeQuery->where(
                function ($query) use ($keyword) {
                    $query
                        ->where(
                            'employee_id',
                            'like',
                            $keyword
                        )
                        ->orWhere(
                            'display_name',
                            'like',
                            $keyword
                        );
                }
            );
        }

        $allEmployees = $employeeQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statisticsData = [
            'totalEmployees' =>
            $totalEmployees,

            'completedEmployees' =>
            $completedEmployees,

            'pendingEmployees' =>
            $pendingEmployees,

            'completionPercentage' =>
            $completionPercentage,

            'hrIncompleteEmployees' =>
            $hrIncompleteEmployees,

            'fullyCompleteEmployees' =>
            $fullyCompleteEmployees,

            'fullyIncompleteEmployees' =>
            $fullyIncompleteEmployees,

            'fullCompletionPercentage' =>
            $fullCompletionPercentage,
        ];

        /*
        * Live search request hanya mengembalikan
        * bagian hasil tabel dan pagination.
        */
        if ($request->ajax()) {
            return response()->json([
                /*
         * HTML tabel.
         */
                'html' => view(
                    'components.dashboard.table-results',
                    [
                        'employees' =>
                        $allEmployees,
                    ]
                )->render(),

                /*
         * HTML card dan progress.
         */
                'statisticsHtml' => view(
                    'components.dashboard.statistics',
                    $statisticsData
                )->render(),

                'total' =>
                $allEmployees->total(),

                'search' =>
                $search,
            ]);
        }

        $companies = employee_details::query()
            ->whereNotNull('company')
            ->where('company', '<>', '')
            ->select('company')
            ->distinct()
            ->orderBy('company')
            ->pluck('company')
            ->map(
                fn($company) => [
                    'value' => $company,
                    'label' => $company,
                ]
            )
            ->values();

        $hasUnregisteredCompany =
            employee_details::query()
            ->where(function ($query) {
                $query
                    ->whereNull('company')
                    ->orWhere('company', '');
            })
            ->exists();

        if ($hasUnregisteredCompany) {
            $companies->prepend([
                'value' => '__NULL__',
                'label' => 'BELUM TERDAFTAR',
            ]);
        }

        return view('pages.dashboard', [
            'title' => 'Employee Dashboard',

            'companies' => $companies,

            'selectedCompanies' =>
            $selectedCompanies->all(),

            'employees' => $allEmployees,

            'search' => $search,

            ...$statisticsData,
        ]);
    }
}
