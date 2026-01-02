<?php

namespace App\Livewire;

use App\Models\Tenant;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

class TenantsTable extends Component
{
	use WithPagination;
	use AuthorizesRequests;

	public string $search = '';
	public string $sort = 'created_at';
	public string $direction = 'desc';
	public bool $showCreate = false;
	public bool $showEdit = false;
	public $editId = null;
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

	protected function rules()
	{
		return [
			'name' => 'required|string|max:255|unique:tenants,name,' . $this->editId,
			'contact' => 'required|string|max:255|unique:tenants,contact,' . $this->editId,
			'room_number' => 'required|string|max:50|unique:tenants,room_number,' . $this->editId,
		];
	}

	public function mount()
	{
		$stored = session()->get('tenant_search');
		if ($stored) {
			$this->search = $stored;
		}
	}

	public function updatedSearch($value)
	{
		$this->search = trim($value);
		$this->resetPage();
		session()->put('tenant_search', $this->search);
		$this->buildSuggestions();
		$this->selectedSuggestionIndex = -1;
	}

	private function buildSuggestions(): void
	{
		if (!strlen($this->search)) {
			$this->suggestions = [];
			return;
		}
		$like = '%' . $this->search . '%';
		$this->suggestions = Tenant::query()
			->where(function ($q) use ($like) {
				$q->where('name', 'like', $like)
					->orWhere('contact', 'like', $like)
					->orWhere('room_number', 'like', $like);
			})
			->orderBy('name')
			->limit(8)
			->get()
			->map(function ($tenant) {
				return [
					'name' => $tenant->name,
					'contact' => $tenant->contact,
					'room_number' => $tenant->room_number
				];
			})
			->unique('name')
			->values()
			->toArray();
	}

	public function performSearch()
	{
		$this->search = trim($this->search);
		$this->resetPage();
		session()->put('tenant_search', $this->search);
		$this->buildSuggestions();
	}

	public function selectSuggestion($value)
	{
		$decoded = json_decode($value, true);
		$name = (is_array($decoded) && isset($decoded['name'])) ? $decoded['name'] : (string)$value;
		$this->search = trim($name);
		session()->put('tenant_search', $this->search);
		$this->suggestions = [];
		$this->selectedSuggestionIndex = -1;
		$this->resetPage();
	}

	public function clearSearch()
	{
		$this->search = '';
		$this->suggestions = [];
		$this->selectedSuggestionIndex = -1;
		session()->forget('tenant_search');
		$this->resetPage();
	}

	public function selectNextSuggestion()
	{
		if (!count($this->suggestions)) return;
		$this->selectedSuggestionIndex = ($this->selectedSuggestionIndex + 1) % count($this->suggestions);
	}

	public function selectPreviousSuggestion()
	{
		if (!count($this->suggestions)) return;
		$this->selectedSuggestionIndex = ($this->selectedSuggestionIndex - 1 + count($this->suggestions)) % count($this->suggestions);
	}

	public function selectHighlightedSuggestion()
	{
		if ($this->selectedSuggestionIndex >= 0 && isset($this->suggestions[$this->selectedSuggestionIndex])) {
			$this->selectSuggestion(json_encode($this->suggestions[$this->selectedSuggestionIndex]));
		}
	}

	public function updatingSort()
	{
		$this->resetPage();
	}

	public function sortBy($field)
	{
		$this->selectedSuggestionIndex = -1;
		if ($this->sort === $field) {
			$this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
		} else {
			$this->sort = $field;
			$this->direction = 'asc';
		}
	}

	public function create()
	{
		$this->authorize('manage-tenants');
		$this->validate();
		Tenant::create([
			'name' => trim($this->name),
			'contact' => trim($this->contact),
			'room_number' => trim($this->room_number),
		]);
		$this->resetForm();
		$this->showCreate = false;
		session()->flash('status', 'Tenant created');
	}

	public function edit($id)
	{
		$this->authorize('manage-tenants');
		$tenant = Tenant::findOrFail($id);
		$this->editId = $tenant->id;
		$this->name = $tenant->name;
		$this->contact = $tenant->contact;
		$this->room_number = $tenant->room_number;
		$this->showEdit = true;
	}

	public function openCreate()
	{
		$this->resetForm();
		$this->showCreate = true;
	}

	public function update()
	{
		$this->authorize('manage-tenants');
		$this->validate();
		$tenant = Tenant::findOrFail($this->editId);
		$tenant->update([
			'name' => trim($this->name),
			'contact' => trim($this->contact),
			'room_number' => trim($this->room_number),
		]);
		$this->resetForm();
		$this->showEdit = false;
		session()->flash('status', 'Tenant updated');
	}

	public function delete($id)
	{
		$this->authorize('manage-tenants');
		Tenant::findOrFail($id)->delete();
		session()->flash('status', 'Tenant deleted');
		$this->resetPage();
	}

	public function notify($id)
	{
		$this->authorize('manage-tenants');
		$tenant = Tenant::findOrFail($id);

		// TODO: Implement actual SMS or Email notification logic here.
		// For now, we'll simulate a successful notification.

		session()->flash('status', "Bill notification sent to {$tenant->name}.");
	}

	private function resetForm()
	{
		$this->editId = null;
		$this->name = '';
		$this->contact = '';
		$this->room_number = '';
	}

	public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
		$query = Tenant::query();
		if ($this->search) {
			$s = '%' . $this->search . '%';
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
