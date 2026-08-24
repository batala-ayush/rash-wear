@extends('layouts.app')

@section('title','My Account — Rashwear')
@section('description','Log in or create an account to view your Rashwear order history and manage your details.')
@section('page','account')

@section('content')
<section class="section-tight">
    <div class="container">

        <div class="account-wrap" data-auth-panel>
            <div class="account-hero">
                <span class="eyebrow">Your account</span>
                <h1>Welcome to Rashwear</h1>
            </div>
            <div class="account-tabs">
                <button class="account-tab active" data-tab="login">Log in</button>
                <button class="account-tab" data-tab="signup">Create account</button>
            </div>

            <div class="account-error" data-login-error></div>
            <form class="account-form active" data-tab-panel="login">
                <div class="field"><label>Email</label><input type="email" name="email" required placeholder="you@example.com"></div>
                <div class="field"><label>Password</label><input type="password" name="password" required placeholder="••••••••"></div>
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </form>

            <div class="account-error" data-signup-error></div>
            <form class="account-form" data-tab-panel="signup">
                <div class="field"><label>Full name</label><input type="text" name="name" required placeholder="e.g. Sabina Shrestha"></div>
                <div class="field"><label>Email</label><input type="email" name="email" required placeholder="you@example.com"></div>
                <div class="field"><label>Phone</label><input type="tel" name="phone" placeholder="98XXXXXXXX"></div>
                <div class="field"><label>Password</label><input type="password" name="password" required minlength="4" placeholder="At least 4 characters"></div>
                <button type="submit" class="btn btn-primary btn-block">Create account</button>
            </form>

            <p style="font-size:12px; color:var(--ink-45); text-align:center; margin-top:20px;">
                Accounts are securely stored in the Rashwear database.
            </p>
        </div>

        <div class="account-dashboard" data-dashboard-panel>
            <div class="account-hero">
                <span class="eyebrow">Your account</span>
                <h1>Hi, <span data-dash-name>there</span></h1>
                <p style="color:var(--ink-70);" data-dash-email></p>
            </div>
            <div style="max-width:560px; margin:0 auto;">
                <div class="divider-tag"><span>Order history</span></div>
                <div data-order-list></div>
                <button class="btn btn-outline btn-block" data-logout-btn style="margin-top:10px;">Log out</button>
            </div>
        </div>

    </div>
</section>
@endsection
