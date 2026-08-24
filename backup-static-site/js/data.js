/* ==========================================================================
   RASHWEAR — product catalog
   NOTE: These are placeholder garment icons/swatches (no photography yet).
   Replace `img` handling in main.js with real product photos when ready —
   see README.md "Adding real product photos".
   Prices are in NPR.
   ========================================================================== */

const RASHWEAR_CATEGORIES = {
  men:   { label:"Men",   subs:["Shirts","T-Shirts","Trousers","Jackets","Ethnic Wear"] },
  women: { label:"Women", subs:["Dresses","Tops & Shirts","Trousers","Knitwear","Ethnic Wear"] },
  kids:  { label:"Kids",  subs:["T-Shirts","Frocks","Jackets","Bottoms"] },
};

const RASHWEAR_PRODUCTS = [
  { id:"m-01", name:"Everyday Oxford Shirt",        cat:"men",   sub:"Shirts",       price:2450, compareAt:2900, icon:"shirt",    swatch:"indigo",   sizes:["S","M","L","XL"], desc:"A breathable cotton oxford built for Kathmandu's shifting weather — sharp enough for the office, easy enough for the weekend.", tags:["Bestseller"] },
  { id:"m-06", name:"Brushed Flannel Shirt",         cat:"men",   sub:"Shirts",       price:2650, icon:"shirt",    swatch:"olive",    sizes:["S","M","L","XL"], desc:"A soft brushed-cotton flannel shirt with a touch of warmth for cool Kathmandu evenings." },
  { id:"m-02", name:"Heavy Cotton Crewneck Tee",     cat:"men",   sub:"T-Shirts",     price:1350, icon:"tshirt",   swatch:"ink",      sizes:["S","M","L","XL","XXL"], desc:"Our heaviest tee — 240gsm combed cotton with a boxier, modern cut that holds its shape wash after wash." },
  { id:"m-07", name:"Striped Pocket Tee",            cat:"men",   sub:"T-Shirts",     price:1250, icon:"tshirt",   swatch:"indigo",   sizes:["S","M","L","XL"], desc:"A relaxed striped tee with a chest pocket, in soft single-jersey cotton." },
  { id:"m-04", name:"Tapered Chino Trouser",         cat:"men",   sub:"Trousers",     price:2200, icon:"trousers", swatch:"olive",    sizes:["30","32","34","36"], desc:"A tapered, mid-rise chino in brushed cotton twill — the trouser we reach for on repeat." },
  { id:"m-08", name:"Relaxed Denim Jean",            cat:"men",   sub:"Trousers",     price:2900, icon:"trousers", swatch:"indigo",   sizes:["30","32","34","36"], desc:"A relaxed straight jean in mid-wash denim, built to soften and fade with wear." },
  { id:"m-03", name:"Wool-Blend Overcoat",           cat:"men",   sub:"Jackets",      price:6900, icon:"jacket",   swatch:"stone2",   sizes:["M","L","XL"], desc:"A structured mid-length coat for Kathmandu winters, cut from a warm wool blend with a clean, minimal collar.", tags:["New"] },
  { id:"m-09", name:"Quilted Bomber Jacket",         cat:"men",   sub:"Jackets",      price:4200, icon:"jacket",   swatch:"ink",      sizes:["M","L","XL"], desc:"A lightly quilted bomber with a ribbed collar and cuffs, for layering through winter." },
  { id:"m-05", name:"Daura-Collar Kurta",            cat:"men",   sub:"Ethnic Wear",  price:2800, icon:"kurta",    swatch:"marigold", sizes:["S","M","L","XL"], desc:"A modern take on the traditional daura collar, tailored slim in soft handloom cotton for festivals and Tihar evenings.", tags:["Handloom"] },
  { id:"m-10", name:"Festival Waistcoat Set",        cat:"men",   sub:"Ethnic Wear",  price:3600, icon:"kurta",    swatch:"terracotta", sizes:["S","M","L","XL"], desc:"A kurta and waistcoat set in handloom cotton, tailored for Dashain and wedding season." },

  { id:"w-01", name:"Linen Wrap Dress",              cat:"women", sub:"Dresses",      price:3400, icon:"dress",    swatch:"terracotta", sizes:["XS","S","M","L"], desc:"An easy wrap silhouette in washed linen, cut long enough to layer through the seasons.", tags:["Bestseller"] },
  { id:"w-06", name:"Tiered Midi Dress",             cat:"women", sub:"Dresses",      price:3100, icon:"dress",    swatch:"dustyrose", sizes:["XS","S","M","L"], desc:"A soft tiered midi dress in lightweight cotton, easy to dress up or down." },
  { id:"w-02", name:"Relaxed Poplin Shirt",          cat:"women", sub:"Tops & Shirts",price:2100, icon:"shirt",    swatch:"dustyrose", sizes:["XS","S","M","L","XL"], desc:"An oversized poplin shirt with dropped shoulders — worn open over a tee or tucked in on its own." },
  { id:"w-07", name:"Boxy Cropped Blouse",           cat:"women", sub:"Tops & Shirts",price:1850, icon:"shirt",    swatch:"terracotta", sizes:["XS","S","M","L"], desc:"A boxy, cropped blouse in soft crepe with a relaxed collar — pairs easily with high-waist trousers." },
  { id:"w-04", name:"High-Waist Wide Trouser",       cat:"women", sub:"Trousers",     price:2600, icon:"trousers", swatch:"ink",      sizes:["XS","S","M","L"], desc:"A wide-leg trouser with a fitted waistband, cut from a fluid drape fabric that moves easily." },
  { id:"w-08", name:"Tapered Ankle Trouser",         cat:"women", sub:"Trousers",     price:2400, icon:"trousers", swatch:"stone2",   sizes:["XS","S","M","L"], desc:"A tapered ankle-length trouser in stretch twill, built for the office and beyond." },
  { id:"w-03", name:"Soft Cardigan Knit",            cat:"women", sub:"Knitwear",     price:2950, icon:"cardigan", swatch:"stone2",   sizes:["S","M","L"], desc:"A lightweight rib-knit cardigan for cool Kathmandu mornings, with horn-effect buttons and a relaxed drape." },
  { id:"w-09", name:"Turtleneck Sweater",            cat:"women", sub:"Knitwear",     price:2700, icon:"cardigan", swatch:"indigo",   sizes:["XS","S","M","L"], desc:"A fine-knit turtleneck sweater in a soft cotton-wool blend, made for layering." },
  { id:"w-05", name:"Handloom Kurta Set",            cat:"women", sub:"Ethnic Wear",  price:3800, icon:"kurta",    swatch:"indigo",   sizes:["S","M","L","XL"], desc:"A two-piece kurta set in hand-loomed cotton, finished with contrast piping along the neckline.", tags:["Handloom","New"] },
  { id:"w-10", name:"Embroidered Festival Kurta",    cat:"women", sub:"Ethnic Wear",  price:4200, icon:"kurta",    swatch:"marigold", sizes:["S","M","L","XL"], desc:"A hand-embroidered kurta for Teej and Dashain, finished with a matching dupatta." },

  { id:"k-01", name:"Little Explorer Tee",           cat:"kids",  sub:"T-Shirts",     price:850,  icon:"kidtee",   swatch:"marigold", sizes:["2-3Y","4-5Y","6-7Y","8-9Y"], desc:"Soft, pre-shrunk cotton tee built for climbing, running, and everything in between." },
  { id:"k-05", name:"Striped Crew Tee",              cat:"kids",  sub:"T-Shirts",     price:790,  icon:"kidtee",   swatch:"indigo",   sizes:["2-3Y","4-5Y","6-7Y","8-9Y"], desc:"A soft striped crewneck tee in breathable cotton, cut roomy for all-day play." },
  { id:"k-02", name:"Cotton Pinafore Frock",         cat:"kids",  sub:"Frocks",       price:1450, icon:"frock",    swatch:"dustyrose", sizes:["2-3Y","4-5Y","6-7Y"], desc:"A simple pinafore frock in brushed cotton with adjustable straps for room to grow into.", tags:["New"] },
  { id:"k-06", name:"Floral Party Frock",            cat:"kids",  sub:"Frocks",       price:1650, icon:"frock",    swatch:"terracotta", sizes:["2-3Y","4-5Y","6-7Y"], desc:"A twirly party frock in soft floral cotton, perfect for celebrations and photos." },
  { id:"k-03", name:"Fleece-Lined Jacket",           cat:"kids",  sub:"Jackets",      price:1950, icon:"jacket",   swatch:"olive",    sizes:["3-4Y","5-6Y","7-8Y"], desc:"A light fleece-lined jacket for chilly mornings at school, with a full front zip kids can manage alone." },
  { id:"k-07", name:"Puffer Jacket",                 cat:"kids",  sub:"Jackets",      price:2350, icon:"jacket",   swatch:"indigo",   sizes:["3-4Y","5-6Y","7-8Y"], desc:"A warm, lightweight puffer jacket for winter mornings, with a snug hood and zip pockets." },
  { id:"k-04", name:"Everyday Joggers",              cat:"kids",  sub:"Bottoms",      price:990,  icon:"trousers", swatch:"stone2",   sizes:["2-3Y","4-5Y","6-7Y","8-9Y"], desc:"Elastic-waist joggers in soft brushed cotton, built to survive the playground." },
  { id:"k-08", name:"Denim Dungarees",               cat:"kids",  sub:"Bottoms",      price:1550, icon:"trousers", swatch:"ink",      sizes:["2-3Y","4-5Y","6-7Y"], desc:"Classic denim dungarees with adjustable straps and roomy pockets for little explorers." },
];

