/* ==========================================================================
   RASHWEAR — page logic
   ========================================================================== */

function initNav(){
  const toggle = document.querySelector(".nav-toggle");
  const links = document.querySelector(".nav-links");
  if (toggle && links){
    toggle.addEventListener("click", () => links.classList.toggle("open"));
    links.querySelectorAll("a").forEach(a => a.addEventListener("click", () => links.classList.remove("open")));
  }
}

function initMegaMenu(){
  const trigger = document.querySelector("[data-mega-trigger]");
  const panel = document.querySelector("[data-mega-panel]");
  if (!trigger || !panel) return;
  trigger.addEventListener("click", (e) => {
    e.stopPropagation();
    panel.classList.toggle("open");
  });
  document.addEventListener("click", (e) => {
    if (!panel.contains(e.target) && e.target !== trigger) panel.classList.remove("open");
  });
}

function initHeaderSearch(){
  const form = document.querySelector("[data-search-form]");
  if (!form) return;
  const params = new URLSearchParams(location.search);
  const input = form.querySelector("[data-search-input]");
  if (input && params.get("q")) input.value = params.get("q");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const q = input.value.trim();
    window.location.href = "shop.html" + (q ? ("?q=" + encodeURIComponent(q)) : "");
  });
}

function productCardHTML(p){
  const priceHTML = p.compareAt
    ? `<span class="product-price">${fmtNPR(p.price)}</span> <span style="text-decoration:line-through;color:var(--ink-45);font-family:var(--font-mono);font-size:11.5px;margin-left:4px;">${fmtNPR(p.compareAt)}</span>`
    : `<span class="product-price">${fmtNPR(p.price)}</span>`;
  return `
    <div class="product-card">
      <a href="product.html?id=${p.id}" style="display:block;">
        <div class="product-media" style="${rashwearMediaStyle(p.swatch)}">
          <span class="price-tag">${fmtNPR(p.price)}</span>
          ${rashwearIcon(p.icon)}
          <button class="btn btn-primary btn-block quick-add" data-quick-add="${p.id}" onclick="event.preventDefault(); quickAdd('${p.id}')">Quick add</button>
        </div>
      </a>
      <a href="product.html?id=${p.id}">
        <div class="product-name">${p.name}</div>
        <div class="product-meta">
          <span class="product-cat">${RASHWEAR_CATLABEL[p.cat]}</span>
          ${priceHTML}
        </div>
      </a>
    </div>`;
}

function quickAdd(productId){
  const p = RASHWEAR_PRODUCTS.find(p => p.id === productId);
  if (!p) return;
  cartAdd(productId, p.sizes[0], 1);
  showToast(`Added "${p.name}" (${p.sizes[0]}) to your bag`);
}

/* ---------------- HOME ---------------- */
function initHome(){
  const el = document.querySelector("[data-featured-grid]");
  if (!el) return;
  const featured = RASHWEAR_PRODUCTS.filter(p => p.tags && p.tags.includes("Bestseller")).concat(
    RASHWEAR_PRODUCTS.filter(p => !p.tags || !p.tags.includes("Bestseller"))
  ).slice(0, 8);
  el.innerHTML = featured.map(productCardHTML).join("");
}

