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
                <th class="px-3 py-2 text-left hidden md:table-cell">History</th>
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
                    {{ $t->created_at->diffForHumans() }}
                </td>

                <td class="px-3 py-2 space-x-2">
                    @can('manage-tenants')
                        <button wire:click="edit({{ $t->id }})"
                                class="px-3 py-1 rounded-full text-xs font-medium
                                   bg-indigo-100 text-indigo-700
                                   hover:bg-indigo-200 transition">
                            Edit
                        </button>


                        <button wire:click="delete({{ $t->id }})"
                                onclick="return confirm('Delete tenant {{ addslashes($t->name) }}?')"
                                class="px-3 py-1 rounded-full text-xs font-medium
               bg-red-100 text-red-700
               hover:bg-red-200 transition">
                            Delete
                        </button>


                        <button wire:click="notify({{ $t->id }})"
                                title="Send Bill Notification"
                                class="px-3 py-1 rounded-full text-xs font-medium
               bg-green-100 text-green-700
               hover:bg-green-200 transition">
                            Notify
                        </button>

                    @endcan
                </td>

                <td class="px-3 py-2">
                    <button type="button"
                            wire:click="openHistory({{ $t->id }})"
                            class="px-3 py-1 rounded-full text-xs font-medium
                                   bg-orange-100 text-orange-700
                                   hover:bg-orange-200 transition
                                   hidden md:table-cell">
                        View
                    </button>

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
