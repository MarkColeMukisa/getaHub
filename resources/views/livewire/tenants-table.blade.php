<div class="space-y-4">
    @if (session('status'))
    <div class="rounded-md bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('status') }}</div>
    @endif

    <div class="flex items-center space-x-2 relative">
        <div class="relative w-72"
            x-data="{ suggestionsVisible: true }"
            @keydown.arrow-down.prevent="$wire.selectNextSuggestion()"
            @keydown.arrow-up.prevent="$wire.selectPreviousSuggestion()"
            @keydown.enter.prevent="$wire.selectHighlightedSuggestion()"
            @click.away="suggestionsVisible = false">
            <input type="text" wire:model.debounce.300ms="search" @focus="suggestionsVisible = true" placeholder="Search tenants by name, contact, or room..." class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm w-full pr-7" />
            @if($search !== '')
            <button type="button" wire:click="clearSearch" class="absolute inset-y-0 right-1 text-gray-400 hover:text-gray-600 text-xs">✕</button>
            @endif
            <div x-show="suggestionsVisible && {{ !empty($suggestions) ? 'true' : 'false' }}" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">
                <ul class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded shadow divide-y divide-gray-100 dark:divide-gray-700 max-h-60 overflow-auto text-sm">
                    @foreach($suggestions as $idx => $s)
                    <li>
                        <button type="button" wire:click="selectSuggestion('{{ json_encode($s) }}')" @click="suggestionsVisible = false" class="w-full text-left px-3 py-1.5 hover:bg-indigo-50 dark:hover:bg-gray-700/60 @if($selectedSuggestionIndex === $idx) bg-indigo-100 dark:bg-gray-700 @endif">
                            <span class="font-semibold">{{ $s['name'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 ml-2">{{ $s['contact'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400 ml-2">({{ $s['room_number'] }})</span>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" wire:click="performSearch" class="px-3 py-2 bg-indigo-600 text-white text-xs rounded">Search</button>
    </div>

    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="px-3 py-2 text-left">Name</th>
                <th class="px-3 py-2 text-left hidden md:table-cell">Contact</th>
                <th class="px-3 py-2 text-left">Room</th>
                <th class="px-3 py-2 text-left hidden md:table-cell">Created</th>
                <th class="px-3 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($tenants as $t)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">

                <td class="px-3 py-2">{!! $search ? preg_replace('/('.preg_quote($search,'/').')/i','<mark class="bg-yellow-200 dark:bg-yellow-600/60 px-0.5 rounded">$1</mark>', e($t->name)) : e($t->name) !!}
                </td>

                <td class="px-3 py-2 hidden md:table-cell">{!! $search ? preg_replace('/('.preg_quote($search,'/').')/i','<mark class="bg-yellow-200 dark:bg-yellow-600/60 px-0.5 rounded">$1</mark>', e($t->contact)) : e($t->contact) !!}
                </td>

                <td class="px-3 py-2">{!! $search ? preg_replace('/('.preg_quote($search,'/').')/i','<mark class="bg-yellow-200 dark:bg-yellow-600/60 px-0.5 rounded">$1</mark>', e($t->room_number)) : e($t->room_number) !!}
                </td>

                <td class="px-3 py-2 text-xs hidden md:table-cell">
                    {{ $t->created_at ? $t->created_at->diffForHumans() : 'N/A' }}
                </td>

                <td class="px-3 py-2">
                    <div class="flex items-center gap-1 sm:gap-2">
                        @can('manage-tenants')
                        <button wire:click="edit({{ $t->id }})"
                            title="Edit Tenant"
                            class="p-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </button>

                        <button wire:click="delete({{ $t->id }})"
                            onclick="confirm('Are you sure you want to delete {{ addslashes($t->name) }}?') || event.stopImmediatePropagation()"
                            title="Delete Tenant"
                            class="p-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="hidden sm:inline">Delete</span>
                        </button>

                        <button wire:click="notify({{ $t->id }})"
                            title="Send Notification"
                            class="p-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="hidden sm:inline">Notify</span>
                        </button>
                        @endcan

                        <button type="button"
                            wire:click="openHistory({{ $t->id }})"
                            title="View History"
                            class="p-1.5 sm:px-3 sm:py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 hover:bg-orange-200 transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="hidden sm:inline">History</span>
                        </button>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-3 py-4 text-center text-gray-500">No tenants found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $tenants->links() }}
    </div>

    <!-- Create Modal -->
    @if($showCreate)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('showCreate', false)"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-medium mb-4">Create Tenant</h3>
            <form wire:submit.prevent="create" class="space-y-4">
                <div>
                    <x-input-label value="Name" />
                    <x-text-input type="text" wire:model.defer="name" class="mt-1 block w-full" />
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label value="Contact" />
                    <x-text-input type="text" wire:model.defer="contact" class="mt-1 block w-full" />
                    @error('contact') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label value="Room Number" />
                    <x-text-input type="text" wire:model.defer="room_number" class="mt-1 block w-full" />
                    @error('room_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" wire:click="$set('showCreate', false)" class="px-4 py-2 rounded-md border text-sm dark:border-gray-600">Cancel</button>
                    <x-primary-button wire:loading.attr="disabled">Create</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEdit)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('showEdit', false)"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-medium mb-4">Edit Tenant</h3>
            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <x-input-label value="Name" />
                    <x-text-input type="text" wire:model.defer="name" class="mt-1 block w-full" />
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label value="Contact" />
                    <x-text-input type="text" wire:model.defer="contact" class="mt-1 block w-full" />
                    @error('contact') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-input-label value="Room Number" />
                    <x-text-input type="text" wire:model.defer="room_number" class="mt-1 block w-full" />
                    @error('room_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" wire:click="$set('showEdit', false)" class="px-4 py-2 rounded-md border text-sm dark:border-gray-600">Cancel</button>
                    <x-primary-button wire:loading.attr="disabled">Save</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>