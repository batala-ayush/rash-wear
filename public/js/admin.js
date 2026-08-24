const adminState = {
  products: [],
  orders: [
    { id: "RW-1203", customer: "Sita Rai", total: 5600, status: "Pending" },
    { id: "RW-1202", customer: "Aashish K.", total: 3900, status: "Confirmed" },
    { id: "RW-1201", customer: "Meena Gurung", total: 7200, status: "Shipped" }
  ],
  users: [
    { name: "Sabina Sharma", email: "sabina@example.com", role: "Customer", status: "Active" },
    { name: "Ram Joshi", email: "ram@example.com", role: "Customer", status: "Active" },
    { name: "Admin User", email: "admin@rashwear.com.np", role: "Admin", status: "Active" }
  ],
  settings: {}
};
let adminCategories = {};
let activeProductFilter = "";
let activeProductCategory = "all";
let activeProductSubcategory = "all";
let activeProductStockSort = "default";
let lowStockOnly = false;
let editingProductId = null;

async function adminApi(url, options = {}) {
  const headers = { Accept: "application/json", ...(options.headers || {}) };
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  if (csrf) headers["X-CSRF-TOKEN"] = csrf;
  const response = await fetch(url, { ...options, headers });
  const body = await response.json().catch(() => ({}));
  if (!response.ok) throw new Error(body.message || "The database request failed.");
  return body;
}

function formatCurrency(value) { return `Rs ${Number(value).toLocaleString("en-IN")}`; }
function adminShowToast(message) { const toast = document.createElement("div"); toast.className = "toast show"; toast.textContent = message; document.body.appendChild(toast); setTimeout(() => toast.remove(), 2400); }

async function loadAdminDatabase() {
  const data = await adminApi("/api/admin/catalog");
  adminCategories = (data.categories || []).reduce((result, category) => {
    result[category.slug] = { id: category.id, label: category.name, subs: (data.subcategories || []).filter(item => item.category_id === category.id).map(item => ({ id: item.id, name: item.name })) };
    return result;
  }, {});
  adminState.products = (data.products || []).map(product => {
    const category = data.categories.find(item => item.id === product.category_id);
    const subcategory = data.subcategories.find(item => item.id === product.subcategory_id);
    const stockBySize = (product.variants || []).reduce((result, variant) => { result[variant.size] = Number(variant.stock_quantity) || 0; return result; }, {});
    return { ...product, category: category?.name || "", subcategory: subcategory?.name || "", stockBySize, sizes: Object.keys(stockBySize), stock: Object.values(stockBySize).reduce((total, value) => total + value, 0) };
  });
  if (document.body.dataset.role === "owner") {
    const users = await adminApi("/api/admin/users");
    adminState.users = users.users || [];
  }
}

function renderProductList() {
  const list = document.querySelector("[data-product-list]");
  if (!list) return;
  const products = adminState.products.filter(product => `${product.name} ${product.category} ${product.subcategory} ${product.status}`.toLowerCase().includes(activeProductFilter) && (activeProductCategory === "all" || product.category === activeProductCategory) && (activeProductSubcategory === "all" || product.subcategory === activeProductSubcategory) && (!lowStockOnly || product.stock <= 10));
  if (activeProductStockSort === "stock-asc") products.sort((a, b) => a.stock - b.stock);
  if (activeProductStockSort === "stock-desc") products.sort((a, b) => b.stock - a.stock);
  list.innerHTML = products.map(product => `<tr><td>${product.name}</td><td>${product.category}<br><small>${product.subcategory}</small></td><td>${formatCurrency(product.price)}</td><td><div class="inventory-cell"><div class="inventory-adjuster"><button type="button" class="inventory-step" data-admin-action="adjust-stock" data-product-id="${product.id}" data-stock-change="-1">-</button><input type="number" min="0" value="${product.stock}" data-stock-input="${product.id}"><button type="button" class="inventory-step" data-admin-action="adjust-stock" data-product-id="${product.id}" data-stock-change="1">+</button></div><span class="stock-indicator ${product.stock <= 0 ? "out" : product.stock <= 10 ? "low" : "healthy"}">${product.stock <= 0 ? "Out of stock" : product.stock <= 10 ? "Low stock" : "In stock"}</span></div></td><td>${product.status}</td><td><button type="button" class="btn btn-outline" data-admin-action="edit-product" data-product-id="${product.id}">Edit</button></td></tr>`).join("");
}

