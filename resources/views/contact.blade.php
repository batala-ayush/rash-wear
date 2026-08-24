@extends('layouts.app')

@section('title','Contact — Rashwear')
@section('description','Contact Rashwear by WhatsApp, phone, email, or visit our Kathmandu counter.')
@section('page','contact')

@section('content')
<div class="page-head container">
    <span class="eyebrow">Get in touch</span>
    <h1>We'd love to hear from you.</h1>
</div>

<section class="section">
    <div class="container contact-grid">
        <div>
            <div class="contact-card">
                <div class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.4-1.36a9.9 9.9 0 0 0 4.64 1.18h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg></div>
                <div>
                    <h4>WhatsApp</h4>
                    <p>Fastest way to reach us — order, ask about sizing, or track a delivery.</p>
                    <a href="https://wa.me/9779800000000" target="_blank" rel="noopener" class="btn btn-wa" style="margin-top:12px;">Message us</a>
                </div>
            </div>
            <div class="contact-card">
                <div class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 22 16.92z"/></svg></div>
                <div>
                    <h4>Call us</h4>
                    <p>+977-98-0000-0000</p>
                    <p>Sunday – Friday, 10 AM – 7 PM</p>
                </div>
            </div>
            <div class="contact-card">
                <div class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <div>
                    <h4>Visit our counter</h4>
                    <p>Kathmandu, Nepal</p>
                </div>
            </div>
            <div class="contact-card">
                <div class="ic"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg></div>
                <div>
                    <h4>Email</h4>
                    <p>hello@rashwear.com.np</p>
                </div>
            </div>
        </div>

        <div class="map-visual">
            <svg viewBox="0 0 200 150" width="70%" fill="none" stroke="var(--ink-45)" stroke-width="1.2">
                <path d="M20 120 Q40 40 100 30 Q160 40 180 120" />
                <circle cx="100" cy="70" r="6" fill="var(--marigold)" stroke="none"/>
                <text x="60" y="140" font-family="IBM Plex Mono" font-size="9" fill="var(--ink-45)">Kathmandu, Nepal</text>
            </svg>
        </div>
    </div>
</section>
@endsection
