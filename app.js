/* ─── NAVIGATION MOBILE TOGGLE ─────────────────────────── */
function toggleMenu() {
  const links = document.getElementById("navLinks");
  links.classList.toggle("open");
}

/* ─── NAVIGATION ACTIVE STATE LINKING ─────────────────── */
document.querySelectorAll('.nav-links a, .footer-col ul a').forEach(link => {
  link.addEventListener('click', function() {
    document.querySelectorAll('.nav-links a').forEach(el => el.classList.remove('active'));
    const href = this.getAttribute('href');
    const correspondNav = document.querySelector(`.nav-links a[href="${href}"]`);
    if(correspondNav) correspondNav.classList.add('active');
    document.getElementById("navLinks").classList.remove("open");
  });
});

/* ─── PRODUCT BROWSE SECTOR FILTERS ────────────────────── */
function filterSector(sector) {
  const cards = document.querySelectorAll('#productGrid .product-card');
  const pills = document.querySelectorAll('#browseFilters .filter-pill');
  
  pills.forEach(pill => {
    pill.classList.remove('active');
    if(sector === 'all' && pill.innerText.includes('All')) pill.classList.add('active');
    if(sector === 'home' && pill.innerText.includes('Home')) pill.classList.add('active');
    if(sector === 'apparel' && pill.innerText.includes('Apparel')) pill.classList.add('active');
    if(sector === 'pantry' && pill.innerText.includes('Pantry')) pill.classList.add('active');
    if(sector === 'decor' && pill.innerText.includes('Decor')) pill.classList.add('active');
  });

  cards.forEach(card => {
    if (sector === 'all' || card.getAttribute('data-sector') === sector) {
      card.classList.remove('hidden');
    } else {
      card.classList.add('hidden');
    }
  });
}

/* ─── FEATURED PRODUCT TABS SLIDER (CAROUSEL) ──────────── */
let currentTab = 0;
const slides = document.querySelectorAll(".featured-slide");
const indicators = document.querySelectorAll(".tab-indicator");
const buttons = document.querySelectorAll(".tab-nav-btn");
const totalTabs = slides.length;
let tabInterval;

function switchFeaturedTab(index) {
  slides.forEach(slide => slide.classList.remove("active"));
  indicators.forEach(ind => ind.classList.remove("active"));
  buttons.forEach(btn => btn.classList.remove("active"));
  
  if (slides[index]) slides[index].classList.add("active");
  if (indicators[index]) indicators[index].classList.add("active");
  if (buttons[index]) buttons[index].classList.add("active");
  
  currentTab = index;
}

function startTabAutoplay() {
  tabInterval = setInterval(() => {
    currentTab = (currentTab + 1) % totalTabs;
    switchFeaturedTab(currentTab);
  }, 5000);
}

function stopTabAutoplay() {
  clearInterval(tabInterval);
}

// Click event assignments for Carousel buttons
buttons.forEach(btn => {
  btn.addEventListener("click", () => {
    stopTabAutoplay();
    const index = parseInt(btn.dataset.tab);
    switchFeaturedTab(index);
    startTabAutoplay();
  });
});

indicators.forEach(ind => {
  ind.addEventListener("click", () => {
    stopTabAutoplay();
    const index = parseInt(ind.dataset.slide);
    switchFeaturedTab(index);
    startTabAutoplay();
  });
});

