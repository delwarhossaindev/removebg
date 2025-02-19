<?php

namespace App\Imports;

use App\Models\KpiMains;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KpiMainsImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row to the KpiMains model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Get the authenticated user's ID
        $authId = Auth::user()->id;

        // Generate a new KPI code
        $kpiCode = $this->getKpiCode();

        return new KpiMains([
            'name'           => $row['name'],
            'objective_id'   => $row['objective_id'],
            'perspective_id' => $row['perspective_id'],
            'unit_id'        => $row['unit_id'],
            'department_id'  => $row['department_id'],
            'kpi_code'       => $kpiCode,
            'created_by'     => $authId,
            'kpi_id'         => 1,
            'type'           => 1,
        ]);
    }

    /**
     * Generate a new KPI code.
     *
     * @return string
     */
    private function getKpiCode()
    {
        // Retrieve the last inserted record's kpi_code
        $lastKRA = KpiMains::orderBy('id', 'desc')->first();

        // Determine the new kpi_code
        if ($lastKRA && preg_match('/KPI(\d+)/', $lastKRA->kpi_code, $matches)) {
            $nextNumber = (int) $matches[1] + 1; // Increment the numeric part
        } else {
            $nextNumber = 1; // Start from 1 if no record exists
        }

        // Generate the kpi_code
        return 'KPI' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
