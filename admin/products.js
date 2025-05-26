document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const addProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const confirmModal = document.getElementById('confirmModal');
    const closeModal = document.querySelector('.close');
    const productForm = document.getElementById('productForm');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const modalTitle = document.getElementById('modalTitle');
    const cancelDelete = document.getElementById('cancelDelete');
    
    // State variables
    let currentProductId = null;
    let deleteProductId = null;

    // Event Listeners
    addProductBtn.addEventListener('click', openAddModal);
    closeModal.addEventListener('click', closeModals);
    cancelDelete.addEventListener('click', closeModals);
    
    // Image preview handler
    imageInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Edit buttons
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            editProduct(productId);
        });
    });

    // Delete buttons
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            deleteProductId = this.getAttribute('data-id');
            confirmModal.style.display = 'block';
        });
    });

    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteProductId) {
            deleteProduct(deleteProductId);
        }
    });

    // Form submission
    productForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Determine if we're adding or updating
        const isUpdate = currentProductId !== null;
        const endpoint = isUpdate ? 'update_product.php' : 'insert_product.php';
        
        fetch(endpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModals();
                window.location.reload();
            } else {
                throw new Error(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while processing your request');
        });
    });

    // Functions
    function openAddModal() {
        currentProductId = null;
        modalTitle.textContent = 'Add New Product';
        productForm.reset();
        imagePreview.innerHTML = '';
        productModal.style.display = 'block';
    }

    function editProduct(productId) {
        currentProductId = productId;
        modalTitle.textContent = 'Edit Product';
        
        // Fetch product data
        fetch(`get_product.php?id=${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load product data');
                }
                return response.json();
            })
            .then(product => {
                document.getElementById('productId').value = product.id;
                document.getElementById('title').value = product.name;
                document.getElementById('description').value = product.description;
                document.getElementById('category').value = product.category;
                document.getElementById('price').value = product.price;
                
                // Display current image
                imagePreview.innerHTML = `<img src="../${product.image_url}" alt="Current Image">`;
                
                productModal.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
    }

    function deleteProduct(productId) {
        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModals();
                window.location.reload();
            } else {
                throw new Error(data.error || 'Failed to delete product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while deleting the product');
        });
    }

    function closeModals() {
        productModal.style.display = 'none';
        confirmModal.style.display = 'none';
        deleteProductId = null;
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === productModal || event.target === confirmModal) {
            closeModals();
        }
    });
});