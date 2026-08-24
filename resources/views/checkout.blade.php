@extends('layouts.app')

@section('title','Checkout — Rashwear')
@section('description','Enter your delivery details and choose your payment method to complete your Rashwear order.')
@section('page','checkout')

@section('content')
<div class="page-head container">
    <span class="eyebrow">Step 2 of 2</span>
    <h1>Checkout</h1>
</div>

<section class="section-tight">
    <div class="container">

        <div data-checkout-empty class="empty-state" style="display:none;">
            <h2>Your bag is empty</h2>
            <p>Add something to your bag before checking out.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Start shopping</a>
        </div>

        <form data-checkout-form data-checkout-body class="checkout-layout">
            <div>
                <div class="form-block">
                    <h3><span class="step-num">1</span> Delivery details</h3>
                    <div class="field-row">
                        <div class="field"><label>Full name</label><input type="text" name="name" required placeholder="e.g. Sabina Shrestha"></div>
                        <div class="field"><label>Phone number</label><input type="tel" name="phone" required placeholder="98XXXXXXXX"></div>
                    </div>
                    <div class="field-row">
                        <div class="field full"><label>Email (optional)</label><input type="email" name="email" placeholder="you@example.com"></div>
                    </div>
                    <div class="field-row">
                        <div class="field full"><label>Delivery address</label><input type="text" name="address" required placeholder="House no., street, tole/ward"></div>
                    </div>
                    <div class="field-row">
                        <div class="field"><label>City / District</label><input type="text" name="city" required placeholder="Kathmandu"></div>
                        <div class="field"><label>Landmark (optional)</label><input type="text" name="landmark" placeholder="Near..."></div>
                    </div>
                    <div class="field-row">
                        <div class="field full"><label>Order note (optional)</label><textarea name="note" placeholder="Delivery time preference, gift note, etc."></textarea></div>
                    </div>
                </div>

                <div class="form-block">
                    <h3><span class="step-num">2</span> Payment method</h3>
                    <div class="pay-options">

                        <label class="pay-option selected">
                            <input type="radio" name="payment" value="eSewa" checked>
                            <div style="flex:1;">
                                <div class="pay-option-title">eSewa</div>
                                <div class="pay-option-desc">Pay with your eSewa wallet.</div>
                                <div class="pay-detail">We'll send an eSewa payment request to <strong>rashwear@esewa</strong> for the order total after you place the order — or scan the QR code we share on WhatsApp.</div>
                            </div>
                        </label>

                        <label class="pay-option">
                            <input type="radio" name="payment" value="Khalti">
                            <div style="flex:1;">
                                <div class="pay-option-title">Khalti</div>
                                <div class="pay-option-desc">Pay with your Khalti wallet.</div>
                                <div class="pay-detail">We'll send a Khalti payment request for the order total, or share a QR code to scan on WhatsApp after you place the order.</div>
                            </div>
                        </label>

                        <label class="pay-option">
                            <input type="radio" name="payment" value="Bank Transfer">
                            <div style="flex:1;">
                                <div class="pay-option-title">Bank transfer</div>
                                <div class="pay-option-desc">Direct deposit or online transfer.</div>
                                <div class="pay-detail">
                                    Bank: <strong>Nepal Bank Ltd.</strong><br>
                                    Account name: <strong>Rashwear Pvt. Ltd.</strong><br>
                                    Account no.: <strong>0000000000000</strong><br>
                                    Please share your payment screenshot on WhatsApp once transferred.
                                </div>
                            </div>
                        </label>

                        <label class="pay-option">
                            <input type="radio" name="payment" value="Cash on Delivery">
                            <div style="flex:1;">
                                <div class="pay-option-title">Cash on delivery <span class="badge-soon">Kathmandu Valley</span></div>
                                <div class="pay-option-desc">Pay in cash when your order arrives.</div>
                                <div class="pay-detail">Available within Kathmandu, Lalitpur and Bhaktapur. Outside the valley, please choose eSewa, Khalti or bank transfer.</div>
                            </div>
                        </label>

                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Place order</button>
                <p style="font-size:12px; color:var(--ink-45); margin-top:12px;">By placing this order you agree to be contacted on WhatsApp or phone to confirm delivery and payment.</p>
            </div>

            <aside class="summary-card">
                <h3>Order summary</h3>
                <div data-co-summary></div>
                <div class="summary-row"><span>Subtotal</span><span data-co-subtotal>Rs 0</span></div>
                <div class="summary-row"><span>Delivery</span><span>Confirmed on WhatsApp</span></div>
                <div class="summary-row total"><span>Total</span><span class="amt" data-co-total>Rs 0</span></div>
            </aside>
        </form>

        <div data-checkout-confirm class="confirm-panel" style="display:none;">
            <div class="check-circle">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div class="order-id">Order <span data-order-id></span></div>
            <p>Thank you! We've received your order and will confirm it shortly. Payment method selected: <strong data-order-payment></strong>. Please tap below to send us your order details on WhatsApp so we can confirm delivery and payment right away.</p>
            <div class="confirm-actions">
                <a href="#" data-wa-confirm target="_blank" rel="noopener" class="btn btn-wa">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.4-1.36a9.9 9.9 0 0 0 4.64 1.18h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Z"/></svg>
                    Confirm on WhatsApp
                </a>
                <a href="{{ route('shop') }}" class="btn btn-outline">Continue shopping</a>
            </div>
        </div>

    </div>
</section>
@endsection
