/* ─── NAVIGATION MOBILE TOGGLE ─────────────────────────── */
function toggleMenu() {
  const links = document.getElementById("navLinks");
  if (links) links.classList.toggle("open");
}

/* ─── NAVIGATION ACTIVE STATE LINKING ─────────────────── */
document.querySelectorAll('.nav-links a, .footer-col ul a').forEach(link => {
  link.addEventListener('click', function() {
    document.querySelectorAll('.nav-links a').forEach(el => el.classList.remove('active'));
    const href = this.getAttribute('href');
    const correspondNav = document.querySelector(`.nav-links a[href="${href}"]`);
    if(correspondNav) correspondNav.classList.add('active');
    
    const navLinksContainer = document.getElementById("navLinks");
    if (navLinksContainer) navLinksContainer.classList.remove("open");
  });
});

/* ─── HAMBURGER MENU EVENT LISTENER ────────────────────── */
const hamburger = document.getElementById('hamburger');
if (hamburger) {
  hamburger.addEventListener('click', toggleMenu);
}

/* ─── AUTHENTICATION SYSTEM ─────────────────────────────── */
let isLoginMode = true;

function checkAuthStatus() {
  fetch(`${API_URL}?action=check_auth`)
    .then(res => res.json())
    .then(data => {
      updateAuthNav(data.logged_in, data.user_email);
    })
    .catch(err => console.error('Auth check failed:', err));
}

function updateAuthNav(loggedIn, userEmail) {
  const container = document.getElementById('authNavContainer');
  if (!container) return;
  
  if (loggedIn && userEmail) {
    container.innerHTML = `
      <div class="user-info">
        <span class="user-email">${userEmail}</span>
        <button class="logout-btn" onclick="handleLogout()">Logout</button>
      </div>
    `;
  } else {
    container.innerHTML = `
      <button class="auth-nav-btn" onclick="openAuthModal()">Login</button>
    `;
  }
}

function openAuthModal() {
  const modal = document.getElementById('authModal');
  if (modal) modal.classList.add('open');
  isLoginMode = true;
  updateAuthModalUI();
}

function closeAuthModal() {
  const modal = document.getElementById('authModal');
  if (modal) modal.classList.remove('open');
  const messageDiv = document.getElementById('authMessage');
  if (messageDiv) messageDiv.innerHTML = '';
  const form = document.getElementById('authForm');
  if (form) form.reset();
}

function toggleAuthMode() {
  isLoginMode = !isLoginMode;
  updateAuthModalUI();
  const messageDiv = document.getElementById('authMessage');
  if (messageDiv) messageDiv.innerHTML = '';
  const form = document.getElementById('authForm');
  if (form) form.reset();
}

function updateAuthModalUI() {
  const title = document.getElementById('authTitle');
  const subtitle = document.getElementById('authSubtitle');
  const submitBtn = document.getElementById('authSubmitBtn');
  const switchText = document.getElementById('authSwitchText');
  const switchLink = document.getElementById('authSwitchLink');
  
  if (isLoginMode) {
    title.textContent = 'Login';
    subtitle.textContent = 'Sign in to vote on your favorite products';
    submitBtn.textContent = 'Login';
    switchText.textContent = "Don't have an account?";
    switchLink.textContent = 'Register';
  } else {
    title.textContent = 'Register';
    subtitle.textContent = 'Create an account to vote on your favorite products';
    submitBtn.textContent = 'Register';
    switchText.textContent = 'Already have an account?';
    switchLink.textContent = 'Login';
  }
}

