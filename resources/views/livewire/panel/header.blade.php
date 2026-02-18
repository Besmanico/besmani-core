<div>
    <header class="panel-header">
        <div>
            {{-- show title based on current page --}}
            <h1>{{ $this->title }}</h1>
            
        </div>
        <div class="cart-summary">
            {{-- count cart items --}}
            @php
                $cartItems = CartCount();
            @endphp
            <a href="{{ config('app.url') }}cart" class="cart-summary-btn">
                <i class="fa fa-shopping-cart"></i>
                <span>Cart ({{ $cartItems }})</span>
            </a>
        </div>
    </header>
</div>