function renderCategoryOptions() {
  const categories = Object.values(adminCategories);
  const options = categories.map(category => `<option value="${category.label}">${category.label}</option>`).join("");
  const productCategory = document.querySelector("[data-product-field='category']");
  const filterCategory = document.querySelector("[data-product-category-filter]");
  if (productCategory) productCategory.innerHTML = `<option value="">Choose a category</option>${options}`;
  if (filterCategory) filterCategory.innerHTML = `<option value="all">All categories</option>${options}`;
  const categoryForSubcategory = document.querySelector("[data-subcategory-category]");
  if (categoryForSubcategory) categoryForSubcategory.innerHTML = Object.entries(adminCategories).map(([key, category]) => `<option value="${key}">${category.label}</option>`).join("");
  renderSubcategoryFilter();
  renderCategoryList();
}

function renderSubcategoryOptions(categoryName, selected = "") { const select = document.querySelector("[data-product-field='subcategory']"); if (!select) return; const category = Object.values(adminCategories).find(item => item.label === categoryName); const options = category?.subs || []; select.innerHTML = `<option value="">Choose a subcategory</option>${options.map(item => `<option value="${item.name}">${item.name}</option>`).join("")}`; select.disabled = !options.length; select.value = selected; }
function renderSubcategoryFilter() { const select = document.querySelector("[data-product-subcategory-filter]"); if (!select) return; const category = Object.values(adminCategories).find(item => item.label === activeProductCategory); const options = category?.subs || []; select.innerHTML = `<option value="all">All subcategories</option>${options.map(item => `<option value="${item.name}">${item.name}</option>`).join("")}`; select.disabled = !category; select.value = options.some(item => item.name === activeProductSubcategory) ? activeProductSubcategory : "all"; }
function renderCategoryList() { const list = document.querySelector("[data-category-list]"); if (!list) return; list.innerHTML = Object.entries(adminCategories).map(([key, category]) => `<div class="category-chip"><div class="category-chip-head"><strong>${category.label}</strong><button type="button" class="category-delete" data-admin-action="delete-category" data-category-key="${key}">x</button></div><div class="category-sub-list">${category.subs.map(sub => `<span>${sub.name}<button type="button" class="subcategory-delete" data-admin-action="delete-subcategory" data-category-key="${key}" data-subcategory="${sub.name}">x</button></span>`).join("")}</div></div>`).join(""); }
async function addCategory(event) { event.preventDefault(); const label = event.target.querySelector("[data-category-name]").value.trim(); const subs = [...new Set(event.target.querySelector("[data-category-subcategories]").value.split(",").map(value => value.trim()).filter(Boolean))]; if (!label || !subs.length || !window.confirm(`Add ${label} category?`)) return; try { await adminApi("/api/categories", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name: label, subcategories: subs }) }); await loadAdminDatabase(); event.target.reset(); renderCategoryOptions(); adminShowToast("Category saved to the database."); } catch (error) { adminShowToast(error.message); } }
async function addSubcategory(event) { event.preventDefault(); const key = event.target.querySelector("[data-subcategory-category]").value; const category = adminCategories[key]; const name = event.target.querySelector("[data-subcategory-name]").value.trim(); if (!category || !name || category.subs.some(item => item.name.toLowerCase() === name.toLowerCase()) || !window.confirm(`Add ${name} subcategory?`)) return; try { await adminApi("/api/subcategories", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ category_id: category.id, name }) }); await loadAdminDatabase(); event.target.reset(); renderCategoryOptions(); adminShowToast("Subcategory saved to the database."); } catch (error) { adminShowToast(error.message); } }
async function deleteCategory(key) { const category = adminCategories[key]; if (!category || !window.confirm(`Delete ${category.label}?`)) return; try { await adminApi(`/api/categories/${category.id}`, { method: "DELETE" }); await loadAdminDatabase(); renderCategoryOptions(); renderProductList(); adminShowToast("Category deleted."); } catch (error) { adminShowToast(error.message); } }
async function deleteSubcategory(key, name) { const category = adminCategories[key]; const sub = category?.subs.find(item => item.name === name); if (!sub || !window.confirm(`Delete ${name}?`)) return; try { await adminApi(`/api/subcategories/${sub.id}`, { method: "DELETE" }); await loadAdminDatabase(); renderCategoryOptions(); renderProductList(); adminShowToast("Subcategory deleted."); } catch (error) { adminShowToast(error.message); } }
function getSizeValues() { return [...new Set((document.querySelector("[data-product-field='sizes']")?.value || "").split(",").map(value => value.trim()).filter(Boolean))]; }
function renderSizeStockFields(sizes = getSizeValues(), stockBySize = {}) { const container = document.querySelector("[data-size-stock-fields]"); if (!container) return; container.innerHTML = sizes.length ? sizes.map(size => `<label class="size-stock-row"><span>${size}</span><input type="number" min="0" value="${Number(stockBySize[size]) || 0}" data-size-stock="${size}"></label>`).join("") : `<p class="form-hint">Add sizes above to set quantities.</p>`; }
function fillProductForm(product = null) {
  const form = document.querySelector("[data-product-form]");
  if (!form) return;
  editingProductId = product?.id || null;
  form.reset();
  form.querySelector("[data-product-id]").value = product?.id || "";
  document.querySelector("[data-product-form-title]").textContent = product ? "Edit product" : "Add new product";
  form.querySelector("[data-product-field='image']").required = !product;
  if (!product) { renderSubcategoryOptions(""); renderSizeStockFields([]); return; }
  form.querySelector("[data-product-field='name']").value = product.name;
  form.querySelector("[data-product-field='category']").value = product.category;
  renderSubcategoryOptions(product.category, product.subcategory);
  form.querySelector("[data-product-field='price']").value = product.price;
  form.querySelector("[data-product-field='sizes']").value = product.sizes.join(", ");
  renderSizeStockFields(product.sizes, product.stockBySize);
  form.querySelector("[data-product-field='status']").value = product.status;
  form.querySelector("[data-product-field='description']").value = product.description || "";
}

