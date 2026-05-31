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
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Piña+Weave+Shirt+Image",
    overview: "Delicately interlaced structural organic thread combinations providing incredible airy structural breathing indexes perfect for humid or warm tropical climates.",
    history: "Piña weaving dates back centuries in Aklan, turning discarded pineapple agricultural leave layers into prized luxury textiles traditionally reserved for colonial elite garments.",
    whereToFind: "Handcrafted directly on-demand by native weaving associations in Kalibo, alongside verified premium local trade boutiques.",
    didYouKnow: "Extracting the premium 'Liniwan' fibers manually requires scraping pineapple leaves with broken porcelain plate fragments to protect the raw strand elasticity!",
    details: {
      "Production Timeline": "14-20 Weaving Days",
      "Material Standard": "Organic Aklan Pineapple Leaf Fiber & Fine Local Cotton Threads",
      "Artisan Partners": "Kalibo Heritage Weaver Unions",
      "Care Instructions": "Hand wash only with mild soap detergents; line dry out of aggressive direct sunlight."
    }
  },
  3: {
    title: "Traditional Sukang Iloko",
    origin: "Ilocos Region, Philippines",
    tag: "Pantry Items",
    tagClass: "food",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Sukang+Iloko+Image",
    overview: "Premium sugarcane extracts processed through historical aging styles mixed carefully with native seasoning tree variants to complete a highly versatile vinegar condiment profile.",
    history: "A fundamental cornerstone of northern Philippine culinary history, Sukang Iloko has been produced in household backyards for generations utilizing specialized dark earthenware storage fermentation processes.",
    whereToFind: "Sourced straight from certified small-batch family farm cooperatives across the Ilocos Norte agricultural processing zones.",
    didYouKnow: "The characteristically deep, dark color and unique earthy dry profile are produced naturally by infusing bark and leaves from the native Samak tree during initial fermentation steps!",
    details: {
      "Fermentation Cycle": "6 to 12 Months Minimum Aging",
      "Material Standard": "Pure Sugarcane Juice & Local Samak Plant Bark Extracts",
      "Artisan Partners": "Northern Luzon Small-Scale Farmers",
      "Shelf Life": "Indefinite shelf stability; profile complexifies naturally over continuous time frames."
    }
  },
  4: {
    title: "Basey Banig Mat",
    origin: "Basey, Samar, Philippines",
    tag: "Home Goods",
    tagClass: "crafts",
    placeholderImg: "https://placehold.co/600x400/2B1F17/C9A97A?text=Banig+Mat+Image",
    overview: "Intricately handwoven reed mats featuring unique geometric, historical, and floral community layout designs that display raw visual Filipino artistry.",
    history: "The weavers of Basey have engineered cave-dwelling workspace operations for generations. Gathering inside cool local environments optimizes reed flexibility during production.",
    whereToFind: "Acquired straight from regional community weavers in Samar or via targeted ethical fair-trade e-commerce hubs.",
    didYouKnow: "Weaving complex banig structures inside humid local cave channels prevents the Tikog leaves from drying out and snapping mid-braid!",
    details: {
      "Production Timeline": "3-4 Weeks Depending on Visual Patterns",
      "Material Standard": "Sustainably Harvested Natural Tikog Reed Strands",
      "Artisan Partners": "Basey Women's Weaving Collectives",
      "Care Instructions": "Keep dry; wipe down cleanly with simple dry cloths if exposed to workspace moisture."
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
        <p>${data.overview}</p>
      </div>
    </div>
    <div class="modal-tabs" id="modalTabsContainer">
      <div class="modal-tab active" onclick="switchModalTab(this, 'overview')">Overview</div>
      <div class="modal-tab" onclick="switchModalTab(this, 'history')">History</div>
      <div class="modal-tab" onclick="switchModalTab(this, 'where')">Where to Find?</div>
      <div class="modal-tab" onclick="switchModalTab(this, 'dyk')">Did You Know?</div>
      <div class="modal-tab" onclick="switchModalTab(this, 'details')">Details</div>
    </div>
    <div class="modal-panel active" id="modalPanelContent">
      <p>${data.overview}</p>
    </div>
  `;
  
  document.getElementById("modalDynamicContent").innerHTML = headerHtml;
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
    panel.innerHTML = `<table class="specs-tbl">${tableRows}</table>`;
  }
}

function closeModal() {
  document.getElementById("productModal").classList.remove("open");
  activeProductData = null;
}

/* ─── RUNTIME INITIALIZATION ────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  startTabAutoplay();
});