/* ─── EXPANDED TABBED MODAL SYSTEM DATA & LOGIC ────────── */
const modalData = {
  1: {
    title: "Burnay Drinking Glass",
    origin: "Vigan, Ilocos Sur, Philippines",
    tag: "Home Goods",
    tagClass: "home",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Burnay+Glass+Image",
    overview: "Each piece is handblown painstakingly out of recycled silica mixtures using regional heritage furnace parameters. Beautifully imperfect and robust for daily hydration use.",
    history: "Originating from pre-colonial methods introduced by early Chinese settlers in Ilocos, the traditional clay 'Burnay' jar frameworks paved the way for local glassblowing furnace applications in the early 20th century.",
    whereToFind: "Available directly through authorized craft collectives inside the Vigan Heritage Village network, or through partnered regional trade expositions across Metro Manila.",
    didYouKnow: "Because they are processed via dynamic manual furnaces without strict synthetic molds, no two glasses share identical microscopic weight measurements or bubble distribution designs!",
    details: {
      "Production Timeline": "7-10 Days Pure Handwork",
      "Material Standard": "100% Regionally Sourced Silica Elements",
      "Artisan Partners": "Ilocano Small Scale Collectives",
      "Care Instructions": "Dishwasher safe; avoid sudden boiling thermal shifts."
    }
  },
  2: {
    title: "Piña Weave T-Shirt",
    origin: "Kalibo, Aklan, Philippines",
    tag: "Apparel",
    tagClass: "fashion",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Pi%C3%B1a+Weave+Shirt+Image",
    overview: "Delicately interlaced structural organic thread combinations providing incredible airy structural breathing indexes perfect for humid or warm tropical climates.",
    history: "Piña weaving dates back centuries in Aklan, turning discarded pineapple agricultural leave layers into prized luxury textiles traditionally reserved for colonial elite garments.",
    whereToFind: "Handcrafted directly on-demand by native weaving associations in Kalibo, or through premier ethical boutique hubs in major urban hubs.",
    didYouKnow: "The raw leaf extraction requires scrapers made out of broken porcelain plates to safely strip away the outer leaf layer without snapping internal fiber microstructures!",
    details: {
      "Production Timeline": "14-20 Days Manual Scraping & Looming",
      "Material Standard": "Organic Aklan Red Pineapple Fiber Blend",
      "Artisan Partners": "Kalibo Heritage Weaving Associations",
      "Care Instructions": "Hand wash only; mild soap formula; line dry out of direct sun."
    }
  },
  3: {
    title: "Sukang Iloko",
    origin: "Ilocos Region, Philippines",
    tag: "Pantry Items",
    tagClass: "food",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Sukang+Iloko+Image",
    overview: "Naturally fermented dark sugar cane vinegar that delivers an incredibly robust sour bite backed with subtle earthy, herbal undertones perfect for culinary dressings.",
    history: "Born out of rural preservation frameworks in northern Luzon, this variant depends heavily on the integration of wild native Samak bark fragments to drive natural aging properties.",
    whereToFind: "Distributed straight from family farm cooperations around Ilocos Norte, packaged cleanly inside standardized glass vessels.",
    didYouKnow: "True Sukang Iloko gains a progressively deeper pitch-black color hue the longer it cures inside its dark community aging chambers!",
    details: {
      "Production Timeline": "3-6 Months Natural Barrel Curing",
      "Material Standard": "100% Pure Ilocos Sugarcane Pressings",
      "Artisan Partners": "Northern Luzon Grassroots Agri-Cooperatives",
      "Care Instructions": "Store at ambient room temperature away from direct solar radiation."
    }
  },
  4: {
    title: "Basey Banig Mat",
    origin: "Basey, Samar, Philippines",
    tag: "Home Goods",
    tagClass: "home",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Banig+Mat+Image",
    overview: "Intricately handwoven native floor mats constructed with dyed tikog stalks, displaying generational community design maps.",
    history: "Basey has served as the weaving center of Eastern Visayas since the early Spanish records, with techniques sustained inside cave networks where climate preserves reed moisture.",
    whereToFind: "Sourced through direct community craft hubs inside Samar or seasonal provincial trade events.",
    didYouKnow: "Weaving happens inside cool limestone caves because the natural underground moisture keeps the tikog fibers exceptionally pliable and prevents snapping!",
    details: {
      "Production Timeline": "12-15 Days Intricate Hand-Weaving",
      "Material Standard": "Locally Harvested and Sun-Dried Tikog Reeds",
      "Artisan Partners": "Basey Women's Weaving Guilds",
      "Care Instructions": "Wipe with clean damp cloth; air dry completely before storage."
    }
  },
  5: {
    title: "Capiz Shell Lotus Lantern",
    origin: "Samal, Bataan, Philippines",
    tag: "Decor/Handicrafts",
    tagClass: "decor",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C4522A?text=Capiz+Lantern+Image",
    overview: "A beautiful lotus-shaped lantern handcrafted using translucent marine windowpane oyster shells, casting a soft, warm geometric ambient glow.",
    history: "Before modern glass imports, Capiz shells were widely used in historical Filipino architecture for windows due to their durable translucent qualities, a craft preserved today through light decorations.",
    whereToFind: "Sourced straight from specialized coastal artisan collectives along Bataan shores or provincial craft networks.",
    didYouKnow: "Each raw oyster shell must be acid-washed and painstakingly baked in traditional kilns to achieve its distinct pearlescent sheen!",
    details: {
      "Production Timeline": "5-7 Days Shell Cutting & Brass Binding",
      "Material Standard": "100% Safely Harvested Windowpane Oyster Shells",
      "Artisan Partners": "Bataan Maritime Craft Collectives",
      "Care Instructions": "Dust lightly with a soft micro-fiber brush; do not apply harsh cleaning chemicals."
    }
  },
  6: {
    title: "Paete Wooden Table Sculpture",
    origin: "Paete, Laguna, Philippines",
    tag: "Decor/Handicrafts",
    tagClass: "decor",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C4522A?text=Paete+Woodcarving+Image",
    overview: "A meticulously detailed table centerpiece hand-chiseled out of sustainably sourced local wood blocks, depicting traditional countryside scenes.",
    history: "Declared the Carving Capital of the Philippines, Paete's woodcarving line extends to ancient roots, with methods passed down across generations since the late 1500s.",
    whereToFind: "Acquired through direct registration with generational family studios based along the main carving strip of Paete, Laguna.",
    didYouKnow: "The name Paete itself was born out of a misunderstanding over 'paet', the local Tagalog word for a carpenter's chisel tool!",
    details: {
      "Production Timeline": "10-14 Days Singlework Chiseling",
      "Material Standard": "Sustainably Foraged Local Acacia or Batikuling Wood",
      "Artisan Partners": "Generational Carving Families of Laguna",
      "Care Instructions": "Apply premium mineral wood oil once a year to keep wood texture rich."
    }
  },
  7: {
    title: "Abaca Twine Basket Vase",
    origin: "Daraga, Albay, Philippines",
    tag: "Decor/Handicrafts",
    tagClass: "decor",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C4522A?text=Abaca+Vase+Image",
    overview: "A durable structural vase shaped tightly from thick coiled abaca hemp stalks, providing excellent structural weight and organic aesthetic values.",
    history: "Abaca, famously known worldwide as Manila Hemp, is native to the Philippines. Bicolano makers have mastered converting this incredibly tough crop plant into home decor assets.",
    whereToFind: "Available directly through sustainable farming cooperatives in Albay province.",
    didYouKnow: "Abaca fiber possesses immense tensile structural strengths; it was historically preferred globally for heavy-duty naval ship rigging ropes!",
    details: {
      "Production Timeline": "3-5 Days Fiber Coiling & Frame Looming",
      "Material Standard": "Premium High-Grade Bicol Abaca Plant Hemp",
      "Artisan Partners": "Daraga Grassroots Farm Cooperatives",
      "Care Instructions": "Keep in dry ambient environments; vacuum on low setting to remove dust."
    }
  },
  8: {
    title: "Traditional Wire Filigree Ornament",
    origin: "Sorsogon City, Philippines",
    tag: "Decor/Handicrafts",
    tagClass: "decor",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C4522A?text=Filigree+Ornament+Image",
    overview: "An delicate decorative hanging ornament created by weaving fine metallic silver-plated copper wire loops into miniature traditional icons.",
    history: "Filigree work involves an incredibly slow, demanding technique where master smiths bond fine thread-like wires together, a heritage design style preserved in local community circles.",
    whereToFind: "Distributed directly through independent family micro-studios based out of Sorsogon.",
    didYouKnow: "The smiths twist two individual wire strands together and hammer them flat to give the micro-borders an iconic rope-like texture look!",
    details: {
      "Production Timeline": "4-6 Days Micro-Soldering Work",
      "Material Standard": "Silver-Plated Pure Soft Copper Wire Element Mix",
      "Artisan Partners": "Sorsogon Provincial Micro-Smith Studios",
      "Care Instructions": "Handle gently by the thick outer borders; store inside soft cloth bags."
    }
  }
};

