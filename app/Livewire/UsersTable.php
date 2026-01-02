<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination, AuthorizesRequests;

    public string $search = '';
    public string $sort = 'created_at';
    public string $direction = 'desc';
    public ?int $confirmUserId = null;
    public string $confirmAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'created_at'],
        'direction' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        $this->authorize('manage-tenants'); // using existing admin gate
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sort === $field) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $field;
            $this->direction = 'asc';
        }
        $this->resetPage();
    }

    public function confirm(string $action, int $userId): void
    {
        $this->authorize('manage-tenants');
        $this->confirmAction = $action; // promote | demote
        $this->confirmUserId = $userId;
    }

    public function cancelConfirm(): void
    {
        $this->confirmAction = '';
        $this->confirmUserId = null;
    }

    public function execute(): void
    {
        $this->authorize('manage-tenants');
        if (!$this->confirmUserId || !$this->confirmAction) return;
        $target = User::findOrFail($this->confirmUserId);

        if ($this->confirmAction === 'promote') {
            if (!$target->is_admin) {
                $target->is_admin = true;
                $target->save();
                session()->flash('status', $target->email . ' promoted to admin');
            }
        } elseif ($this->confirmAction === 'demote') {
            if ($target->is_admin) {
                $adminCount = User::where('is_admin', true)->count();
                if ($adminCount <= 1) {
                    session()->flash('status', 'Cannot demote the last remaining admin.');
                } else {
                    $target->is_admin = false;
                    $target->save();
                    session()->flash('status', $target->email . ' demoted.');
                }
            }
        }

        $this->cancelConfirm();
    }

    public function render()
    {
        $query = User::query();
        if ($this->search) {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('email', 'like', $s);
            });
        }
        $users = $query->orderBy($this->sort, $this->direction)->paginate(10);
        $adminCount = User::where('is_admin', true)->count();
        return view('livewire.users-table', [
            'users' => $users,
            'adminCount' => $adminCount,
        ]);
    }
}
