@extends('layouts.app')

@section('title','Your Bag — Rashwear')
@section('description','View your bag and proceed to checkout or order via WhatsApp.')
@section('page','cart')

@section('content')
<div class="page-head container">
    <span class="eyebrow">Step 1 of 2</span>
    <h1>Your bag</h1>
</div>

<section class="section-tight">
    <div class="container">

        <div data-cart-empty class="empty-state" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 6h15l-1.5 9h-12z"/><circle cx="9" cy="20" r="1.2"/><circle cx="17" cy="20" r="1.2"/><path d="M3 3h2l1 3"/></svg>
            <h2>Your bag is empty</h2>
            <p>Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Start shopping</a>
        </div>

        <div data-cart-filled class="cart-layout" style="display:grid;">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <span class="eyebrow" data-count-label></span>
                </div>
                <div data-cart-list></div>
            </div>
            <aside class="summary-card">
                <h3>Order summary</h3>
                <div class="summary-row"><span>Subtotal</span><span data-subtotal>Rs 0</span></div>
                <div class="summary-row"><span>Delivery</span><span>Calculated at checkout</span></div>
                <div class="summary-row total"><span>Estimated total</span><span class="amt" data-total>Rs 0</span></div>
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-block" style="margin-top:16px;">Proceed to checkout</a>
                <button class="btn btn-wa btn-block" data-wa-order style="margin-top:10px;">
                    Order this on WhatsApp instead
                </button>
                <p style="font-size:12px; color:var(--ink-45); margin-top:14px; margin-bottom:0;">Prefer to confirm sizes with us first? Send your bag straight to WhatsApp.</p>
            </aside>
        </div>

    </div>
</section>
@endsection