const RASHWEAR_SWATCH = {
  indigo:     "#2E3A59",
  ink:        "#171614",
  stone2:     "#8B8578",
  olive:      "#5C6B4A",
  marigold:   "#D98E2B",
  terracotta: "#B5562F",
  dustyrose:  "#B07A78",
};

const RASHWEAR_CATLABEL = { men:"Men", women:"Women", kids:"Kids" };

/* ---- garment line-icon set (hand-drawn, generic silhouettes — no IP) ---- */
function rashwearIcon(type){
  const icons = {
    shirt: `<path d="M35 20 L45 12 L58 18 L64 12 L74 20 L68 32 L62 28 L62 88 L37 88 L37 28 L31 32 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M45 12 C45 20 65 20 58 12" fill="none" stroke="currentColor" stroke-width="2.4"/>`,
    tshirt: `<path d="M32 22 L44 14 L56 14 L68 22 L78 32 L68 40 L62 34 L62 88 L38 88 L38 34 L32 40 L22 32 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M44 14 C44 22 56 22 56 14" fill="none" stroke="currentColor" stroke-width="2.4"/>`,
    jacket: `<path d="M30 24 L44 13 L50 20 L56 13 L70 24 L76 36 L67 42 L63 34 L63 90 L37 90 L37 34 L33 42 L24 36 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M50 20 L50 90 M44 13 C44 22 50 24 50 20 C50 24 56 22 56 13" fill="none" stroke="currentColor" stroke-width="2.2"/>`,
    trousers: `<path d="M38 12 H62 L65 40 L70 90 H58 L52 46 L46 90 H34 L39 40 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M38 22 H62" stroke="currentColor" stroke-width="2.2"/>`,
    kurta: `<path d="M34 22 L46 12 L50 18 L54 12 L66 22 L70 34 L62 39 L60 32 L60 92 H40 L40 32 L38 39 L30 34 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M46 12 C46 22 54 22 54 12" fill="none" stroke="currentColor" stroke-width="2.2"/><path d="M50 40 L50 60" stroke="currentColor" stroke-width="1.6" stroke-dasharray="2 4"/>`,
    dress: `<path d="M40 12 L48 10 L50 16 L52 10 L60 12 L58 30 L70 90 H30 L42 30 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M48 10 C48 18 52 18 52 10" fill="none" stroke="currentColor" stroke-width="2.2"/>`,
    frock: `<path d="M38 14 L50 10 L62 14 L60 26 L70 86 H30 L40 26 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/><path d="M38 14 L34 22 M62 14 L66 22" stroke="currentColor" stroke-width="2.2"/>`,
    kidtee: `<path d="M36 24 L46 16 L54 16 L64 24 L72 32 L64 38 L60 33 L60 84 L40 84 L40 33 L36 38 L28 32 Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>`,
    cardigan: `<path d="M32 22 L44 13 L48 20 V90 H37 V32 L31 38 L22 30 Z" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linejoin="round"/><path d="M68 22 L56 13 L52 20 V90 H63 V32 L69 38 L78 30 Z" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linejoin="round"/>`,
  };
  return `<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" fill="none">${icons[type] || icons.tshirt}</svg>`;
}

function rashwearMediaStyle(swatchKey){
  const hex = RASHWEAR_SWATCH[swatchKey] || RASHWEAR_SWATCH.stone2;
  return `background:${hex}1A; color:${hex};`;
}

function fmtNPR(n){
  return "Rs " + Number(n).toLocaleString("en-IN");
}
