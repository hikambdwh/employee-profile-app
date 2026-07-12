<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeDetailsWorkbookImport implements WithMultipleSheets
{
    private EmployeeDetailsSheetImport $sheetImport;

    public function __construct()
    {
        $this->sheetImport = new EmployeeDetailsSheetImport();
    }

    public function sheets(): array
    {
        return [
            'Employee Details' => $this->sheetImport,
        ];
    }

    public function getInserted(): int
    {
        return $this->sheetImport->getInserted();
    }

    public function getUpdated(): int
    {
        return $this->sheetImport->getUpdated();
    }

    public function getSkipped(): int
    {
        return $this->sheetImport->getSkipped();
    }

    /**
     * Ringkasan hasil import.
     */
    public function summary(): array
    {
        return [
            'inserted' => $this->getInserted(),
            'updated' => $this->getUpdated(),
            'skipped' => $this->getSkipped(),
        ];
    }
}
