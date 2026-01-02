<?php

namespace App\Livewire;

use App\Models\Bill;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class TenantBillHistory extends Component
{
    use WithPagination;

    public ?int $tenantId = null;
    public bool $show = false;
    public string $tenantName = '';

    protected $listeners = [
        'open-bill-history' => 'open',
    ];

    public function openBillHistory(int $tenant): void {}

    public function openBillHistoryTenant(int $tenant): void {}

    public function openBillHistoryTenantId(int $tenant): void {}

    public function open(int $tenant): void
    {
        $tenantModel = Tenant::findOrFail($tenant);
        $this->tenantId = $tenantModel->id;
    $this->tenantName = $tenantModel->name.' (Room '.$tenantModel->room_number.')';
        $this->resetPage();
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function getBillsProperty()
    {
        if (!$this->tenantId) return collect();
        return Bill::where('tenant_id', $this->tenantId)
            ->latest('id')
            ->paginate(10);
    }

    public function render()
    {
        $vatRate = config('billing.vat_rate');
        $paye = config('billing.paye_amount');
        $rubbish = config('billing.rubbish_fee');

        $mapped = collect();
        if ($this->tenantId) {
            $mapped = $this->bills->getCollection()->map(function($bill) use ($vatRate,$paye,$rubbish){
                $base = $bill->total_amount;
                $vat = (int) round($base * $vatRate);
                $grand = $base + $vat + $paye + $rubbish;
                return [
                    'id' => $bill->id,
                    'month' => $bill->month,
                    'year' => $bill->year,
                    'previous' => $bill->previous_reading,
                    'current' => $bill->current_reading,
                    'units' => $bill->units_used,
                    'base' => $base,
                    'vat' => $vat,
                    'paye' => $paye,
                    'rubbish' => $rubbish,
                    'grand' => $grand,
                    'created' => $bill->created_at->diffForHumans(),
                ];
            });
        }

        return view('livewire.tenant-bill-history', [
            'rows' => $mapped,
            'pagination' => $this->tenantId ? $this->bills->links() : null,
        ]);
    }
}
