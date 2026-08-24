@extends('layouts.app')

@section('title','Product — Rashwear')
@section('description','Product details for Rashwear clothing. Choose size, quantity, and add the item to your bag.')
@section('page','product')

@section('content')
<section class="section" data-pdp>
    <div class="container pdp-grid">
        <div class="pdp-media" data-pdp-media></div>
        <div class="pdp-info">
            <span class="eyebrow" data-pdp-cat></span>
            <h1 data-pdp-name></h1>
            <div class="pdp-price" data-pdp-price></div>
            <p class="pdp-desc" data-pdp-desc></p>

            <div class="option-block">
                <span class="option-label">Size</span>
                <div class="size-row" data-pdp-sizes></div>
            </div>

            <div class="option-block">
                <span class="option-label">Quantity</span>
                <div class="qty-row">
                    <button data-qty-minus type="button">−</button>
                    <input data-qty-input type="text" value="1" readonly>
                    <button data-qty-plus type="button">+</button>
                </div>
            </div>

            <div class="pdp-actions">
                <button class="btn btn-primary" data-add-cart>Add to bag</button>
                <a class="btn btn-wa" data-wa-ask target="_blank" rel="noopener">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.4-1.36a9.9 9.9 0 0 0 4.64 1.18h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg>
                    Ask about this
                </a>
            </div>

            <div class="pdp-meta-list">
                <div><span>Delivery</span><span>2–5 days, nationwide</span></div>
                <div><span>Payment</span><span>eSewa · Khalti · Bank · COD</span></div>
                <div><span>Returns</span><span>3-day exchange, unworn with tag</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="divider-tag"><span>You might also like</span></div>
        <div class="product-grid" data-related-grid></div>
    </div>
</section>
@endsection
