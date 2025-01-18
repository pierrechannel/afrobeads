@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create New Product</h5>
            <button type="button" class="btn btn-light btn-sm" id="resetFormBtn">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Form
            </button>
        </div>
        <div class="card-body">
            <div id="globalMessage" class="alert d-none"></div>

            <form id="productForm" class="needs-validation" novalidate enctype="multipart/form-data">
                <input type="hidden" id="productId" name="productId">

                <!-- Basic Information -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Enter product name" required minlength="3">
                            <div class="invalid-feedback">Product name must be at least 3 characters.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="base_price" class="form-label">Base Price *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" class="form-control" id="base_price" name="base_price"
                                   placeholder="0.00" required min="0" step="0.01">
                            <div class="invalid-feedback">Please enter a valid price (min: 0).</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="description" class="form-label">Description *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                            <textarea class="form-control" id="description" name="description"
                                    placeholder="Enter product description" required rows="3"
                                    minlength="20"></textarea>
                            <div class="invalid-feedback">Description must be at least 20 characters.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="category" class="form-label">Category *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-folder"></i></span>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="">Select a category</option>
                            </select>
                            <div class="invalid-feedback">Please select a category.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                            <input type="text" class="form-control" id="brand" name="brand"
                                   placeholder="Enter brand" required>
                            <div class="invalid-feedback">Please enter a brand name.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="unisex">Unisex</option>
                            </select>
                            <div class="invalid-feedback">Please select a gender.</div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="mt-4">
                    <label class="form-label">Product Images *</label>
                    <div class="image-upload-container border rounded p-3">
                        <div class="dropzone-area text-center p-4 border-dashed cursor-pointer" id="dropZone">
                            <i class="bi bi-cloud-upload display-4"></i>
                            <p>Drag & drop images here or click to browse</p>
                            <input type="file" class="d-none" id="images" name="images[]" multiple accept="image/*">
                        </div>
                        <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                        <div class="invalid-feedback">Please upload at least one product image.</div>
                    </div>
                </div>

                <!-- Variants Section -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0">Product Variants *</label>
                        <button type="button" class="btn btn-primary btn-sm" id="addVariantBtn">
                            <i class="bi bi-plus-lg"></i> Add Variant
                        </button>
                    </div>
                    <div id="variantsContainer" class="border rounded p-3"></div>
                    <div class="invalid-feedback">Please add at least one variant.</div>
                </div>

                <div id="errorMessages" class="alert alert-danger mt-3 d-none"></div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">
                        <i class="bi bi-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Core DOM Elements
    const productForm = document.getElementById('productForm');
    const errorMessages = document.getElementById('errorMessages');
    const globalMessage = document.getElementById('globalMessage');
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('images');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const variantsContainer = document.getElementById('variantsContainer');

    // State Management
    let uploadedFiles = new Set();
    let isSubmitting = false;

    // Constants
    const VALIDATION_RULES = {
        minDescriptionLength: 20,
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedImageTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxImages: 5,
        minNameLength: 3
    };

    // Form Validation
    function validateForm() {
        const form = productForm;
        const description = form.querySelector('#description').value;
        const name = form.querySelector('#name').value;

        // Clear previous errors
        hideError();

        // Validate required fields
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            showError('Please fill in all required fields correctly.');
            return false;
        }

        // Validate description length
        if (description.length < VALIDATION_RULES.minDescriptionLength) {
            showError(`Description must be at least ${VALIDATION_RULES.minDescriptionLength} characters.`);
            return false;
        }

        // Validate name length
        if (name.length < VALIDATION_RULES.minNameLength) {
            showError(`Product name must be at least ${VALIDATION_RULES.minNameLength} characters.`);
            return false;
        }

        // Validate images
        if (uploadedFiles.size === 0) {
            showError('Please upload at least one product image.');
            return false;
        }

        // Validate variants
        const variants = collectVariants();
        if (variants.length === 0) {
            showError('Please add at least one product variant.');
            return false;
        }

        return true;
    }

    // Image Handling
    function validateImage(file) {
        if (!VALIDATION_RULES.allowedImageTypes.includes(file.type)) {
            showError(`Invalid file type. Allowed types: JPEG, PNG, WebP`);
            return false;
        }

        if (file.size > VALIDATION_RULES.maxFileSize) {
            showError(`File size must be less than ${VALIDATION_RULES.maxFileSize / (1024 * 1024)}MB`);
            return false;
        }

        if (uploadedFiles.size >= VALIDATION_RULES.maxImages) {
            showError(`Maximum ${VALIDATION_RULES.maxImages} images allowed`);
            return false;
        }

        return true;
    }

    function handleImageUpload(files) {
        Array.from(files).forEach(file => {
            if (!validateImage(file)) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewId = `preview_${Date.now()}`;
                addImagePreview(previewId, e.target.result, file.name);
                uploadedFiles.add(file);
            };
            reader.readAsDataURL(file);
        });
    }

    function addImagePreview(previewId, imgSrc, imgAlt) {
        const template = `
            <div class="position-relative" id="${previewId}">
                <div class="image-preview-wrapper border rounded p-1">
                    <img src="${imgSrc}" alt="${imgAlt}" class="img-thumbnail"
                         style="height: 100px; width: 100px; object-fit: cover;">
                    <div class="image-preview-overlay">
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="removeImage('${previewId}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        imagePreviewContainer.insertAdjacentHTML('beforeend', template);
    }

    // Variant Management
    function addVariantRow(data = {}) {
        const variantId = Date.now();
        const template = `
            <div class="variant-row border rounded p-3 mb-3" data-variant-id="${variantId}">
                <div class="row g-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="size_${variantId}"
                                   value="${data.size || ''}" required placeholder="Size">
                            <label>Size</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="color_${variantId}"
                                   value="${data.color || ''}" required placeholder="Color">
                            <label>Color</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="sku_${variantId}"
                                   value="${data.sku || ''}" required placeholder="SKU"
                                   pattern="^[A-Za-z0-9-_]+$">
                            <label>SKU</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="stock_${variantId}"
                                   value="${data.stock_quantity || 0}" min="0" required placeholder="Stock">
                            <label>Stock</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="price_${variantId}"
                                   value="${data.price_adjustment || 0}" step="0.01" placeholder="Price Adjustment">
                            <label>Price Adjustment</label>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <button type="button" class="btn btn-danger" onclick="removeVariant(${variantId})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', template);
    }

    function collectVariants() {
        const variants = [];
        document.querySelectorAll('.variant-row').forEach(row => {
            const variantId = row.dataset.variantId;
            const variant = {
                size: document.getElementById(`size_${variantId}`).value.trim(),
                color: document.getElementById(`color_${variantId}`).value.trim(),
                sku: document.getElementById(`sku_${variantId}`).value.trim(),
                stock_quantity: parseInt(document.getElementById(`stock_${variantId}`).value, 10),
                price_adjustment: parseFloat(document.getElementById(`price_${variantId}`).value) || 0
            };

            if (variant.size && variant.color && variant.sku && variant.stock_quantity >= 0) {
                variants.push(variant);
            }
        });
        return variants;
    }

    // API Calls
