<div x-data="{ show: @entangle('showModal') }" x-show="show" x-on:open-bill-calc-modal.window="show = true" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative">

            <!-- Close Button -->
            <button @click="show = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-8 md:p-10">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Side: Form -->
                    <div class="lg:w-1/2 space-y-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Bill Calculator</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Generate and save tenant bills.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Select Tenant" />
                                <select wire:model.live="tenant_id" class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition-all">
                                    <option value="">-- Choose Tenant --</option>
                                    @foreach($tenants as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }} (Room {{ $t->room_number }})</option>
                                    @endforeach
                                </select>
                                @error('tenant_id') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Prev Reading" />
                                    <x-text-input type="number" wire:model="previous_reading" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed" readonly disabled />
                                </div>
                                <div>
                                    <x-input-label value="Curr Reading" />
                                    <x-text-input type="number" wire:model.live.debounce.300ms="current_reading" class="mt-1 block w-full" placeholder="0" />
                                    @error('current_reading') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <x-input-label value="Bill Month" />
                                <select wire:model="month" class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition-all">
                                    @foreach(["January","February","March","April","May","June","July","August","September","October","November","December"] as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('month') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button wire:click="generatePreview" wire:loading.attr="disabled" class="flex-1 bg-primary hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-xl transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                                    <span wire:loading.remove wire:target="generatePreview">Preview</span>
                                    <span wire:loading wire:target="generatePreview" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                                    <svg wire:loading.remove wire:target="generatePreview" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button wire:click="resetForm" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl transition-all">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Preview -->
                    <div class="lg:w-1/2 flex flex-col">
                        @if($preview)
                        <div class="flex-1 bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 flex flex-col animate-in fade-in slide-in-from-right-4">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-bold text-gray-900 dark:text-white uppercase tracking-wider text-xs">Bill Breakdowns</h4>
                                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $month }}</span>
                            </div>

                            <div class="space-y-4 flex-1">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tenant</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $preview['tenant_name'] }} (Room {{ $preview['room_number'] }})</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Units Used</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ number_format($preview['units']) }} Units</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Base Cost (x3,516)</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">UGX {{ number_format($preview['base']) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">VAT (18%)</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">UGX {{ number_format($preview['vat']) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">PAYE</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">UGX {{ number_format($preview['paye']) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Rubbish Fee</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">UGX {{ number_format($preview['rubbish']) }}</span>
                                </div>
                            </div>

                            <div class="mt-8 p-6 bg-secondary rounded-2xl text-white shadow-2xl relative overflow-hidden group">
                                <div class="absolute inset-0 bg-primary opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative flex justify-between items-center">
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mb-1 block">Grand Total Due</span>
                                        <span class="text-3xl font-black font-mono tracking-tighter">UGX {{ number_format($preview['grand']) }}</span>
                                    </div>
                                    <div class="p-3 bg-white/10 rounded-xl">
                                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <button wire:click="saveBill" wire:loading.attr="disabled" class="mt-4 w-full bg-primary hover:bg-orange-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-primary/20 active:scale-95 flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="saveBill">Save Bill Permanent</span>
                                <span wire:loading wire:target="saveBill" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                                <svg wire:loading.remove wire:target="saveBill" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                            </button>
                        </div>
                        @else
                        <div class="flex-1 bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center space-y-4">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm">
                                <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-500 dark:text-gray-400">No Preview Generated</h4>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Fill the details on the left and click 'Preview' to see the breakdown.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>