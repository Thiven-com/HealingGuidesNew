<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PatientMedicalReportCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($report) {

            return [

                'id' => $report->id,

                'customer_id' => $report->customer_id,

                'family_member_id' => $report->family_member_id,

                'appointment_id' => $report->appointment_id,

                'doctor_id' => $report->doctor_id,

                'report_type' => $report->report_type,

                'report_name' => $report->report_name,

                'report_file' => $report->report_file
                    ? asset($report->report_file)
                    : null,

                'report_date' => $report->report_date,

                'notes' => $report->notes,

                'created_at' => $report->created_at,

                'updated_at' => $report->updated_at,

            ];

        })->values()->all();
    }
}