async function readImageData() { const file = document.querySelector("[data-product-field='image']")?.files[0]; if (!file) return ""; return new Promise((resolve, reject) => { const reader = new FileReader(); reader.onload = () => resolve(reader.result); reader.onerror = reject; reader.readAsDataURL(file); }); }
async function saveProductFromForm() {
  const form = document.querySelector("[data-product-form]");
  const sizes = getSizeValues();
  const stockBySize = Object.fromEntries([...document.querySelectorAll("[data-size-stock]")].map(input => [input.dataset.sizeStock, Math.max(0, Number(input.value) || 0)]));
  const category = Object.values(adminCategories).find(item => item.label === form.querySelector("[data-product-field='category']").value);
  const subcategory = category?.subs.find(item => item.name === form.querySelector("[data-product-field='subcategory']").value);
  const imageData = await readImageData();
  const existing = adminState.products.find(product => product.id == editingProductId);
  if (!category || !subcategory || !sizes.length || (!imageData && !existing?.image)) { adminShowToast("Complete category, subcategory, image, sizes, and inventory."); return; }
  if (!window.confirm(`${existing ? "Update" : "Add"} ${form.querySelector("[data-product-field='name']").value.trim()}?`)) return;
  try {
    await adminApi(existing ? `/api/products/${existing.id}` : "/api/products", { method: existing ? "PATCH" : "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name: form.querySelector("[data-product-field='name']").value.trim(), category_id: category.id, subcategory_id: subcategory.id, price: Number(form.querySelector("[data-product-field='price']").value) || 0, description: form.querySelector("[data-product-field='description']").value.trim(), status: form.querySelector("[data-product-field='status']").value, sizes: sizes.map(size => ({ name: size, stock: stockBySize[size] || 0 })), image_data: imageData }) });
    await loadAdminDatabase();
    editingProductId = null;
    form.reset();
    renderCategoryOptions();
    renderProductList();
    updateSummaryCounts();
    adminShowToast("Product saved to the database.");
  } catch (error) { adminShowToast(error.message); }
}
async function adjustStock(productId, change) { try { await adminApi(`/api/products/${productId}/inventory`, { method: "PATCH", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ change }) }); await loadAdminDatabase(); renderProductList(); updateSummaryCounts(); } catch (error) { adminShowToast(error.message); } }

