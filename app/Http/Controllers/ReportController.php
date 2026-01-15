<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class ReportController extends Controller
{

    public function income(Request $request)
    {
        if (\Auth::user()->can('manage income report')) {


            $property = Property::where('parent_id', parentId())->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $invoices = Invoice::where('parent_id', parentId());

            if ($request->filled('property_id') && $request->property_id != 0) {
                $invoices->where('property_id', $request->property_id);
            }

            if ($request->filled('unit_id') && $request->unit_id != 0) {
                $invoices->where('unit_id', $request->unit_id);
            }

            if ($request->filled('start_date')) {
                $invoices->whereDate('invoice_month', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $invoices->whereDate('invoice_month', '<=', $request->end_date);
            }

            $invoices = $invoices->orderBy('id', 'desc')->get();

            $units = [];
            if ($request->filled('property_id')) {
                $units = PropertyUnit::where('property_id', $request->property_id)
                    ->where('parent_id', parentId())
                    ->pluck('name', 'id');
            }

            return view('report.income', compact('invoices', 'property', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function expense(Request $request)
    {
        if (\Auth::user()->can('manage income report')) {

            $property = Property::where('parent_id', parentId())->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $expenses = Expense::where('parent_id', parentId());

            if ($request->filled('property_id') && $request->property_id != 0) {
                $expenses->where('property_id', $request->property_id);
            }

            if ($request->filled('unit_id') && $request->unit_id != 0) {
                $expenses->where('unit_id', $request->unit_id);
            }

            if ($request->filled('start_date')) {
                $expenses->whereDate('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $expenses->whereDate('date', '<=', $request->end_date);
            }

            $expenses = $expenses->orderBy('id', 'desc')->get();

            $units = [];
            if ($request->filled('property_id')) {
                $units = PropertyUnit::where('property_id', $request->property_id)
                    ->where('parent_id', parentId())
                    ->pluck('name', 'id');
            }

            return view('report.expense', compact('expenses', 'property', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function reportProfitLoss(Request $request)
    {
        $year = $request->get('year', now()->year);
        $properties = Property::where('parent_id', parentId())->pluck('name', 'id')->prepend('Select Property', '');

        $units = [];
        if ($request->filled('property_id')) {
            $units = PropertyUnit::where('property_id', $request->property_id)
                ->pluck('name', 'id');
        }

        $incomeQuery = InvoicePayment::selectRaw('MONTH(invoice_payments.payment_date) as month, SUM(invoice_payments.amount) as income')
            ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
            ->whereYear('invoice_payments.payment_date', $year);

        if ($request->filled('property_id')) {
            $incomeQuery->where('invoices.property_id', $request->property_id);
        }

        if ($request->filled('unit_id')) {
            $incomeQuery->where('invoices.unit_id', $request->unit_id);
        }

        $incomeData = $incomeQuery->groupBy('month')->pluck('income', 'month');

        $expenseQuery = Expense::selectRaw('MONTH(created_at) as month, SUM(amount) as expense')
            ->whereYear('created_at', $year);

        if ($request->filled('property_id')) {
            $expenseQuery->where('property_id', $request->property_id);
        }

        if ($request->filled('unit_id')) {
            $expenseQuery->where('unit_id', $request->unit_id);
        }

        $expenseData = $expenseQuery->groupBy('month')->pluck('expense', 'month');


        $report = [];
        for ($m = 1; $m <= 12; $m++) {
            $income = $incomeData[$m] ?? 0;
            $expense = $expenseData[$m] ?? 0;

            // Skip if both income and expense are 0
            if ($income == 0 && $expense == 0) {
                continue;
            }

            $report[] = (object)[
                'month' => date("F Y", mktime(0, 0, 0, $m, 1)),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense,
            ];
        }

        return view('report.profit_loss', [
            'year' => $year,
            'years' => range(now()->year, now()->year - 10),
            'property' => $properties,
            'units' => $units,
            'report' => $report,
            'incomeExpenseByMonth' => $this->incomeByMonth($year, $request->property_id, $request->unit_id),
        ]);
    }


    public function reportPropertyUnit(Request $request)
    {
        if (\Auth::user()->can('manage income report')) {
            $property = Property::where('parent_id', parentId())->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');

            $unitsQuery = PropertyUnit::with('properties')->whereHas('properties', function ($q) {
                $q->where('parent_id', parentId());
            });

            if ($request->filled('property_id')) {
                $unitsQuery->where('property_id', $request->property_id);
            }

            // if ($request->filled('status')) {
            //     $unitsQuery->where('is_occupied', (int) $request->status);
            // }

            $units = $unitsQuery->get();

            $proUnits = $units->groupBy('property_id')->map(function ($group) {
                return (object)[
                    'property_id' => $group->first()->property_id,
                    'property'    => $group->first()->properties,
                    'vacant'      => $group->where('is_occupied', 0)->count(),
                    'occupied'    => $group->where('is_occupied', 1)->count(),
                    'total'       => $group->count(),
                ];
            });

            return view('report.property_unit', compact('proUnits', 'property'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    public function tenant(Request $request)
    {
        if (\Auth::user()->can('manage tenant history report')) {

            $tenantOptions = Tenant::where('parent_id', parentId())
                ->whereHas('user')
                ->with('user')
                ->get()
                ->mapWithKeys(function ($tenant) {
                    return [$tenant->id => $tenant->user->first_name . ' ' . $tenant->user->last_name];
                })
                ->prepend(__('Select Tenant'), '')
                ->toArray();

            $invoiceStatus = Invoice::$status;

            $propertyOptions = Property::where('parent_id', parentId())
                ->pluck('name', 'id')
                ->prepend(__('Select Property'), '');

            $unitOptions = [];
            if ($request->property_id) {
                $unitOptions = PropertyUnit::where('property_id', $request->property_id)
                    ->pluck('name', 'id')
                    ->prepend(__('Select Unit'), '');
            }

            $query = Tenant::with(['user', 'properties', 'units'])->where('parent_id', parentId());

            if ($request->filled('tenant_id')) {
                $query->where('id', $request->tenant_id);
            }

            if ($request->filled('property_id')) {
                $query->where('property', $request->property_id);
            }

            if ($request->filled('unit_id')) {
                $query->where('unit', $request->unit_id);
            }

            if ($request->filled('status')) {

                $query->whereHas('invoices', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
            $tenants = $query->get();

            return view('report.tenant', [
                'tenants' => $tenants,
                'tenant_options' => $tenantOptions,
                'property' => $propertyOptions,
                'units' => $unitOptions,
                'status' => $invoiceStatus,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function maintenance(Request $request)
    {
        if (\Auth::user()->can('manage maintenance report')) {

            $tenantOptions = Tenant::where('parent_id', parentId())
                ->with('user')
                ->get()
                ->mapWithKeys(function ($tenant) {
                    return [$tenant->id => $tenant->user->first_name . ' ' . $tenant->user->last_name];
                })
                ->prepend(__('Select Tenant'), '')
                ->toArray();

            $propertyOptions = Property::where('parent_id', parentId())
                ->pluck('name', 'id')
                ->prepend(__('Select Property'), '');

            $unitOptions = [];
            if ($request->property_id) {
                $unitOptions = PropertyUnit::where('property_id', $request->property_id)
                    ->pluck('name', 'id');
            }

            $query = MaintenanceRequest::with(['properties', 'units', 'types', 'maintainers'])
                ->where('parent_id', parentId());

            if ($request->filled('property_id')) {
                $query->where('property_id', $request->property_id);
            }

            if ($request->filled('unit_id')) {
                $query->where('unit_id', $request->unit_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('tenant_id')) {
                $tenant = Tenant::find($request->tenant_id);
                if ($tenant) {
                    $query->where('property_id', $tenant->property)
                        ->where('unit_id', $tenant->unit);
                }
            }

            $maintenances = $query->get();
            $reqstatus = MaintenanceRequest::$status;

            return view('report.maintenance', [
                'tenant_options' => $tenantOptions,
                'property' => $propertyOptions,
                'units' => $unitOptions,
                'status' => $reqstatus,
                'maintenances' => $maintenances,
            ]);
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    public function incomeByMonth($year = null, $property_id = null, $unit_id = null)
    {
        $year = $year ?? date('Y');
        $start = strtotime("$year-01");
        $end = strtotime("$year-12");

        $currentdate = $start;
        $payment = [];

        while ($currentdate <= $end) {
            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['label'][] = date('M-Y', $currentdate);

            // Income
            $incomeQuery = InvoicePayment::join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->whereMonth('payment_date', $month)
                ->whereYear('payment_date', $year)
                ->where('invoice_payments.parent_id', parentId());

            if ($property_id) {
                $incomeQuery->where('invoices.property_id', $property_id);
            }

            if ($unit_id) {
                $incomeQuery->where('invoices.unit_id', $unit_id);
            }

            $payment['income'][] = $incomeQuery->sum('invoice_payments.amount');

            // Expense
            $expenseQuery = Expense::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('parent_id', parentId());

            if ($property_id) {
                $expenseQuery->where('property_id', $property_id);
            }

            if ($unit_id) {
                $expenseQuery->where('unit_id', $unit_id);
            }

            $payment['expense'][] = $expenseQuery->sum('amount');

            $currentdate = strtotime('+1 month', $currentdate);
        }

        return $payment;
    }
}