/* ---------------- SHOP ---------------- */
function initShop(){
  const grid = document.querySelector("[data-shop-grid]");
  if (!grid) return;
  const pills = document.querySelectorAll("[data-filter]");
  const subBar = document.querySelector("[data-sub-bar]");
  const params = new URLSearchParams(location.search);
  let activeCat = params.get("cat") || "all";
  let activeSub = params.get("sub") || "all";
  const searchQ = (params.get("q") || "").trim().toLowerCase();

  function renderSubBar(){
    if (!subBar) return;
    const tree = RASHWEAR_CATEGORIES[activeCat];
    if (!tree){ subBar.innerHTML = ""; subBar.style.display = "none"; return; }
    subBar.style.display = "flex";
    subBar.innerHTML = [`<button class="filter-pill sub-pill active" data-sub="all">All ${tree.label}</button>`]
      .concat(tree.subs.map(s => `<button class="filter-pill sub-pill" data-sub="${s}">${s}</button>`)).join("");
    subBar.querySelectorAll("[data-sub]").forEach(btn => btn.addEventListener("click", () => {
      activeSub = btn.dataset.sub;
      const url = new URL(location.href);
      if (activeSub === "all") url.searchParams.delete("sub"); else url.searchParams.set("sub", activeSub);
      history.replaceState(null, "", url);
      render();
    }));
    subBar.querySelectorAll("[data-sub]").forEach(b => b.classList.toggle("active", b.dataset.sub === activeSub));
  }

  function render(){
    pills.forEach(p => p.classList.toggle("active", p.dataset.filter === activeCat));
    renderSubBar();
    let list = activeCat === "all" ? RASHWEAR_PRODUCTS.slice() : RASHWEAR_PRODUCTS.filter(p => p.cat === activeCat);
    if (activeSub !== "all") list = list.filter(p => p.sub === activeSub);
    if (searchQ) list = list.filter(p => (p.name + " " + p.desc).toLowerCase().includes(searchQ));
    grid.innerHTML = list.length ? list.map(productCardHTML).join("")
      : `<p style="grid-column:1/-1; color:var(--ink-45); padding:40px 0;">No products match${searchQ ? ` "${params.get("q")}"` : " this filter"}. Try a different search or category.</p>`;
    const countEl = document.querySelector("[data-result-count]");
    if (countEl) countEl.textContent = `${list.length} item${list.length !== 1 ? "s" : ""}`;
  }
  pills.forEach(p => p.addEventListener("click", () => {
    activeCat = p.dataset.filter;
    activeSub = "all";
    const url = new URL(location.href);
    if (activeCat === "all") url.searchParams.delete("cat"); else url.searchParams.set("cat", activeCat);
    url.searchParams.delete("sub");
    history.replaceState(null, "", url);
    render();
  }));
  render();
}

/* ---------------- PRODUCT DETAIL ---------------- */
function initProduct(){
  const wrap = document.querySelector("[data-pdp]");
  if (!wrap) return;
  const params = new URLSearchParams(location.search);
  const p = RASHWEAR_PRODUCTS.find(p => p.id === params.get("id")) || RASHWEAR_PRODUCTS[0];
  let selectedSize = p.sizes[0];
  let qty = 1;

  document.title = `${p.name} — Rashwear`;
  wrap.querySelector("[data-pdp-media]").style.cssText += rashwearMediaStyle(p.swatch);
  wrap.querySelector("[data-pdp-media]").innerHTML = rashwearIcon(p.icon);
  wrap.querySelector("[data-pdp-cat]").textContent = RASHWEAR_CATLABEL[p.cat];
  wrap.querySelector("[data-pdp-name]").textContent = p.name;
  wrap.querySelector("[data-pdp-price]").textContent = fmtNPR(p.price);
  wrap.querySelector("[data-pdp-desc]").textContent = p.desc;

  const sizeRow = wrap.querySelector("[data-pdp-sizes]");
  sizeRow.innerHTML = p.sizes.map((s,i) => `<button class="size-btn ${i===0?'selected':''}" data-size="${s}">${s}</button>`).join("");
  sizeRow.querySelectorAll(".size-btn").forEach(btn => btn.addEventListener("click", () => {
    sizeRow.querySelectorAll(".size-btn").forEach(b => b.classList.remove("selected"));
    btn.classList.add("selected");
    selectedSize = btn.dataset.size;
  }));

  const qtyInput = wrap.querySelector("[data-qty-input]");
  wrap.querySelector("[data-qty-minus]").addEventListener("click", () => { qty = Math.max(1, qty-1); qtyInput.value = qty; });
  wrap.querySelector("[data-qty-plus]").addEventListener("click", () => { qty = qty+1; qtyInput.value = qty; });

  wrap.querySelector("[data-add-cart]").addEventListener("click", () => {
    cartAdd(p.id, selectedSize, qty);
    showToast(`Added "${p.name}" (${selectedSize}) × ${qty} to your bag`);
  });

  const waBtn = wrap.querySelector("[data-wa-ask]");
  if (waBtn){
    const msg = encodeURIComponent(`Hi Rashwear! I'd like to ask about "${p.name}" (${fmtNPR(p.price)}).`);
    waBtn.href = `https://wa.me/${RASHWEAR_STORE.whatsapp}?text=${msg}`;
  }

  const related = document.querySelector("[data-related-grid]");
  if (related){
    const rel = RASHWEAR_PRODUCTS.filter(x => x.cat === p.cat && x.id !== p.id).slice(0,4);
    related.innerHTML = rel.map(productCardHTML).join("");
  }
}

