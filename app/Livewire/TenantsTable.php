<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Mail\TenantCreated;
use App\Models\Tenant;
use App\Services\NotificationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TenantsTable extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $search = '';

    public string $sort = 'created_at';

    public string $direction = 'desc';

    public bool $showCreate = false;

    public bool $showEdit = false;

    public $editId;

    public $name = '';

    public $contact = '';

    public $room_number = '';

    public array $suggestions = [];

    public int $selectedSuggestionIndex = -1;

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'created_at'],
        'direction' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'open-create-tenant' => 'openCreate',
    ];

    public function openHistory(int $id): void
    {
        $this->dispatch('open-bill-history', tenant: $id);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tenants,name,'.$this->editId,
            'contact' => 'required|string|max:255|unique:tenants,contact,'.$this->editId,
            'room_number' => 'required|string|max:50|unique:tenants,room_number,'.$this->editId,
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount(): void
    {
        $stored = session()->get('tenant_search');
        if ($stored) {
            $this->search = $stored;
        }
    }

    public function updatedSearch($value): void
    {
        $this->search = trim((string) $value);
        $this->resetPage();
        session()->put('tenant_search', $this->search);
        $this->buildSuggestions();
        $this->selectedSuggestionIndex = -1;
    }

    private function buildSuggestions(): void
    {
        if ($this->search === '') {
            $this->suggestions = [];

            return;
        }
        $like = '%'.$this->search.'%';
        $this->suggestions = Tenant::query()
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('contact', 'like', $like)
                    ->orWhere('room_number', 'like', $like);
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(fn ($tenant) => [
                'name' => $tenant->name,
                'contact' => $tenant->contact,
                'room_number' => $tenant->room_number,
            ])
            ->unique('name')
            ->values()
            ->toArray();
    }

    public function performSearch(): void
    {
        $this->search = trim($this->search);
        $this->resetPage();
        session()->put('tenant_search', $this->search);
        $this->buildSuggestions();
    }

    public function selectSuggestion($value): void
    {
        $decoded = json_decode((string) $value, true);
        $name = (is_array($decoded) && isset($decoded['name'])) ? $decoded['name'] : (string) $value;
        $this->search = trim((string) $name);
        session()->put('tenant_search', $this->search);
        $this->suggestions = [];
        $this->selectedSuggestionIndex = -1;
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->suggestions = [];
        $this->selectedSuggestionIndex = -1;
        session()->forget('tenant_search');
        $this->resetPage();
    }

    public function selectNextSuggestion(): void
    {
        if (! count($this->suggestions)) {
            return;
        }
        $this->selectedSuggestionIndex = ($this->selectedSuggestionIndex + 1) % count($this->suggestions);
    }

    public function selectPreviousSuggestion(): void
    {
        if (! count($this->suggestions)) {
            return;
        }
        $this->selectedSuggestionIndex = ($this->selectedSuggestionIndex - 1 + count($this->suggestions)) % count($this->suggestions);
    }

    public function selectHighlightedSuggestion(): void
    {
        if ($this->selectedSuggestionIndex >= 0 && isset($this->suggestions[$this->selectedSuggestionIndex])) {
            $this->selectSuggestion(json_encode($this->suggestions[$this->selectedSuggestionIndex]));
        }
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        $this->selectedSuggestionIndex = -1;
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
    }

    public function create(): void
    {
        $this->authorize('manage-tenants');
        $this->validate();
        $tenant = Tenant::create([
            'name' => trim((string) $this->name),
            'contact' => trim((string) $this->contact),
            'room_number' => trim((string) $this->room_number),
        ]);
        $this->resetForm();
        $this->showCreate = false;
        session()->flash('status', 'Tenant created');

        Mail::to('joegapp256@gmail.com')->send(new TenantCreated($tenant));
    }

    public function edit($id): void
    {
        $this->authorize('manage-tenants');
        $tenant = Tenant::findOrFail($id);
        $this->editId = $tenant->id;
        $this->name = $tenant->name;
        $this->contact = $tenant->contact;
        $this->room_number = $tenant->room_number;
        $this->showEdit = true;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showCreate = true;
    }

    public function update(): void
    {
        $this->authorize('manage-tenants');
        $this->validate();
        $tenant = Tenant::findOrFail($this->editId);
        $tenant->update([
            'name' => trim((string) $this->name),
            'contact' => trim((string) $this->contact),
            'room_number' => trim((string) $this->room_number),
        ]);
        $this->resetForm();
        $this->showEdit = false;
        session()->flash('status', 'Tenant updated');
    }

    public function delete($id): void
    {
        $this->authorize('manage-tenants');
        Tenant::findOrFail($id)->delete();
        session()->flash('status', 'Tenant deleted');
        $this->resetPage();
    }

    public function notify($id, NotificationService $notificationService): void
    {
        $this->authorize('manage-tenants');
        $tenant = Tenant::with('latestBill')->findOrFail($id);
        $latestBill = $tenant->latestBill;

        if (! $latestBill) {
            session()->flash('error', "No bill found for {$tenant->name}. Cannot send notification.");

            return;
        }

        if ($notificationService->notify($latestBill)) {
            $this->dispatch('celebrate');
            session()->flash('status', "Bill notification sent to {$tenant->name} ({$tenant->contact}).");
        } else {
            session()->flash('error', "Failed to send notification to {$tenant->name}. Check logs for details.");
        }
    }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->name = '';
        $this->contact = '';
        $this->room_number = '';
    }

    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $query = Tenant::query();
        if ($this->search !== '' && $this->search !== '0') {
            $s = '%'.$this->search.'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('contact', 'like', $s)
                    ->orWhere('room_number', 'like', $s);
            });
        }
        $tenants = $query->orderBy($this->sort, $this->direction)->paginate(10);

        return view('livewire.tenants-table', ['tenants' => $tenants]);
    }
}
