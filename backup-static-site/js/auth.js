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

function usersRead(){
  try{ return JSON.parse(localStorage.getItem("rashwear_users") || "[]"); }
  catch(e){ return []; }
}
function usersWrite(list){ localStorage.setItem("rashwear_users", JSON.stringify(list)); }

function sessionSet(session){
  localStorage.setItem("rashwear_session", JSON.stringify(session));
  renderAccountState();
}
function authCurrent(){
  try{ return JSON.parse(localStorage.getItem("rashwear_session")); }
  catch(e){ return null; }
}
function authLogout(){
  localStorage.removeItem("rashwear_session");
  renderAccountState();
  window.location.href = "index.html";
}

function authSignup(name, email, phone, password){
  const users = usersRead();
  if (users.find(u => u.email.toLowerCase() === email.toLowerCase())){
    return { ok:false, error:"An account with this email already exists — try logging in instead." };
  }
  users.push({ name, email, phone, password });
  usersWrite(users);
  sessionSet({ name, email, phone });
  return { ok:true };
}

function authLogin(email, password){
  const users = usersRead();
  const match = users.find(u => u.email.toLowerCase() === email.toLowerCase() && u.password === password);
  if (!match){
    return { ok:false, error:"That email and password don't match any account." };
  }
  sessionSet({ name:match.name, email:match.email, phone:match.phone });
  return { ok:true };
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
            <a href="account.html">My account</a>
            <button type="button" data-logout>Logout</button>
          </div>
        </div>`;
      const chip = slot.querySelector("[data-account-chip]");
      chip.addEventListener("click", (e) => { e.stopPropagation(); chip.classList.toggle("open"); });
      slot.querySelector("[data-logout]").addEventListener("click", authLogout);
    } else {
      slot.innerHTML = `<a href="account.html" class="utility-login">Login</a>`;
    }
  });
}

document.addEventListener("click", () => {
  document.querySelectorAll(".account-chip.open").forEach(c => c.classList.remove("open"));
});

document.addEventListener("DOMContentLoaded", renderAccountState);