let activeProductData = null;

function openModal(id) {
  const data = modalData[id];
  if (!data) return;
  activeProductData = data;

  const headerHtml = `
    <div class="modal-header">
      <div class="modal-img">
        <img src="${data.placeholderImg}" alt="${data.title}">
      </div>
      <div class="modal-intro">
        <span class="tag ${data.tagClass}">${data.tag}</span>
        <h2>${data.title}</h2>
        <div class="origin"><i class="fas fa-map-marker-alt"></i> ${data.origin}</div>
        <p>Proudly made by local craft groups using heirloom methods preserved through family lineages.</p>
      </div>
    </div>
  `;

  const tabsHtml = `
    <div class="modal-tab active" onclick="switchModalTab(this, 'overview')">Overview</div>
    <div class="modal-tab" onclick="switchModalTab(this, 'history')">Heritage Story</div>
    <div class="modal-tab" onclick="switchModalTab(this, 'where')">Where to Find</div>
    <div class="modal-tab" onclick="switchModalTab(this, 'dyk')">Did You Know?</div>
    <div class="modal-tab" onclick="switchModalTab(this, 'details')">Specifications</div>
  `;

  document.getElementById("modalDynamicContent").innerHTML = headerHtml;
  document.getElementById("modalTabsContainer").innerHTML = tabsHtml;
  
  // Load default tab panel view
  const panel = document.getElementById('modalPanelContent');
  panel.innerHTML = `<p>${data.overview}</p>`;

  document.getElementById("productModal").classList.add("open");
}

function switchModalTab(tabElement, tabType) {
  const tabs = document.querySelectorAll('#modalTabsContainer .modal-tab');
  tabs.forEach(t => t.classList.remove('active'));
  tabElement.classList.add('active');

  const panel = document.getElementById('modalPanelContent');
  if (!activeProductData) return;

  if (tabType === 'overview') {
    panel.innerHTML = `<p>${activeProductData.overview}</p>`;
  } else if (tabType === 'history') {
    panel.innerHTML = `<p>${activeProductData.history}</p>`;
  } else if (tabType === 'where') {
    panel.innerHTML = `<p>${activeProductData.whereToFind}</p>`;
  } else if (tabType === 'dyk') {
    panel.innerHTML = `<p><i class="fas fa-lightbulb" style="color:var(--gold); margin-right:6px;"></i> ${activeProductData.didYouKnow}</p>`;
  } else if (tabType === 'details') {
    let tableRows = '';
    for (const [key, value] of Object.entries(activeProductData.details)) {
      tableRows += `<tr><td>${key}</td><td>${value}</td></tr>`;
    }
    panel.innerHTML = `<table class=\"specs-tbl\">${tableRows}</table>`;
  }
}

function closeModal() {
  document.getElementById("productModal").classList.remove("open");
  activeProductData = null;
}

/* ─── INITIALIZATION ON RUNTIME ───────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  startTabAutoplay();
});