// Add this function inside your DOMContentLoaded event listener

function populateCategorySelect(categories) {
    const categorySelect = document.getElementById('category');
    // Keep the default option
    categorySelect.innerHTML = '<option value="">Select a category</option>';

    // Add categories from API response
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

// Update the loadCategories function to handle the correct API response structure
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        if (!response.ok) throw new Error('Failed to load categories');

        const data = await response.json();

        // Check if data.categories exists and is an array
        if (data.categories && Array.isArray(data.categories)) {
            populateCategorySelect(data.categories);
        } else {
            throw new Error('Invalid category data format');
        }
    } catch (error) {
        console.error('Category loading error:', error);
        showError('Failed to load categories. Please refresh the page.');
    }
}

    async function handleSubmit() {
        if (isSubmitting || !validateForm()) return;

        isSubmitting = true;
        showLoading(true);

        try {
            const formData = new FormData(productForm);
            const variants = collectVariants();
            formData.append('variants', JSON.stringify(variants));

            // Clear previous images and add current ones
            formData.delete('images[]');
            uploadedFiles.forEach(file => formData.append('images[]', file));

            const response = await fetch('/api/products', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to save product');
            }

            showSuccess('Product saved successfully');
            resetForm();
        } catch (error) {
            showError(error.message);
        } finally {
            isSubmitting = false;
            showLoading(false);
        }
    }

    // UI Helpers
    function showError(message) {
        errorMessages.textContent = message;
        errorMessages.classList.remove('d-none');
    }

    function hideError() {
        errorMessages.textContent = '';
        errorMessages.classList.add('d-none');
    }

    function showSuccess(message) {
        globalMessage.textContent = message;
        globalMessage.className = 'alert alert-success';
        setTimeout(() => {
            globalMessage.className = 'alert d-none';
        }, 5000);
    }

    function showLoading(show) {
        const saveBtn = document.getElementById('saveProductBtn');
        if (show) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        } else {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-save"></i> Save Product';
        }
    }

    // Event Listeners
    dropZone.addEventListener('click', () => imageInput.click());
    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('border-primary');
    });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-primary'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-primary');
        handleImageUpload(e.dataTransfer.files);
    });

    // Add these functions inside your DOMContentLoaded event listener

// Reset form function
function resetForm() {
    productForm.reset();
    productForm.classList.remove('was-validated');
    hideError();

    // Clear image previews
    imagePreviewContainer.innerHTML = '';
    uploadedFiles.clear();

    // Reset variants
    variantsContainer.innerHTML = '';
    addVariantRow(); // Add one empty variant row

    // Reset global message
    globalMessage.className = 'alert d-none';
}

// Remove image function
function removeImage(previewId) {
    const previewElement = document.getElementById(previewId);
    if (!previewElement) return;

    // Remove the preview element from DOM
    previewElement.remove();

    // Find and remove the corresponding file from uploadedFiles
    const imgSrc = previewElement.querySelector('img').src;
    uploadedFiles.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (e.target.result === imgSrc) {
                uploadedFiles.delete(file);
            }
        };
        reader.readAsDataURL(file);
    });
}

// Remove variant function
function removeVariant(variantId) {
    const variantRow = document.querySelector(`.variant-row[data-variant-id="${variantId}"]`);
    if (!variantRow) return;

    // Only remove if there's more than one variant
    const variantCount = document.querySelectorAll('.variant-row').length;
    if (variantCount > 1) {
        variantRow.remove();
    } else {
        showError('At least one variant is required.');
    }
}

// Make these functions globally available
window.removeImage = removeImage;
window.removeVariant = removeVariant;
window.resetForm = resetForm;

    imageInput.addEventListener('change', e => handleImageUpload(e.target.files));
    document.getElementById('addVariantBtn').addEventListener('click', () => addVariantRow());
    document.getElementById('saveProductBtn').addEventListener('click', handleSubmit);
    document.getElementById('resetFormBtn').addEventListener('click', resetForm);

    // Initialize form
    loadCategories();
    addVariantRow();
});
</script>
@endsection
