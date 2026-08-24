/* ==========================================================================
   RASHWEAR — accounts (demo/prototype auth)

   IMPORTANT — read before going live:
   This is a front-end-only demo so the site has a working login/signup flow
   with no backend to set up. Accounts and passwords are stored in the
   visitor's own browser (localStorage), in plain text, and are NOT shared
   between devices or visible to you as the store owner.
   That is fine for demoing the site, but it is NOT secure enough for real
   customer accounts — anyone using the same browser/device can read them.
   Before launch, replace this with a real auth backend such as Firebase
   Authentication, Supabase Auth, or a custom server that hashes passwords
   (e.g. with bcrypt) and issues real sessions. See README.md → "Accounts &
   login" for details.
   ========================================================================== */

let currentUser = null;

async function authApi(url, options = {}) {
  const headers = { Accept: "application/json", ...(options.headers || {}) };
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  if (csrf) headers["X-CSRF-TOKEN"] = csrf;
  const response = await fetch(url, { ...options, headers });
  const body = await response.json().catch(() => ({}));
  if (!response.ok) throw new Error(body.message || "Authentication request failed.");
  return body;
}

function authCurrent(){ return currentUser; }
async function authLogout(){ await authApi("/api/auth/logout", { method: "POST" }); currentUser = null; renderAccountState(); window.location.href = "/"; }

function authSignup(){ return null; }
function authLogin(){ return null; }

function updateAccountPage(){
  const user = authCurrent();
  const authPanel = document.querySelector("[data-auth-panel]");
  const dashboardPanel = document.querySelector("[data-dashboard-panel]");
  if (user){
    if (authPanel) authPanel.style.display = "none";
    if (dashboardPanel) dashboardPanel.style.display = "block";
    const nameEl = document.querySelector("[data-dash-name]");
    const emailEl = document.querySelector("[data-dash-email]");
    if (nameEl) nameEl.textContent = user.name.split(" ")[0] || user.email;
    if (emailEl) emailEl.textContent = user.email || "";
    const logoutBtn = document.querySelector("[data-logout-btn]");
    if (logoutBtn) logoutBtn.addEventListener("click", authLogout);
  } else {
    if (authPanel) authPanel.style.display = "";
    if (dashboardPanel) dashboardPanel.style.display = "none";
  }
}

function setFormError(container, message){
  if (!container) return;
  container.textContent = message;
}

function initAccountTabs(){
  const tabs = document.querySelectorAll("[data-tab]");
  const panels = document.querySelectorAll("[data-tab-panel]");
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      const target = tab.dataset.tab;
      tabs.forEach(t => t.classList.toggle("active", t === tab));
      panels.forEach(panel => panel.classList.toggle("active", panel.dataset.tabPanel === target));
    });
  });
}

function initAccountForms(){
  const loginForm = document.querySelector("form[data-tab-panel='login']");
  const signupForm = document.querySelector("form[data-tab-panel='signup']");
  const loginError = document.querySelector("[data-login-error]");
  const signupError = document.querySelector("[data-signup-error]");

  if (loginForm){
    loginForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      setFormError(loginError, "");
      const email = loginForm.querySelector("[name='email']")?.value.trim() || "";
      const password = loginForm.querySelector("[name='password']")?.value || "";
      try { currentUser = (await authApi("/api/auth/login", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ email, password }) })).user; window.location.href = currentUser.role === "owner" || currentUser.role === "shop_manager" ? "/admin" : "/"; }
      catch (error) { setFormError(loginError, error.message); }
    });
  }

  if (signupForm){
    signupForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      setFormError(signupError, "");
      const name = signupForm.querySelector("[name='name']")?.value.trim() || "";
      const email = signupForm.querySelector("[name='email']")?.value.trim() || "";
      const phone = signupForm.querySelector("[name='phone']")?.value.trim() || "";
      const password = signupForm.querySelector("[name='password']")?.value || "";
      try { currentUser = (await authApi("/api/auth/register", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name, email, phone, password }) })).user; window.location.href = "/"; }
      catch (error) { setFormError(signupError, error.message); }
    });
  }
}

/* ---- header state: swap "Login" for an account chip once signed in ---- */
function renderAccountState(){
  const user = authCurrent();
  document.querySelectorAll("[data-account-slot]").forEach(slot => {
    if (user){
      const firstName = user.name.split(" ")[0];
      slot.innerHTML = `
        <div class="account-chip" data-account-chip>
          <span>Hi, ${firstName}</span>
          <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="m6 9 6 6 6-6"/></svg>
          <div class="account-drop">
            <a href="/account">My account</a>
            <button type="button" data-logout>Logout</button>
          </div>
        </div>`;
      const chip = slot.querySelector("[data-account-chip]");
      chip.addEventListener("click", (e) => { e.stopPropagation(); chip.classList.toggle("open"); });
      slot.querySelector("[data-logout]").addEventListener("click", authLogout);
    } else {
      slot.innerHTML = `<a href="/account" class="utility-login">Login</a>`;
    }
  });
}

document.addEventListener("click", () => {
  document.querySelectorAll(".account-chip.open").forEach(c => c.classList.remove("open"));
});

document.addEventListener("DOMContentLoaded", () => {
  authApi("/api/auth/current").then(data => { currentUser = data.user; renderAccountState(); updateAccountPage(); initAccountTabs(); initAccountForms(); }).catch(() => { currentUser = null; renderAccountState(); updateAccountPage(); initAccountTabs(); initAccountForms(); });
});
