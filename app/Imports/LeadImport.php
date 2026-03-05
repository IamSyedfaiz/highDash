<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lead([
            'company_name' => $row['company_name'],
            'contact_name' => $row['contact_name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'],
            'phone_1' => $row['phone_1'] ?? null,
            'phone_2' => $row['phone_2'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'address' => $row['address'] ?? null,
            'lead_source' => $row['lead_source'] ?? null,
            'business_type' => $row['business_type'] ?? 'Trader',
        ]);
    }
}
