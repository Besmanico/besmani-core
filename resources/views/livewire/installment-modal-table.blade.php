<div>
    @if($installments->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400 py-4">No installments for this order.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">Amount</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installments as $installment)
                        <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-4 py-2">{{ $installment->id }}</td>
                            <td class="px-4 py-2 font-medium">${{ number_format($installment->amount ?? 0, 2) }}</td>
                            <td class="px-4 py-2">{{ $installment->date ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <button
                                    type="button"
                                    role="switch"
                                    aria-checked="{{ $installment->status ? 'true' : 'false' }}"
                                    wire:click="toggleStatus({{ $installment->id }})"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $installment->status ? 'bg-green-500 focus:ring-green-500' : 'bg-gray-200 dark:bg-gray-600 focus:ring-gray-400' }}"
                                >
                                    <span
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $installment->status ? 'translate-x-5' : 'translate-x-1' }}"
                                    ></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif    

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Extra Pay:</label>
            <input
                type="text"
                inputmode="decimal"
                placeholder="0.00"
                wire:model.defer="orderFreePrice"
                class="w-28 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
            />
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                 <input
                    type="date"
                    wire:model="orderFreePriceDate"
                    class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                /> 
            </label>
            <button
                type="button"
                wire:click="saveOrderFreePrice"
                class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            >
                Save
            </button>
        </div>
        <div class="flex flex-col items-center">
            <span class="text-xs text-gray-500 dark:text-gray-400">Total (Amounts + Extra Pay)</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($totalWithFreePrice ?? 0, 2) }}</span>
        </div>
        
    </div>
</div> 
