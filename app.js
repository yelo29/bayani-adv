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
        if (document.getElementById('productGrid')) {
          loadProductsFromAPI(currentFilter);
        }
        if (document.getElementById('featuredContent')) {
          loadFeaturedProducts();
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
        if (document.getElementById('productGrid')) {
          loadProductsFromAPI(currentFilter);
        }
        if (document.getElementById('featuredContent')) {
          loadFeaturedProducts();
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
  
  document.querySelectorAll('#productGrid .product-card').forEach(card => {
    card.addEventListener('click', () => {
      const productId = card.getAttribute('data-product-id');
      if (productId) {
        openModal(parseInt(productId));
      }
    });
  });
}

async function loadFeaturedProducts() {
  try {
    const response = await fetch(`${API_URL}?action=get_featured`);
    const products = await response.json();
    renderFeaturedProducts(products);
  } catch (error) {
    console.error('Error loading featured products:', error);
  }
}

function renderFeaturedProducts(products) {
  const tabNav = document.getElementById('tabNav');
  const featuredContent = document.getElementById('featuredContent');
  const tabIndicators = document.getElementById('tabIndicators');
  
  if (!tabNav || !featuredContent || !tabIndicators) return;
  
  // Render tab buttons
  tabNav.innerHTML = products.map((product, index) => `
    <button class="tab-nav-btn ${index === 0 ? 'active' : ''}" data-tab="${index}">${product.title}</button>
  `).join('');
  
  // Render slides
  featuredContent.innerHTML = products.map((product, index) => `
    <div class="featured-slide ${index === 0 ? 'active' : ''}" data-slide="${index}">
      <div class="featured-slide-inner">
        <div class="featured-image">
          <img src="${product.image_url}" alt="${product.title}">
        </div>
        <div class="featured-details">
          <span class="tag ${product.tag_class}">${product.tag}</span>
          <h3>${product.title}</h3>
          <div class="origin"><i class="fas fa-map-marker-alt"></i> ${product.origin}</div>
          <p>${product.description}</p>
          <a href="#" class="featured-cta" onclick="openModal(${product.id}); return false;">View Details →</a>
        </div>
      </div>
    </div>
  `).join('');
  
  // Render indicators
  tabIndicators.innerHTML = products.map((_, index) => `
    <div class="tab-indicator ${index === 0 ? 'active' : ''}" data-slide="${index}"></div>
  `).join('');
  
  // Re-attach event listeners
  initFeaturedCarousel();
}

async function toggleVote(productId) {
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
      
      if (document.getElementById('productGrid')) {
        loadProductsFromAPI(currentFilter);
      }
      if (document.getElementById('featuredContent')) {
        loadFeaturedProducts();
      }
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

  pills.forEach(pill => {
    pill.classList.remove('active');
    const filter = pill.getAttribute('data-filter');
    if (filter === currentFilter) {
      pill.classList.add('active');
    }
  });

  cards.forEach(card => {
    const sector = card.getAttribute('data-sector');
    const isPopular = card.getAttribute('data-popular') === 'true';
    const title = card.querySelector('h3').textContent.toLowerCase();
    const origin = card.querySelector('.product-card-origin').textContent.toLowerCase();
    const desc = card.querySelector('.product-card-desc').textContent.toLowerCase();
    
    let matchesSector = false;
    if (currentFilter === 'all') {
      matchesSector = true;
    } else if (currentFilter === 'popular') {
      matchesSector = isPopular;
    } else {
      matchesSector = sector === currentFilter;
    }
    
    let matchesSearch = true;
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      matchesSearch = title.includes(query) || origin.includes(query) || desc.includes(query);
    }
    
    if (matchesSector && matchesSearch) {
      card.classList.remove('hidden');
      visibleCount++;
    } else {
      card.classList.add('hidden');
    }
  });

  if (noResults) {
    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
  }

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

/* ─── FEATURED PRODUCT CAROUSEL ────────────────────────── */
let currentTab = 0;
let tabInterval;

function initFeaturedCarousel() {
  const slides = document.querySelectorAll(".featured-slide");
  const indicators = document.querySelectorAll(".tab-indicator");
  const buttons = document.querySelectorAll(".tab-nav-btn");
  const totalTabs = slides.length;
  
  if (totalTabs === 0) return;

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

  // Start autoplay
  startTabAutoplay();
}

/* ─── DYNAMIC PRODUCT MODAL ─────────────────────────────── */
let activeProductData = null;

async function openModal(id) {
  try {
    const response = await fetch(`${API_URL}?action=get_product_details&product_id=${id}`);
    const data = await response.json();
    
    if (data.error) {
      console.error('Error loading product details:', data.error);
      return;
    }
    
    activeProductData = data;

    const headerHtml = `
      <div class="modal-header">
        <div class="modal-img">
          <img src="${data.image_url}" alt="${data.title}">
        </div>
        <div class="modal-intro">
          <span class="tag ${data.tag_class}">${data.tag}</span>
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
    panel.innerHTML = `<p>${data.description}</p>`;

    document.getElementById("productModal").classList.add("open");
  } catch (error) {
    console.error('Error opening modal:', error);
  }
}

function switchModalTab(tabElement, tabType) {
  const tabs = document.querySelectorAll('#modalTabsContainer .modal-tab');
  tabs.forEach(t => t.classList.remove('active'));
  tabElement.classList.add('active');

  const panel = document.getElementById('modalPanelContent');
  if (!activeProductData) return;

  if (tabType === 'overview') {
    panel.innerHTML = `<p>${activeProductData.description}</p>`;
  } else if (tabType === 'history') {
    panel.innerHTML = `<p>This product represents the rich heritage of Filipino craftsmanship. Each piece is created using traditional techniques passed down through generations, preserving our cultural identity.</p>`;
  } else if (tabType === 'where') {
    panel.innerHTML = `<p>Available through local artisan collectives and regional craft markets across the Philippines. Support local makers and help preserve our traditional crafts.</p>`;
  } else if (tabType === 'dyk') {
    panel.innerHTML = `<p><i class="fas fa-lightbulb" style="color:var(--gold); margin-right:6px;"></i> Each product is unique, with slight variations that reflect the handmade nature and individual artisan's touch.</p>`;
  } else if (tabType === 'details') {
    panel.innerHTML = `
      <table class="specs-tbl">
        <tr><td>Origin</td><td>${activeProductData.origin}</td></tr>
        <tr><td>Category</td><td>${activeProductData.tag}</td></tr>
        <tr><td>Sector</td><td>${activeProductData.sector}</td></tr>
        <tr><td>Vote Count</td><td>${activeProductData.vote_count}</td></tr>
      </table>
    `;
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

/* ─── FOOTER FILTER LINKS ───────────────────────────────── */
document.querySelectorAll('.footer-col ul a[data-filter-link]').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const filter = link.getAttribute('data-filter-link');
    localStorage.setItem('filterSector', filter);
    window.location.href = 'products.html';
  });
});

/* ─── INITIALIZATION ON RUNTIME ───────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const authForm = document.getElementById('authForm');
  if (authForm) {
    authForm.addEventListener('submit', handleAuthSubmit);
  }
  
  checkAuthStatus();
  
  // Load featured products if on homepage
  if (document.getElementById('featuredContent')) {
    loadFeaturedProducts();
  }
  
  // Initialize voting system if on products page
  if (document.getElementById('productGrid')) {
    loadProductsFromAPI(currentFilter);
    
    const targetSector = localStorage.getItem('filterSector');
    if (targetSector) {
      currentFilter = targetSector;
      loadProductsFromAPI(currentFilter);
      localStorage.removeItem('filterSector');
    }
  }
});