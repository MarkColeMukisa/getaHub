<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TenantController extends Controller
{
    use AuthorizesRequests;

    public function index(): View|Application|Factory
    {
        // Dashboard is accessible to all authenticated users; a tenants section itself is gated in Blade.
        return view('dashboard');
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $this->authorize('manage-tenants');
        $tenant = Tenant::create($request->validated());

        return redirect()->route('dashboard')->with('status', 'Tenant '.$tenant->name.' created');
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $this->authorize('manage-tenants');
        $tenant->update($request->validated());

        return redirect()->route('dashboard')->with('status', 'Tenant '.$tenant->name.' updated');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Tenant $tenant): RedirectResponse
    {
        $this->authorize('manage-tenants');
        $name = $tenant->name;
        $tenant->delete();

        return redirect()->route('dashboard')->with('status', 'Tenant '.$name.' deleted');
    }

    /**
     * @throws AuthorizationException
     */
    public function exportCsv(): StreamedResponse
    {
        $this->authorize('manage-tenants');
        $query = $this->baseFilteredQuery();
        [$sort, $direction] = $this->resolveSort();
        $query->orderBy($sort, $direction);
        $tenants = $query->get(['id', 'name', 'contact', 'room_number', 'created_at']);

        $filename = 'tenants_export_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($tenants) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Contact', 'Room', 'Created At']);
            foreach ($tenants as $t) {
                fputcsv($out, [
                    $t->id,
                    $t->name,
                    $t->contact,
                    $t->room_number,
                    $t->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function baseFilteredQuery(): Builder
    {
        return Tenant::query();
    }

    private function resolveSort(): array
    {
        return ['created_at', 'desc'];
    }
}
