<div class="payment-detail-content">
    <div class="payment-detail-header">
        <div class="payment-detail-header-icon">
            <i class="fa fa-credit-card" aria-hidden="true"></i>
        </div>
        <h3 class="payment-detail-title">  Payment Details</h3>
        <p class="payment-detail-meta">
            <span class="payment-detail-label">Invoice:</span>
            <strong class="payment-detail-code">{{ $order->tracking_code }}</strong>
        </p>
        <p class="payment-detail-date">
            <i class="fa fa-calendar"></i>
            {{ $order->created_at->format('m/d/Y') }}
        </p> 
    </div>

    <div class="payment-detail-table-wrap">
        <table class="payment-detail-table">
            <thead> 
                <tr>
                    <th>#</th>
                    <th>Amount</th>
                    <th>Due date</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($installmentPays as $index => $pay)
                    <tr>
                        <td class="payment-detail-num">{{ $index + 1 }}</td>
                        <td class="payment-amount">${{ number_format($pay->amount, 2) }}</td>
                        <td>{{ $pay->date ? \Carbon\Carbon::parse($pay->date)->format('m/d/Y') : '—' }}</td>
                        <td>{{ $pay->created_at ? $pay->created_at->format('m/d/Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="payment-detail-empty">
                            <i class="fa fa-inbox"></i>
                            No installment payments for this order.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($installmentPays->isNotEmpty())
        <div class="payment-detail-summary">
            <div class="payment-detail-summary-row">
                <span class="payment-detail-summary-label">Installments</span>
                <span class="payment-detail-summary-value">{{ $installmentPays->count() }}</span>
            </div>
            <div class="payment-detail-summary-row payment-detail-summary-total">
                <span class="payment-detail-summary-label">Total</span>
                <span class="payment-detail-summary-value">${{ number_format($installmentPays->sum('amount'), 2) }}</span>
            </div>
            <div class="payment-detail-summary-row">
                <span class="payment-detail-summary-label">Extra pay :</span>
                <span class="payment-detail-summary-value {{ ($order->free_price ?? 0) < 0 ? 'color-red' : 'color-green' }}">${{ number_format($order->free_price ?? 0, 2) }}</span>
            </div>
        </div>
    @endif 

    <div class="payment-detail-actions">
        <button type="button" class="btn-payment-pdf payment-pdf-download">
            <i class="fa fa-file-pdf-o"></i>
            <span>Save PDF</span>
            <i class="fa fa-spinner fa-spin payment-pdf-spinner" style="display: none;"></i>
        </button>
    </div> 
</div>
