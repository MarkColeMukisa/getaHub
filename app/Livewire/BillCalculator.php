<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Bill;
use App\Models\Tenant;
use Livewire\Component;

class BillCalculator extends Component
{
    public bool $showModal = false;

    public $tenant_id;

    public $previous_reading = 0;

    public $current_reading = '';

    public $month = '';

    public $unit_price = 3516;

    public $preview;

    protected $listeners = [
        'open-bill-calc-modal' => 'openModal',
    ];

    public function mount(): void
    {
        $this->month = now()->format('F');
    }

    public function openModal(): void
    {
        logger('openModal called');
        $this->resetForm();
        $this->showModal = true;
        logger('showModal set to: '.$this->showModal);
    }

    public function openBillCalcModal(): void
    {
        logger('openBillCalcModal called');
        $this->resetForm();
        $this->showModal = true;
        logger('showModal set to: '.$this->showModal);
    }

    public function updatedTenantId($value): void
    {
        if (! $value) {
            $this->previous_reading = 0;

            return;
        }

        $lastBill = Bill::where('tenant_id', $value)->latest('id')->first();
        $this->previous_reading = $lastBill?->current_reading ?? 0;
    }

    public function generatePreview(): void
    {
        $this->authorize('manage-tenants');

        $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'current_reading' => 'required|numeric|min:'.$this->previous_reading,
            'month' => 'required|string',
        ]);

        $units = $this->current_reading - $this->previous_reading;
        $base = $units * $this->unit_price;

        $vatRate = config('billing.vat_rate', 0.18);
        $paye = config('billing.paye_amount', 375);
        $rubbish = config('billing.rubbish_fee', 5000);

        $vat = round($base * $vatRate);
        $grand = $base + $vat + $paye + $rubbish;

        $tenant = Tenant::find($this->tenant_id);

        $this->preview = [
            'tenant_name' => $tenant->name,
            'room_number' => $tenant->room_number,
            'units' => $units,
            'base' => $base,
            'vat' => $vat,
            'paye' => $paye,
            'rubbish' => $rubbish,
            'grand' => $grand,
        ];
    }

    public function saveBill(): void
    {
        $this->authorize('manage-tenants');

        $this->generatePreview(); // Re-validate and ensure preview is up to date

        Bill::create([
            'tenant_id' => $this->tenant_id,
            'previous_reading' => $this->previous_reading,
            'current_reading' => $this->current_reading,
            'units_used' => $this->preview['units'],
            'unit_price' => $this->unit_price,
            'total_amount' => $this->preview['base'],
            'vat_amount' => $this->preview['vat'],
            'paye_amount' => $this->preview['paye'],
            'rubbish_amount' => $this->preview['rubbish'],
            'grand_total' => $this->preview['grand'],
            'month' => $this->month,
            'year' => (int) now()->year,
        ]);

        $this->showModal = false;
        $this->resetForm();

        $this->dispatch('celebrate');
        $this->dispatch('status-message', message: 'Bill saved successfully.');
        $this->dispatch('refresh-bill-history'); // Optional: if history needs refresh
        session()->flash('status', 'Bill saved successfully.');
    }

    public function resetForm(): void
    {
        $this->tenant_id = null;
        $this->previous_reading = 0;
        $this->current_reading = '';
        $this->month = now()->format('F');
        $this->preview = null;
    }

    public function render()
    {
        return view('livewire.bill-calculator', [
            'tenants' => Tenant::orderBy('name')->get(),
        ]);
    }
}
