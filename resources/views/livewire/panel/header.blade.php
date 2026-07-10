<div>
    <header class="panel-header">
        <div class="panel-header-left">
            <h1>{{ $this->title }}</h1>
            {{-- @if(request()->path() === 'panel')
                <div class="panel-tabs panel-tabs-in-header" id="activeItemsTabs">
                    <button type="button" class="panel-tab active" data-tab="business" aria-selected="true">
                        <i class="fa fa-briefcase"></i>
                        <span>Business</span>
                    </button>
                    <button type="button" class="panel-tab" data-tab="personal" aria-selected="false">
                        <i class="fa fa-user"></i>
                        <span>Personal</span>
                    </button>
                </div>
            @endif --}}
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