/* ---------------- CART ---------------- */
function renderCartLine(l){
  return `
  <div class="cart-line" data-line="${l.id}|${l.size}">
    <div class="cart-line-media" style="${rashwearMediaStyle(l.product.swatch)}">${rashwearIcon(l.product.icon)}</div>
    <div>
      <div class="cart-line-name">${l.product.name}</div>
      <div class="cart-line-meta">Size ${l.size}</div>
      <div class="cart-line-actions">
        <div class="mini-qty">
          <button data-qty-dec>−</button><span>${l.qty}</span><button data-qty-inc>+</button>
        </div>
        <button class="cart-remove" data-remove>Remove</button>
      </div>
    </div>
    <div class="cart-line-price">${fmtNPR(l.lineTotal)}</div>
  </div>`;
}

function initCart(){
  const list = document.querySelector("[data-cart-list]");
  if (!list) return;
  const empty = document.querySelector("[data-cart-empty]");
  const filled = document.querySelector("[data-cart-filled]");

  function render(){
    const lines = cartLines();
    if (lines.length === 0){
      if (empty) empty.style.display = "block";
      if (filled) filled.style.display = "none";
      return;
    }
    if (empty) empty.style.display = "none";
    if (filled) filled.style.display = "grid";
    list.innerHTML = lines.map(renderCartLine).join("");
    const subtotal = cartSubtotal();
    document.querySelectorAll("[data-subtotal]").forEach(el => el.textContent = fmtNPR(subtotal));
    document.querySelectorAll("[data-total]").forEach(el => el.textContent = fmtNPR(subtotal));
    document.querySelectorAll("[data-count-label]").forEach(el => el.textContent = `${lines.reduce((s,l)=>s+l.qty,0)} item${lines.length!==1?'s':''}`);

    list.querySelectorAll("[data-remove]").forEach(btn => btn.addEventListener("click", (e) => {
      const [id,size] = e.target.closest(".cart-line").dataset.line.split("|");
      cartRemove(id,size); render();
    }));
    list.querySelectorAll("[data-qty-inc]").forEach(btn => btn.addEventListener("click", (e) => {
      const [id,size] = e.target.closest(".cart-line").dataset.line.split("|");
      const line = cartLines().find(l => l.id===id && l.size===size);
      cartSetQty(id,size,line.qty+1); render();
    }));
    list.querySelectorAll("[data-qty-dec]").forEach(btn => btn.addEventListener("click", (e) => {
      const [id,size] = e.target.closest(".cart-line").dataset.line.split("|");
      const line = cartLines().find(l => l.id===id && l.size===size);
      cartSetQty(id,size,line.qty-1); render();
    }));
  }
  render();

  const waCheckout = document.querySelector("[data-wa-order]");
  if (waCheckout){
    waCheckout.addEventListener("click", () => {
      const lines = cartLines();
      if (!lines.length) return;
      let msg = `Hi Rashwear! I'd like to order:%0A`;
      lines.forEach(l => { msg += `- ${l.product.name} (${l.size}) x${l.qty} — ${fmtNPR(l.lineTotal)}%0A`; });
      msg += `%0ATotal: ${fmtNPR(cartSubtotal())}`;
      window.open(`https://wa.me/${RASHWEAR_STORE.whatsapp}?text=${msg}`, "_blank");
    });
  }
}

