const ADMIN_API_URL = 'admin.php';

// Check login on page load
document.addEventListener('DOMContentLoaded', () => {
  checkAdminAuth();
  
  // Allow Enter key for login
  document.getElementById('adminPassword').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      handleAdminLogin();
    }
  });
  
  document.getElementById('adminEmail').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      document.getElementById('adminPassword').focus();
    }
  });
});

async function checkAdminAuth() {
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=check_admin_auth`);
    const data = await response.json();
    
    if (data.logged_in) {
      showAdminPanel(data.admin_email);
    }
  } catch (error) {
    console.error('Auth check failed:', error);
  }
}

async function handleAdminLogin() {
  const email = document.getElementById('adminEmail').value;
  const password = document.getElementById('adminPassword').value;
  
  if (!email || !password) {
    document.getElementById('loginError').textContent = 'Email and password are required';
    document.getElementById('loginError').classList.add('active');
    setTimeout(() => {
      document.getElementById('loginError').classList.remove('active');
    }, 2000);
    return;
  }
  
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=admin_login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    
    const result = await response.json();
    
    if (result.success) {
      showAdminPanel(email);
    } else {
      document.getElementById('loginError').textContent = result.error;
      document.getElementById('loginError').classList.add('active');
      setTimeout(() => {
        document.getElementById('loginError').classList.remove('active');
      }, 2000);
    }
  } catch (error) {
    console.error('Login error:', error);
    document.getElementById('loginError').textContent = 'Login failed. Please try again.';
    document.getElementById('loginError').classList.add('active');
  }
}

function showAdminPanel(email) {
  document.getElementById('loginOverlay').classList.add('hidden');
  document.getElementById('adminContent').classList.add('active');
  loadProducts();
  loadCategories();
}

async function logout() {
  try {
    await fetch(`${ADMIN_API_URL}?action=admin_logout`);
    location.reload();
  } catch (error) {
    console.error('Logout error:', error);
    location.reload();
  }
}

// Load all products
async function loadProducts() {
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=get_products`);
    const products = await response.json();
    renderProductsTable(products);
  } catch (error) {
    console.error('Error loading products:', error);
    showMessage('Error loading products', 'error');
  }
}

// Render products table
function renderProductsTable(products) {
  const tbody = document.getElementById('productsTableBody');
  
  if (products.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No products found</td></tr>';
    return;
  }
  
  tbody.innerHTML = products.map(product => `
    <tr>
      <td><img src="${product.image_url}" alt="${product.title}"></td>
      <td>${product.title}</td>
      <td>${product.origin}</td>
      <td>${product.tag}</td>
      <td>${product.sector}</td>
      <td>${product.vote_count}</td>
      <td>
        <button class="btn-edit" onclick="openEditModal(${product.id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn-delete" onclick="deleteProduct(${product.id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    </tr>
  `).join('');
}

// Open add modal
function openAddModal() {
  document.getElementById('modalTitle').textContent = 'Add Product';
  document.getElementById('productForm').reset();
  document.getElementById('productId').value = '';
  document.getElementById('imagePreview').classList.remove('active');
  document.getElementById('productModal').classList.add('active');
  loadCategories();
}

// Open edit modal
async function openEditModal(id) {
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=get_products`);
    const products = await response.json();
    const product = products.find(p => p.id === id);
    
    if (!product) {
      showMessage('Product not found', 'error');
      return;
    }
    
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = product.id;
    document.getElementById('title').value = product.title;
    document.getElementById('origin').value = product.origin;
    document.getElementById('tag').value = product.tag;
    document.getElementById('tag_class').value = product.tag_class;
    document.getElementById('category_id').value = product.category_id || '';
    document.getElementById('sector').value = product.sector;
    document.getElementById('description').value = product.description;
    document.getElementById('heritage_story').value = product.heritage_story || '';
    document.getElementById('where_to_find').value = product.where_to_find || '';
    document.getElementById('did_you_know').value = product.did_you_know || '';
    
    // Show current image preview
    const preview = document.getElementById('imagePreview');
    preview.src = product.image_url;
    preview.classList.add('active');
    
    // Make image optional for edit
    document.getElementById('image').required = false;
    
    document.getElementById('productModal').classList.add('active');
    loadCategories();
  } catch (error) {
    console.error('Error loading product:', error);
    showMessage('Error loading product', 'error');
  }
}

// Close modal
function closeModal() {
  document.getElementById('productModal').classList.remove('active');
  document.getElementById('productForm').reset();
  document.getElementById('imagePreview').classList.remove('active');
  document.getElementById('image').required = true;
}

// Handle form submission
document.getElementById('productForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const formData = new FormData();
  const id = document.getElementById('productId').value;
  
  formData.append('title', document.getElementById('title').value);
  formData.append('origin', document.getElementById('origin').value);
  formData.append('tag', document.getElementById('tag').value);
  formData.append('tag_class', document.getElementById('tag_class').value);
  formData.append('category_id', document.getElementById('category_id').value);
  formData.append('sector', document.getElementById('sector').value);
  formData.append('description', document.getElementById('description').value);
  formData.append('heritage_story', document.getElementById('heritage_story').value);
  formData.append('where_to_find', document.getElementById('where_to_find').value);
  formData.append('did_you_know', document.getElementById('did_you_know').value);
  
  const imageInput = document.getElementById('image');
  if (imageInput.files.length > 0) {
    formData.append('image', imageInput.files[0]);
  }
  
  if (id) {
    formData.append('id', id);
  }
  
  const action = id ? 'update_product' : 'add_product';
  
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=${action}`, {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      showMessage(result.message, 'success');
      closeModal();
      loadProducts();
    } else {
      showMessage(result.error, 'error');
    }
  } catch (error) {
    console.error('Error saving product:', error);
    showMessage('Error saving product', 'error');
  }
});

