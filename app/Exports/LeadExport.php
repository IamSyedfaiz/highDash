<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Company Name',
            'Contact Name',
            'Email',
            'Phone',
            'Business Type',
            'Status',
            'Calling Status',
            'Assigned To',
            'Created At',
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->company_name,
            $lead->contact_name,
            $lead->email,
            $lead->phone,
            $lead->business_type,
            $lead->status,
            $lead->calling_status,
            $lead->assignedUser->name ?? 'Unassigned',
            $lead->created_at->format('Y-m-d'),
        ];
    }
}