/* ---------------- CHECKOUT ---------------- */
function initCheckout(){
  const form = document.querySelector("[data-checkout-form]");
  if (!form) return;
  const lines = cartLines();
  if (!lines.length){
    document.querySelector("[data-checkout-body]").style.display = "none";
    document.querySelector("[data-checkout-empty]").style.display = "block";
    return;
  }

  const user = typeof authCurrent === "function" ? authCurrent() : null;
  if (user){
    if (form.name) form.name.value = user.name || "";
    if (form.phone) form.phone.value = user.phone || "";
    if (form.email) form.email.value = user.email || "";
  }

  document.querySelectorAll("[data-co-subtotal]").forEach(el => el.textContent = fmtNPR(cartSubtotal()));
  document.querySelectorAll("[data-co-total]").forEach(el => el.textContent = fmtNPR(cartSubtotal()));
  const summaryList = document.querySelector("[data-co-summary]");
  if (summaryList){
    summaryList.innerHTML = lines.map(l => `
      <div class="summary-row"><span>${l.product.name} (${l.size}) × ${l.qty}</span><span class="amt">${fmtNPR(l.lineTotal)}</span></div>
    `).join("");
  }

  const payOptions = document.querySelectorAll(".pay-option");
  payOptions.forEach(opt => opt.addEventListener("click", () => {
    payOptions.forEach(o => o.classList.remove("selected"));
    opt.classList.add("selected");
    opt.querySelector("input[type=radio]").checked = true;
  }));

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = new FormData(form);
    const name = data.get("name");
    const phone = data.get("phone");
    const email = data.get("email") || "";
    const address = data.get("address");
    const city = data.get("city");
    const note = data.get("note") || "";
    const payment = data.get("payment");

    const orderId = "RW" + Date.now().toString().slice(-8);
    const order = {
      id: orderId, name, phone, email, address, city, note, payment,
      items: lines.map(l => ({ name: l.product.name, size: l.size, qty: l.qty, total: l.lineTotal })),
      total: cartSubtotal(), placedAt: new Date().toISOString(),
    };
    const orders = JSON.parse(localStorage.getItem("rashwear_orders") || "[]");
    orders.push(order);
    localStorage.setItem("rashwear_orders", JSON.stringify(orders));

    document.querySelector("[data-checkout-body]").style.display = "none";
    const confirm = document.querySelector("[data-checkout-confirm]");
    confirm.style.display = "block";
    confirm.querySelector("[data-order-id]").textContent = orderId;
    confirm.querySelector("[data-order-payment]").textContent = payment;

    const waMsg = encodeURIComponent(
      `New order ${orderId} from ${name} (${phone})\n` +
      `Deliver to: ${address}, ${city}\n` +
      lines.map(l => `- ${l.product.name} (${l.size}) x${l.qty} — ${fmtNPR(l.lineTotal)}`).join("\n") +
      `\nTotal: ${fmtNPR(cartSubtotal())}\nPayment: ${payment}` +
      (note ? `\nNote: ${note}` : "")
    );
    const waLink = confirm.querySelector("[data-wa-confirm]");
    if (waLink) waLink.href = `https://wa.me/${RASHWEAR_STORE.whatsapp}?text=${waMsg}`;

    cartClear();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initNav();
  initMegaMenu();
  initHeaderSearch();
  initHome();
  initShop();
  initProduct();
  initCart();
  initCheckout();
});