// Delete product
async function deleteProduct(id) {
  if (!confirm('Are you sure you want to delete this product?')) {
    return;
  }
  
  try {
    const formData = new FormData();
    formData.append('id', id);
    
    const response = await fetch(`${ADMIN_API_URL}?action=delete_product`, {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      showMessage(result.message, 'success');
      loadProducts();
    } else {
      showMessage(result.error, 'error');
    }
  } catch (error) {
    console.error('Error deleting product:', error);
    showMessage('Error deleting product', 'error');
  }
}

// Show message
function showMessage(text, type) {
  const messageDiv = document.getElementById('message');
  messageDiv.textContent = text;
  messageDiv.className = `message ${type} active`;
  
  setTimeout(() => {
    messageDiv.classList.remove('active');
  }, 3000);
}

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('imagePreview');
      preview.src = e.target.result;
      preview.classList.add('active');
    }
    reader.readAsDataURL(file);
  }
});

// ─── CATEGORY MANAGEMENT ─────────────────────────────────

async function loadCategories() {
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=get_categories`);
    const categories = await response.json();
    renderCategoriesTable(categories);
    populateCategoryDropdown(categories);
  } catch (error) {
    console.error('Error loading categories:', error);
  }
}

function renderCategoriesTable(categories) {
  const tbody = document.getElementById('categoriesTableBody');
  
  if (categories.length === 0) {
    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No categories found</td></tr>';
    return;
  }
  
  tbody.innerHTML = categories.map(category => `
    <tr>
      <td>${category.name}</td>
      <td>${category.slug}</td>
      <td>${category.description || '-'}</td>
      <td>
        <button class="btn-edit" onclick="editCategory(${category.id})">
          <i class="fas fa-edit"></i> Edit
        </button>
        <button class="btn-delete" onclick="deleteCategory(${category.id})">
          <i class="fas fa-trash"></i> Delete
        </button>
      </td>
    </tr>
  `).join('');
}

function populateCategoryDropdown(categories) {
  const select = document.getElementById('category_id');
  select.innerHTML = '<option value="">Select a category</option>';
  
  categories.forEach(category => {
    const option = document.createElement('option');
    option.value = category.id;
    option.textContent = category.name;
    select.appendChild(option);
  });
}

function openCategoryModal() {
  document.getElementById('categoryModalTitle').textContent = 'Manage Categories';
  document.getElementById('categoryForm').reset();
  document.getElementById('categoryId').value = '';
  document.getElementById('categoryModal').classList.add('active');
  loadCategories();
}

function closeCategoryModal() {
  document.getElementById('categoryModal').classList.remove('active');
  document.getElementById('categoryForm').reset();
}

async function editCategory(id) {
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=get_categories`);
    const categories = await response.json();
    const category = categories.find(c => c.id === id);
    
    if (!category) {
      showMessage('Category not found', 'error');
      return;
    }
    
    document.getElementById('categoryId').value = category.id;
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categorySlug').value = category.slug;
    document.getElementById('categoryDescription').value = category.description || '';
    
    document.getElementById('categoryModal').classList.add('active');
  } catch (error) {
    console.error('Error loading category:', error);
    showMessage('Error loading category', 'error');
  }
}

async function deleteCategory(id) {
  if (!confirm('Are you sure you want to delete this category?')) {
    return;
  }
  
  try {
    const formData = new FormData();
    formData.append('id', id);
    
    const response = await fetch(`${ADMIN_API_URL}?action=delete_category`, {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      showMessage(result.message, 'success');
      loadCategories();
    } else {
      showMessage(result.error, 'error');
    }
  } catch (error) {
    console.error('Error deleting category:', error);
    showMessage('Error deleting category', 'error');
  }
}

// Category form submission
document.getElementById('categoryForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const id = document.getElementById('categoryId').value;
  const data = {
    name: document.getElementById('categoryName').value,
    slug: document.getElementById('categorySlug').value,
    description: document.getElementById('categoryDescription').value
  };
  
  const action = id ? 'update_category' : 'add_category';
  
  try {
    const response = await fetch(`${ADMIN_API_URL}?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    
    if (result.success) {
      showMessage(result.message, 'success');
      document.getElementById('categoryForm').reset();
      document.getElementById('categoryId').value = '';
      loadCategories();
    } else {
      showMessage(result.error, 'error');
    }
  } catch (error) {
    console.error('Error saving category:', error);
    showMessage('Error saving category', 'error');
  }
});
