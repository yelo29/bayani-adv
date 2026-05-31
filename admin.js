const ADMIN_API_URL = 'admin.php';
const ADMIN_PASSWORD = 'gawang2026'; // Change this to your desired password

// Check login on page load
document.addEventListener('DOMContentLoaded', () => {
  // Check if already logged in
  if (sessionStorage.getItem('adminLoggedIn') === 'true') {
    showAdminPanel();
  }
  
  // Allow Enter key for login
  document.getElementById('adminPassword').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      checkLogin();
    }
  });
});

function checkLogin() {
  const password = document.getElementById('adminPassword').value;
  
  if (password === ADMIN_PASSWORD) {
    sessionStorage.setItem('adminLoggedIn', 'true');
    showAdminPanel();
  } else {
    document.getElementById('loginError').classList.add('active');
    setTimeout(() => {
      document.getElementById('loginError').classList.remove('active');
    }, 2000);
  }
}

function showAdminPanel() {
  document.getElementById('loginOverlay').classList.add('hidden');
  document.getElementById('adminContent').classList.add('active');
  loadProducts();
}

function logout() {
  sessionStorage.removeItem('adminLoggedIn');
  location.reload();
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
    document.getElementById('sector').value = product.sector;
    document.getElementById('description').value = product.description;
    
    // Show current image preview
    const preview = document.getElementById('imagePreview');
    preview.src = product.image_url;
    preview.classList.add('active');
    
    // Make image optional for edit
    document.getElementById('image').required = false;
    
    document.getElementById('productModal').classList.add('active');
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
  formData.append('sector', document.getElementById('sector').value);
  formData.append('description', document.getElementById('description').value);
  
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
