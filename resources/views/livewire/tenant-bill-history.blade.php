<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-start md:items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" wire:click="close"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-5xl max-h-[90vh] flex flex-col">
                <div class="px-5 py-4 border-b flex items-center justify-between dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Bill History: {{ $tenantName }}</h3>
                    <button wire:click="close" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">✕</button>
                </div>
                <div class="overflow-auto flex-1">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left">Month</th>
                                <th class="px-3 py-2 text-left">Prev</th>
                                <th class="px-3 py-2 text-left">Current</th>
                                <th class="px-3 py-2 text-left">Units</th>
                                <th class="px-3 py-2 text-left">Base</th>
                                <th class="px-3 py-2 text-left">VAT</th>
                                <th class="px-3 py-2 text-left">PAYE</th>
                                <th class="px-3 py-2 text-left">Rubbish</th>
                                <th class="px-3 py-2 text-left">Grand</th>
                                <th class="px-3 py-2 text-left">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($rows as $r)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-3 py-2">{{ $r['month'] }} {{ $r['year'] }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['previous']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['current']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['units']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['base']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['vat']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['paye']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($r['rubbish']) }}</td>
                                    <td class="px-3 py-2 font-semibold text-orange-600">{{ number_format($r['grand']) }}</td>
                                    <td class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">{{ $r['created'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-3 py-4 text-center text-gray-500">No bills yet for this tenant.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t dark:border-gray-700 flex items-center justify-between">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Showing billing details including computed VAT, PAYE & rubbish fees.</div>
                    <div class="text-sm">{!! $pagination !!}</div>
                </div>
            </div>
        </div>
    @endif
</div>