function handleAuthSubmit(e) {
  e.preventDefault();
  
  const email = document.getElementById('authEmail').value;
  const password = document.getElementById('authPassword').value;
  const messageDiv = document.getElementById('authMessage');
  
  const action = isLoginMode ? 'login' : 'register';
  
  fetch(`${API_URL}?action=${action}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      messageDiv.innerHTML = `<div class="auth-success">${data.message}</div>`;
      setTimeout(() => {
        closeAuthModal();
        checkAuthStatus();
        // Reload products to update vote UI
        if (document.getElementById('productGrid')) {
          loadProductsFromAPI(currentFilter);
        }
      }, 1000);
    } else {
      messageDiv.innerHTML = `<div class="auth-error">${data.error}</div>`;
    }
  })
  .catch(err => {
    messageDiv.innerHTML = `<div class="auth-error">An error occurred. Please try again.</div>`;
    console.error('Auth error:', err);
  });
}

function handleLogout() {
  fetch(`${API_URL}?action=logout`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        checkAuthStatus();
        // Reload products to update vote UI
        if (document.getElementById('productGrid')) {
          loadProductsFromAPI(currentFilter);
        }
      }
    })
    .catch(err => console.error('Logout error:', err));
}

/* ─── VOTING SYSTEM (PHP API integration) ─────────────────────────── */
const API_URL = 'api.php';

async function loadProductsFromAPI(sector = 'all') {
  try {
    const url = sector === 'all' ? `${API_URL}?action=get_products` : `${API_URL}?action=get_products&sector=${sector}`;
    const response = await fetch(url);
    const products = await response.json();
    renderProductsFromAPI(products);
  } catch (error) {
    console.error('Error loading products:', error);
  }
}

function renderProductsFromAPI(products) {
  const grid = document.getElementById('productGrid');
  if (!grid) return;
  
  grid.innerHTML = products.map(product => `
    <div class="product-card" data-sector="${product.sector}" data-popular="${product.vote_count > 0 ? 'true' : 'false'}" data-product-id="${product.id}">
      <div class="product-card-image">
        <img src="${product.image_url}" alt="${product.title}">
        <span class="product-card-tag ${product.tag_class}">${product.tag}</span>
        ${product.vote_count > 0 ? `<span class="product-card-badge popular"><i class="fas fa-star"></i> Popular</span>` : ''}
        <button class="vote-button ${product.user_voted ? 'voted' : ''}" onclick="event.stopPropagation(); toggleVote(${product.id})" data-product-id="${product.id}">
          <i class="fas fa-heart"></i>
          <span class="vote-count">${product.vote_count}</span>
        </button>
      </div>
      <div class="product-card-content">
        <div class="product-card-origin">${product.origin}</div>
        <h3>${product.title}</h3>
        <p class="product-card-desc">${product.description}</p>
        <div class="product-card-footer">
          <span class="product-card-action">View Breakdown →</span>
        </div>
      </div>
    </div>
  `).join('');
  
  // Re-attach click handlers
  document.querySelectorAll('#productGrid .product-card').forEach(card => {
    card.addEventListener('click', () => {
      const productId = card.getAttribute('data-product-id');
      if (productId) {
        openModal(parseInt(productId));
      }
    });
  });
}

async function toggleVote(productId) {
  // Check if logged in first
  try {
    const authRes = await fetch(`${API_URL}?action=check_auth`);
    const authData = await authRes.json();
    
    if (!authData.logged_in) {
      openAuthModal();
      return;
    }
  } catch (err) {
    console.error('Auth check failed:', err);
    openAuthModal();
    return;
  }
  
  // Proceed with voting if logged in
  try {
    const response = await fetch(`${API_URL}?action=vote`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ product_id: productId })
    });
    
    const result = await response.json();
    
    if (result.success) {
      // Update UI for this specific button
      const btn = document.querySelector(`.vote-button[data-product-id="${productId}"]`);
      if (btn) {
        const countSpan = btn.querySelector('.vote-count');
        if (countSpan) {
          countSpan.textContent = result.vote_count;
        }
        
        if (result.voted) {
          btn.classList.add('voted');
        } else {
          btn.classList.remove('voted');
        }
      }
      
      // Reload products to update popular badges
      loadProductsFromAPI(currentFilter);
    } else {
      console.error('Vote failed:', result.error);
    }
  } catch (error) {
    console.error('Error voting:', error);
  }
}

/* ─── PRODUCT FILTERING SYSTEM ─────────────────────────── */
let currentFilter = 'all';
let searchQuery = '';

function filterProducts() {
  const cards = document.querySelectorAll('#productGrid .product-card');
  const pills = document.querySelectorAll('#browseFilters .filter-pill');
  const noResults = document.getElementById('noResults');
  const resultsCount = document.getElementById('searchResultsCount');
  
  let visibleCount = 0;

  // Update active pill
  pills.forEach(pill => {
    pill.classList.remove('active');
    const filter = pill.getAttribute('data-filter');
    if (filter === currentFilter) {
      pill.classList.add('active');
    }
  });

  // Filter cards
  cards.forEach(card => {
    const sector = card.getAttribute('data-sector');
    const isPopular = card.getAttribute('data-popular') === 'true';
    const title = card.querySelector('h3').textContent.toLowerCase();
    const origin = card.querySelector('.product-card-origin').textContent.toLowerCase();
    const desc = card.querySelector('.product-card-desc').textContent.toLowerCase();
    
    // Check sector filter
    let matchesSector = false;
    if (currentFilter === 'all') {
      matchesSector = true;
    } else if (currentFilter === 'popular') {
      matchesSector = isPopular;
    } else {
      matchesSector = sector === currentFilter;
    }
    
    // Check search query
    let matchesSearch = true;
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      matchesSearch = title.includes(query) || origin.includes(query) || desc.includes(query);
    }
    
    // Apply filter
    if (matchesSector && matchesSearch) {
      card.classList.remove('hidden');
      visibleCount++;
    } else {
      card.classList.add('hidden');
    }
  });

  // Show/hide no results message
  if (noResults) {
    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  // Update results count
  if (resultsCount) {
    if (searchQuery || currentFilter !== 'all') {
      resultsCount.textContent = `Showing ${visibleCount} product${visibleCount !== 1 ? 's' : ''}`;
    } else {
      resultsCount.textContent = '';
    }
  }
}

function filterSector(sector) {
  currentFilter = sector;
  filterProducts();
}

/* ─── SEARCH FUNCTIONALITY ─────────────────────────────── */
const searchInput = document.getElementById('searchInput');
const searchClear = document.getElementById('searchClear');

if (searchInput) {
  searchInput.addEventListener('input', (e) => {
    searchQuery = e.target.value.trim();
    if (searchClear) {
      searchClear.style.display = searchQuery ? 'block' : 'none';
    }
    filterProducts();
  });
}

if (searchClear) {
  searchClear.addEventListener('click', () => {
    if (searchInput) {
      searchInput.value = '';
      searchQuery = '';
      searchClear.style.display = 'none';
      filterProducts();
      searchInput.focus();
    }
  });
}

/* ─── FILTER BUTTON EVENT LISTENERS ───────────────────── */
document.querySelectorAll('#browseFilters .filter-pill').forEach(pill => {
  pill.addEventListener('click', () => {
    const filter = pill.getAttribute('data-filter');
    filterSector(filter);
  });
});

/* ─── PRODUCT CARD CLICK HANDLERS ─────────────────────── */
document.querySelectorAll('#productGrid .product-card').forEach(card => {
  card.addEventListener('click', () => {
    const productId = card.getAttribute('data-product-id');
    if (productId) {
      openModal(parseInt(productId));
    }
  });
});

/* ─── FOOTER FILTER LINKS ───────────────────────────────── */
document.querySelectorAll('.footer-col ul a[data-filter-link]').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const filter = link.getAttribute('data-filter-link');
    localStorage.setItem('filterSector', filter);
    window.location.href = 'products.html';
  });
});

/* ─── FEATURED PRODUCT TABS SLIDER (CAROUSEL) ──────────── */
let currentTab = 0;
const slides = document.querySelectorAll(".featured-slide");
const indicators = document.querySelectorAll(".tab-indicator");
const buttons = document.querySelectorAll(".tab-nav-btn");
const totalTabs = slides.length;
let tabInterval;

function switchFeaturedTab(index) {
  if (totalTabs === 0) return;
  slides.forEach(slide => slide.classList.remove("active"));
  indicators.forEach(ind => ind.classList.remove("active"));
  buttons.forEach(btn => btn.classList.remove("active"));
  
  if (slides[index]) slides[index].classList.add("active");
  if (indicators[index]) indicators[index].classList.add("active");
  if (buttons[index]) buttons[index].classList.add("active");
  
  currentTab = index;
}

function startTabAutoplay() {
  if (totalTabs === 0) return;
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
    panel.innerHTML = `<table class="specs-tbl">${tableRows}</table>`;
  }
}

function closeModal() {
  document.getElementById("productModal").classList.remove("open");
  activeProductData = null;
}

/* ─── MODAL EVENT LISTENERS ─────────────────────────────── */
const closeModalBtn = document.getElementById('closeModal');
if (closeModalBtn) {
  closeModalBtn.addEventListener('click', closeModal);
}

const modalOverlay = document.getElementById('productModal');
if (modalOverlay) {
  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      closeModal();
    }
  });
}

/* ─── INITIALIZATION ON RUNTIME ───────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  // Auth form submit handler
  const authForm = document.getElementById('authForm');
  if (authForm) {
    authForm.addEventListener('submit', handleAuthSubmit);
  }
  
  // Check auth status on page load
  checkAuthStatus();
  
  // Init hero tabs slider if on homepage
  if(document.querySelectorAll(".featured-slide").length > 0) {
    startTabAutoplay();
  }
  
  // Initialize voting system if on products page
  if (document.getElementById('productGrid')) {
    loadProductsFromAPI(currentFilter);
    
    // Handles cross-page sector filtering from footer links
    const targetSector = localStorage.getItem('filterSector');
    if (targetSector) {
      currentFilter = targetSector;
      loadProductsFromAPI(targetFilter);
      localStorage.removeItem('filterSector'); // clear state token
    }
  }
});