function updateSummaryCounts() { const values = { products: adminState.products.length, lowStock: adminState.products.filter(product => product.stock <= 10).length, openOrders: 0, users: 0 }; Object.entries(values).forEach(([key, value]) => { const element = document.querySelector(`[data-admin-summary='${key}']`); if (element) element.textContent = value; }); }
async function loadSettings() { const result = await adminApi("/api/settings"); Object.entries(result.settings || {}).forEach(([key, value]) => { const field = document.querySelector(`[data-setting='${key}']`); if (field) field.type === "checkbox" ? field.checked = Boolean(value) : field.value = value; }); }
async function saveSettings() { const settings = {}; document.querySelectorAll("[data-setting]").forEach(field => { settings[field.dataset.setting] = field.type === "checkbox" ? field.checked : field.value.trim(); }); try { await adminApi("/api/settings", { method: "PATCH", headers: { "Content-Type": "application/json" }, body: JSON.stringify(settings) }); adminShowToast("Settings saved to the database."); } catch (error) { adminShowToast(error.message); } }
function renderOrderList() { const list = document.querySelector("[data-order-list]"); if (list) list.innerHTML = adminState.orders.map(order => `<tr><td>${order.id}</td><td>${order.customer}</td><td>${formatCurrency(order.total)}</td><td>${order.status}</td><td>-</td></tr>`).join(""); }
function renderUserList() { const list = document.querySelector("[data-user-list]"); if (list) list.innerHTML = adminState.users.map(user => `<tr><td>${user.name}</td><td>${user.email}</td><td>${user.role}</td><td>${user.status}</td><td>-</td></tr>`).join(""); }
function initAdminPage() {
  document.body.addEventListener("click", event => { const button = event.target.closest("[data-admin-action]"); if (!button) return; if (button.dataset.adminAction === "adjust-stock") adjustStock(button.dataset.productId, Number(button.dataset.stockChange)); if (button.dataset.adminAction === "filter-low-stock") { lowStockOnly = !lowStockOnly; renderProductList(); } if (button.dataset.adminAction === "delete-category") deleteCategory(button.dataset.categoryKey); if (button.dataset.adminAction === "delete-subcategory") deleteSubcategory(button.dataset.categoryKey, button.dataset.subcategory); if (button.dataset.adminAction === "add-product") { document.querySelector("[data-admin-tab='products']")?.click(); fillProductForm(); } if (button.dataset.adminAction === "edit-product") fillProductForm(adminState.products.find(product => product.id == button.dataset.productId)); if (button.dataset.adminAction === "toggle-maintenance") { const field = document.querySelector("[data-setting='maintenance']"); if (field) { field.checked = !field.checked; saveSettings(); } } if (button.dataset.adminAction === "toggle-checkout") { const field = document.querySelector("[data-setting='disableCheckout']"); if (field) { field.checked = !field.checked; saveSettings(); } } });
  document.querySelectorAll("[data-admin-tab]").forEach(tab => tab.addEventListener("click", () => { document.querySelectorAll(".admin-tab").forEach(item => item.classList.toggle("active", item === tab)); document.querySelectorAll(".admin-panel").forEach(panel => panel.classList.toggle("active", panel.dataset.adminPanel === tab.dataset.adminTab)); }));
  document.querySelector("[data-product-form]")?.addEventListener("submit", event => { event.preventDefault(); saveProductFromForm(); });
  document.querySelector("[data-product-search]")?.addEventListener("input", event => { activeProductFilter = event.target.value.toLowerCase(); renderProductList(); });
  document.querySelector("[data-product-category-filter]")?.addEventListener("change", event => { activeProductCategory = event.target.value; activeProductSubcategory = "all"; renderSubcategoryFilter(); renderProductList(); });
  document.querySelector("[data-product-subcategory-filter]")?.addEventListener("change", event => { activeProductSubcategory = event.target.value; renderProductList(); });
  document.querySelector("[data-product-stock-sort]")?.addEventListener("change", event => { activeProductStockSort = event.target.value; renderProductList(); });
  document.querySelector("[data-product-field='category']")?.addEventListener("change", event => renderSubcategoryOptions(event.target.value));
  document.querySelector("[data-product-field='sizes']")?.addEventListener("input", () => renderSizeStockFields());
  document.querySelector("[data-category-form]")?.addEventListener("submit", addCategory);
  document.querySelector("[data-subcategory-form]")?.addEventListener("submit", addSubcategory);
  document.querySelector("[data-admin-action='save-settings']")?.addEventListener("click", saveSettings);
  loadAdminDatabase().then(() => { renderCategoryOptions(); renderProductList(); renderOrderList(); renderUserList(); updateSummaryCounts(); return loadSettings(); }).catch(error => adminShowToast(error.message));
}
document.addEventListener("DOMContentLoaded", initAdminPage);
