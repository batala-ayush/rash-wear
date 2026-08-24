@extends('layouts.app')

@section('title','Rashwear — Everyday clothing for the whole family, Kathmandu')
@section('description','Rashwear is a Kathmandu-based clothing store for men, women and kids. Shop online with delivery across Nepal, pay via eSewa, Khalti, bank transfer or cash on delivery.')
@section('page','home')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow hero-eyebrow">Kathmandu, Nepal · Since 2026</span>
            <h1>Clothes for<br>every <em>member</em><br>of the family.</h1>
            <p class="lede">Rashwear is a family-run clothing store bringing everyday essentials and festival wear to men, women and kids across Nepal — online, on WhatsApp, or at our counter.</p>
            <div class="hero-ctas">
                <a href="{{ route('shop') }}" class="btn btn-primary">Shop the collection</a>
                <a href="https://wa.me/9779800000000" target="_blank" rel="noopener" class="btn btn-wa">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.4-1.36a9.9 9.9 0 0 0 4.64 1.18h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg>
                    Order on WhatsApp
                </a>
            </div>
            <div class="hero-trust">
                <span class="tag-chip">Delivery across Nepal</span>
                <span class="tag-chip">eSewa · Khalti · COD</span>
                <span class="tag-chip">Easy 3-day exchange</span>
            </div>
        </div>
        <div class="hero-visual">
            <div class="corner-tag">RASHWEAR</div>
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" fill="none" style="color:#F7F5F1">
                <path d="M32 22 L44 13 L50 20 L56 13 L70 24 L76 36 L67 42 L63 34 L63 90 L37 90 L37 34 L33 42 L24 36 Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                <path d="M50 20 L50 90 M44 13 C44 22 50 24 50 20 C50 24 56 22 56 13" stroke="currentColor" stroke-width="1.4"/>
            </svg>
        </div>
    </div>
</section>

<div class="strip">
    <div class="container">
        <div class="strip-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="15" height="11" rx="1"/><path d="M17 10h3l2 3v5h-5"/><circle cx="7" cy="19" r="1.6"/><circle cx="17.5" cy="19" r="1.6"/></svg> Nationwide delivery from Kathmandu</div>
        <div class="strip-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg> eSewa, Khalti &amp; bank transfer accepted</div>
        <div class="strip-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Cash on delivery, Kathmandu Valley</div>
        <div class="strip-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg> New arrivals every month</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Shop by category</span>
                <h2>Dressed for the whole house.</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-outline">View everything</a>
        </div>
        <div class="cat-grid">
            <a class="cat-tile" href="{{ route('shop') }}?cat=men" style="background:#2E3A59;">
                <svg viewBox="0 0 100 133" preserveAspectRatio="xMidYMid slice"><rect width="100" height="133" fill="#2E3A59"/></svg>
                <div><span class="cat-label">Men</span><span class="cat-count">Shirts, trousers &amp; more</span></div>
            </a>
            <a class="cat-tile" href="{{ route('shop') }}?cat=women" style="background:#B5562F;">
                <svg viewBox="0 0 100 133" preserveAspectRatio="xMidYMid slice"><rect width="100" height="133" fill="#B5562F"/></svg>
                <div><span class="cat-label">Women</span><span class="cat-count">Dresses, kurtas &amp; more</span></div>
            </a>
            <a class="cat-tile" href="{{ route('shop') }}?cat=kids" style="background:#5C6B4A;">
                <svg viewBox="0 0 100 133" preserveAspectRatio="xMidYMid slice"><rect width="100" height="133" fill="#5C6B4A"/></svg>
                <div><span class="cat-label">Kids</span><span class="cat-count">Tees, frocks &amp; jackets</span></div>
            </a>
        </div>
    </div>
</section>

<section class="section section-tight" style="padding-top:0;">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Handpicked</span>
                <h2>This week's favourites.</h2>
            </div>
        </div>
        <div class="product-grid" data-featured-grid></div>
    </div>
</section>

<section class="section" style="background:var(--paper-dim);">
    <div class="container story">
        <div class="story-visual">
            <svg viewBox="0 0 100 100" fill="none" stroke="var(--indigo)" stroke-width="1.6"><path d="M34 22 L46 12 L50 18 L54 12 L66 22 L70 34 L62 39 L60 32 L60 92 H40 L40 32 L38 39 L30 34 Z"/></svg>
        </div>
        <div>
            <span class="eyebrow">Our story</span>
            <h2>A family counter, now online.</h2>
            <p>From our Kathmandu counter to your door, Rashwear brings everyday essentials and festival-ready clothing to the whole family.</p>
            <p>New arrivals land every month, and if you want a different size or colour, just send us a WhatsApp message and we'll help you choose.</p>
        </div>
    </div>
</section>
@endsection
