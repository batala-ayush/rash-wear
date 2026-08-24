/* ==========================================================================
   RASHWEAR — cart storage (localStorage-backed; this is a real deployed
   site, not a Claude artifact, so browser storage is fine here)
   ========================================================================== */

const RASHWEAR_STORE = {
  name: "Rashwear",
  whatsapp: "9779800000000",     // TODO: replace with your real WhatsApp number (countrycode+number, no +)
  phone: "+977-98-0000-0000",    // TODO: replace with your real phone number
  email: "hello@rashwear.com.np",// TODO: replace with your real email
  address: "Kathmandu, Nepal",
  esewaId: "rashwear@esewa",     // TODO: replace once you have a real eSewa merchant / ID
  khaltiId: "rashwear",          // TODO: replace once you have a real Khalti merchant ID
  bank: { name: "Nepal Bank Ltd.", acctName: "Rashwear Pvt. Ltd.", acctNo: "0000000000000" }, // TODO
};

function cartRead(){
  try{ return JSON.parse(localStorage.getItem("rashwear_cart") || "[]"); }
  catch(e){ return []; }
}
function cartWrite(items){
  localStorage.setItem("rashwear_cart", JSON.stringify(items));
  updateCartCount();
}
function cartAdd(productId, size, qty){
  const items = cartRead();
  const existing = items.find(i => i.id === productId && i.size === size);
  if (existing){ existing.qty += qty; }
  else { items.push({ id: productId, size: size, qty: qty }); }
  cartWrite(items);
}
function cartSetQty(productId, size, qty){
  let items = cartRead();
  const line = items.find(i => i.id === productId && i.size === size);
  if (!line) return;
  if (qty <= 0){ items = items.filter(i => !(i.id === productId && i.size === size)); }
  else { line.qty = qty; }
  cartWrite(items);
}
function cartRemove(productId, size){
  const items = cartRead().filter(i => !(i.id === productId && i.size === size));
  cartWrite(items);
}
function cartClear(){ cartWrite([]); }
function cartCount(){ return cartRead().reduce((sum,i) => sum + i.qty, 0); }
function cartLines(){
  return cartRead().map(i => {
    const p = RASHWEAR_PRODUCTS.find(p => p.id === i.id);
    return p ? { ...i, product: p, lineTotal: p.price * i.qty } : null;
  }).filter(Boolean);
}
function cartSubtotal(){ return cartLines().reduce((s,l) => s + l.lineTotal, 0); }

function updateCartCount(){
  document.querySelectorAll("[data-cart-count]").forEach(el => {
    const n = cartCount();
    el.textContent = n;
    el.style.display = n > 0 ? "flex" : "none";
  });
  document.querySelectorAll("[data-cart-mini-total]").forEach(el => {
    el.textContent = fmtNPR(cartSubtotal());
  });
}

function showToast(msg){
  let toast = document.querySelector(".toast");
  if (!toast){
    toast = document.createElement("div");
    toast.className = "toast";
    document.body.appendChild(toast);
  }
  toast.textContent = msg;
  toast.classList.add("show");
  clearTimeout(window._toastTimer);
  window._toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
}

document.addEventListener("DOMContentLoaded", updateCartCount);
