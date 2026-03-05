<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Imports\LeadImport;
use App\Exports\LeadExport;
use Maatwebsite\Excel\Facades\Excel;

class LeadImportExportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new LeadImport, $request->file('file'));

        return back()->with('success', 'Leads imported successfully.');
    }

    public function export(Request $request)
    {
        $query = Lead::query();

        if ($request->status)
            $query->where('status', $request->status);
        if ($request->business_type)
            $query->where('business_type', $request->business_type);
        if ($request->assigned_to)
            $query->where('assigned_to', $request->assigned_to);

        return Excel::download(new LeadExport($query), 'leads_export_' . date('Y-m-d') . '.xlsx');
    }
}
