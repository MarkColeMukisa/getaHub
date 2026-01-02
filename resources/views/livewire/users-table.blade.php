<div class="space-y-4">
    @if (session('status'))
        <div class="rounded-md bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('status') }}</div>
    @endif

    <div class="flex items-center space-x-2">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search users..." class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm w-64" />
        <button wire:click="sortBy('name')" class="text-xs">Name @if($sort==='name') ({{ $direction }}) @endif</button>
        <button wire:click="sortBy('email')" class="text-xs">Email @if($sort==='email') ({{ $direction }}) @endif</button>
        <button wire:click="sortBy('created_at')" class="text-xs">Created @if($sort==='created_at') ({{ $direction }}) @endif</button>
    </div>

    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left">Email</th>
                <th class="px-3 py-2 text-left">Role</th>
                <th class="px-3 py-2 text-left">Created</th>
                <th class="px-3 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $u)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-3 py-2">{{ $u->name }}</td>
                    <td class="px-3 py-2">{{ $u->email }}</td>
                    <td class="px-3 py-2">
                        @if($u->is_admin)
                            <span class="inline-block px-2 py-0.5 text-xs rounded bg-indigo-600 text-white">Admin</span>
                        @else
                            <span class="inline-block px-2 py-0.5 text-xs rounded bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200">User</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-xs">{{ $u->created_at->diffForHumans() }}</td>
                    <td class="px-3 py-2 space-x-2">
                        @if(!$u->is_admin)
                            <button wire:click="confirm('promote', {{ $u->id }})" class="text-green-600 hover:underline">Promote</button>
                        @else
                            @php($isLast = $adminCount <= 1)
                            <button wire:click="confirm('demote', {{ $u->id }})" class="text-red-600 hover:underline disabled:opacity-50" @if($isLast) disabled @endif>Demote</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $users->links() }}
    </div>

    @if($confirmUserId)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" wire:click="cancelConfirm"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-medium mb-4 capitalize">Confirm {{ $confirmAction }}</h3>
                <p class="text-sm mb-4">Are you sure you want to {{ $confirmAction }} this user?</p>
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="cancelConfirm" class="px-4 py-2 rounded-md border text-sm dark:border-gray-600">Cancel</button>
                    <x-primary-button wire:click="execute" wire:loading.attr="disabled">Yes</x-primary-button>
                </div>
            </div>
        </div>
    @endif
</div>
