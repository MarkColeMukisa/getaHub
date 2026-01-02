<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillStoreRequest;
use App\Models\Bill;
use App\Models\Tenant;
use App\Events\BillCreated;

class BillController extends Controller
{
    public function index(Tenant $tenant)
    {
        $tenants = Tenant::orderBy('name')->get();
        $bills = Bill::with('tenant')->latest('id')->limit(50)->get();

    // Server-side calculations for presentation
    $vatRate = config('billing.vat_rate');
    $payeFixed = config('billing.paye_amount');
    $rubbishFee = config('billing.rubbish_fee');
    $billRows = $bills->map(function($bill) use ($vatRate,$payeFixed,$rubbishFee){
        $base = $bill->total_amount; // persisted base (units * unit price)
        $vat = (int) round($base * $vatRate);
        $paye = $payeFixed;
        $rubbish = $rubbishFee;
        $grand = $base + $vat + $paye + $rubbish;
        return [
            'tenant_display' => $bill->tenant->name.' (Room '.$bill->tenant->room_number.')',
            'previous_reading' => $bill->previous_reading,
            'current_reading' => $bill->current_reading,
            'units_used' => $bill->units_used,
            'base_amount' => $base,
            'rubbish' => $rubbish,
            'grand_total' => $grand,
        ];
    });
    $grandTotal = $billRows->sum('grand_total');

    return view('index', compact('tenants','billRows','grandTotal'));
    }

    public function store(BillStoreRequest $request)
    {
        $tenant = Tenant::findOrFail($request->tenant_id);

        // Get last bill for tenant
        $lastBill = Bill::where('tenant_id', $tenant->id)->latest('id')->first();
        $previousReading = $lastBill?->current_reading ?? 0;

        $current = (int)$request->current_reading;
        if ($current < $previousReading) {
            return back()->withErrors(['current_reading' => 'Current reading cannot be less than previous reading.'])->withInput();
        }

        $units = $current - $previousReading;
        $unitPrice = $request->unit_price ?? 3516; // default constant from UI
        $monthName = $request->filled('month') ? $request->month : now()->format('F');
        $year = (int) now()->year;

        $totalAmount = $units * $unitPrice; // base amount per specification (UI adds other charges visually)

    $bill = Bill::create([
            'tenant_id' => $tenant->id,
            'previous_reading' => $previousReading,
            'current_reading' => $current,
            'units_used' => $units,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'month' => $monthName,
            'year' => $year,
        ]);
    BillCreated::dispatch($bill);
        return redirect()->back()->with('bill_created', $bill->id);
    }

    public function previousReading(Tenant $tenant)
    {
        $lastBill = Bill::where('tenant_id', $tenant->id)->latest('id')->first();
        return response()->json([
            'previous_reading' => $lastBill?->current_reading ?? 0,
        ]);
    }
}
