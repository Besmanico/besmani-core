<div class="p-1">
    @if($order && ($order->cart_id !== null && $order->cart_id !== ''))
        <livewire:installment-modal-table :order-id="$order->id" />
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400 py-4">No cart linked to this order. Installments are available only when the order has a cart.</p>
    @endif
</div>
 