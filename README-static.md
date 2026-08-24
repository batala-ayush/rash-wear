# Rashwear — website

A ready-to-host website for Rashwear, your Kathmandu clothing store. Plain HTML/CSS/JS — no build step, no server required to get started.

## What's included

- `index.html` — homepage
- `shop.html` — full catalog with Men / Women / Kids filters
- `product.html` — product detail page (reads `?id=` from the URL)
- `cart.html` — shopping bag
- `checkout.html` — delivery form + payment method (eSewa, Khalti, Bank Transfer, Cash on Delivery)
- `contact.html` — contact info + WhatsApp button
- `css/style.css` — all styling
- `js/data.js` — your product catalog (edit this to add/remove products)
- `js/cart.js` — cart logic + your store's contact details
- `js/main.js` — page behaviour

## What's new: top bar, categories, and accounts

- **Two-tier header** — a top utility strip (email, phone, social links, language selector, login) plus a main bar with logo, an "All Categories" mega-menu, a search box, and account/cart icons — similar to common storefront layouts.
- **Subcategories** — Men, Women and Kids each now have their own subcategories (e.g. Men → Shirts, T-Shirts, Trousers, Jackets, Ethnic Wear). Pick a top category on the shop page and a second row of pills appears to narrow further. The mega-menu in the header links straight to any subcategory.
- **Search** — the header search box filters products by name/description and lands on the shop page with results.
- **Accounts & login** — see below.

## Accounts & login

`account.html` gives customers a Login / Create account page, and once signed in, an order-history dashboard (orders are matched to the account by email, so ask customers to enter their email at checkout).

**Please read this before you rely on it for real customers:** this login system is a front-end demo. Accounts and passwords are stored in `localStorage` — inside the customer's own browser, in plain text. That means:
- Accounts don't sync across devices or browsers (a customer who signs up on their phone won't see that account on their laptop).
- You, as the store owner, cannot see or manage this account list from anywhere.
- It is **not secure** for anything customers would consider sensitive, because anyone with access to that browser/device can read the stored passwords.

This is genuinely useful for showing the *experience* of having accounts, and for letting a signed-in customer see their own past orders on the same browser. But before real launch, swap it for a real auth backend, for example:
- **Firebase Authentication** or **Supabase Auth** — both have generous free tiers and handle password hashing, email verification, and password reset for you, with a small amount of JS to wire up.
- A custom backend with hashed passwords (bcrypt) and server-side sessions, if you want full control.

Happy to help wire either of those in when you're ready — just ask.

## 1. Put in your real details (do this first)

Open `js/cart.js` and edit the `RASHWEAR_STORE` object at the top:

```js
const RASHWEAR_STORE = {
  whatsapp: "9779800000000",   // your real WhatsApp number, country code + number, no + or spaces
  phone: "+977-98-0000-0000",
  email: "hello@rashwear.com.np",
  esewaId: "rashwear@esewa",
  khaltiId: "rashwear",
  bank: { name: "...", acctName: "...", acctNo: "..." },
};
```

The same WhatsApp number is used for the floating chat button, "Ask about this" on product pages, and order confirmations — update it once here and it updates everywhere in `js`. It's also hardcoded a few places directly in the HTML (`wa.me/9779800000000` links in each page's footer and floating button) — find-and-replace `9779800000000` across all `.html` files with your real number.

Also update the bank details shown in `checkout.html` (search for "Nepal Bank Ltd.") and the address/email in each page's footer.

## 2. Add your real products and photos

Edit `js/data.js`. Each product looks like this:

```js
{ id:"m-01", name:"Everyday Oxford Shirt", cat:"men", price:2450, icon:"shirt", swatch:"indigo", sizes:["S","M","L","XL"], desc:"..." }
```

Right now products use simple line-drawn icons instead of photos, so the site works before you've done a product shoot. To switch to real photos:

1. Add an `img` field to a product, e.g. `img:"images/oxford-shirt.jpg"`.
2. In `js/main.js`, inside `productCardHTML()` and `initProduct()`, swap the `rashwearIcon(...)` calls for an `<img src="${p.img}">` when `p.img` exists. Keep the icon as a fallback for products without photos yet.
3. Create an `images/` folder and drop your product photos in (square or 3:4 works best).

## 3. Connect real payment (important)

The checkout page currently lets a customer choose eSewa, Khalti, Bank Transfer, or Cash on Delivery, then sends you the order details over WhatsApp so you can confirm and collect payment manually. This works well for a small store starting out, and needs zero backend.

**eSewa and Khalti do not support taking real payment from a plain static website** — both require a small server-side component, because the payment request has to be cryptographically signed with a secret key that can never be exposed in browser code. To accept real in-app payment later:

- **eSewa**: register as a merchant at [merchant.esewa.com.np](https://merchant.esewa.com.np), then follow the ePay v2 API docs at [developer.esewa.com.np](https://developer.esewa.com.np). You'll need a small backend (Node/PHP/Python — even a few serverless functions are enough) to generate the HMAC-SHA256 signature for each order and verify the payment response.
- **Khalti**: register at [khalti.com](https://khalti.com) for a merchant account and API keys, and similarly call their API from a backend, not directly from this site.
- Until that's set up, the WhatsApp + manual confirmation flow (already built in) is a normal, common way for small Nepali stores to operate.

If you'd like, a simple backend (e.g. a small Node.js API deployed on Vercel/Render) can be built next to handle this — just ask.

## 4. Hosting

This is a static site — any of these work, most have a free tier:

- **Netlify** or **Vercel** — drag-and-drop the whole folder, done in minutes.
- **GitHub Pages** — push this folder to a GitHub repo and enable Pages in settings.
- Any shared hosting that serves plain HTML (common with Nepali hosting providers too).

Once hosted, point your domain (e.g. `rashwear.com.np`) at it via your registrar's DNS settings.

## 5. Notes

- The cart and order history are stored in the visitor's own browser (`localStorage`) — there's no shared database yet. Every order also gets sent to you via WhatsApp when the customer checks out, so you won't miss any orders.
- Fonts (Fraunces, Inter, IBM Plex Mono) load from Google Fonts — no local files needed, just an internet connection.
- The site is responsive down to mobile and keyboard-navigable.
