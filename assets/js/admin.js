// Admin JavaScript for Dori Website

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('expanded');
        });
    }
    
    // User dropdown toggle
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function() {
            dropdownMenu.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('active');
            }
        });
    }
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('blur', function() {
            if (slugInput.value === '') {
                const titleValue = titleInput.value.trim();
                const slugValue = createSlug(titleValue);
                slugInput.value = slugValue;
            }
        });
    }
    
    // Helper function to create a slug
    function createSlug(string) {
        return string
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }
    
    // Initialize WYSIWYG editor
    const editors = document.querySelectorAll('.wysiwyg-editor');
    
    if (editors.length > 0 && typeof ClassicEditor !== 'undefined') {
        editors.forEach(editor => {
            ClassicEditor
                .create(editor, {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        });
    }
    
    // File upload preview
    const fileInput = document.getElementById('file-upload');
    const filePreview = document.getElementById('file-preview');
    
    if (fileInput && filePreview) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const fileType = file.type.split('/')[0];
                    
                    if (fileType === 'image') {
                        filePreview.innerHTML = `<img src="${e.target.result}" alt="${file.name}">`;
                    } else {
                        filePreview.innerHTML = `
                            <div class="file-icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <div class="file-info">
                                <p>${file.name}</p>
                                <small>${(file.size / 1024).toFixed(2)} KB</small>
                            </div>
                        `;
                    }
                    
                    filePreview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Sortable sections and items
    if (typeof Sortable !== 'undefined') {
        const sortableLists = document.querySelectorAll('.sortable-list');
        
        sortableLists.forEach(list => {
            Sortable.create(list, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function(evt) {
                    // Update order numbers
                    const items = evt.to.querySelectorAll('.sortable-item');
                    
                    items.forEach((item, index) => {
                        const orderInput = item.querySelector('input[name*="order_num"]');
                        
                        if (orderInput) {
                            orderInput.value = index;
                        }
                    });
                }
            });
        });
    }
    
    // Add another section item
    const addItemButtons = document.querySelectorAll('.add-item-btn');
    
    addItemButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemsList = this.previousElementSibling;
            const itemTemplate = itemsList.querySelector('.item-template');
            
            if (itemTemplate) {
                const newItem = itemTemplate.cloneNode(true);
                const itemCount = itemsList.querySelectorAll('.sortable-item').length;
                
                newItem.classList.remove('item-template');
                newItem.style.display = 'block';
                
                // Update IDs and names
                const inputs = newItem.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${itemCount}]`);
                        input.setAttribute('name', newName);
                    }
                    
                    // Clear values
                    input.value = '';
                });
                
                itemsList.appendChild(newItem);
                
                // Initialize new WYSIWYG editors
                const newEditors = newItem.querySelectorAll('.wysiwyg-editor');
                
                if (newEditors.length > 0 && typeof ClassicEditor !== 'undefined') {
                    newEditors.forEach(editor => {
                        ClassicEditor
                            .create(editor)
                            .catch(error => {
                                console.error(error);
                            });
                    });
                }
                
                // Remove item button
                const removeButtons = newItem.querySelectorAll('.remove-item-btn');
                
                removeButtons.forEach(removeBtn => {
                    removeBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this item?')) {
                            const item = this.closest('.sortable-item');
                            item.remove();
                        }
                    });
                });
            }
        });
    });
    
    // Remove item button
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                const item = this.closest('.sortable-item');
                item.remove();
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);
    }
});