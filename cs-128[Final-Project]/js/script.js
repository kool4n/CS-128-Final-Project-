// js/script.js
class UserManager {
    constructor() {
        this.users = window.APP_CONFIG.users || [];
        this.apiUrl = window.APP_CONFIG.apiUrl;
        this.currentUserId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateUsersDisplay();
    }

    bindEvents() {
        // Modal events
        document.getElementById('addUserBtn').addEventListener('click', () => this.showModal());
        document.getElementById('closeModal').addEventListener('click', () => this.hideModal());
        document.getElementById('cancelBtn').addEventListener('click', () => this.hideModal());
        
        // Form submission
        document.getElementById('userForm').addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Click outside modal to close
        document.getElementById('userModal').addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.hideModal();
            }
        });

        // Delete and edit buttons (event delegation)
        document.getElementById('usersList').addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-user')) {
                const userId = e.target.getAttribute('data-id');
                this.deleteUser(userId);
            } else if (e.target.classList.contains('edit-user')) {
                const userId = e.target.getAttribute('data-id');
                this.editUser(userId);
            }
        });
    }

    showModal(isEdit = false) {
        const modal = document.getElementById('userModal');
        const title = document.getElementById('modalTitle');
        const saveBtn = document.getElementById('saveBtn');
        
        if (isEdit) {
            title.textContent = 'Edit User';
            saveBtn.textContent = 'Update User';
        } else {
            title.textContent = 'Add New User';
            saveBtn.textContent = 'Save User';
            this.clearForm();
        }
        
        modal.classList.remove('hidden');
        document.getElementById('userName').focus();
    }

    hideModal() {
        document.getElementById('userModal').classList.add('hidden');
        this.clearForm();
        this.currentUserId = null;
    }

    clearForm() {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const userData = {
            name: formData.get('name'),
            email: formData.get('email'),
            id: this.currentUserId
        };

        if (!this.validateUserData(userData)) {
            return;
        }

        this.showLoading();

        try {
            const response = await this.saveUser(userData);
            
            if (response.success) {
                this.showAlert('User saved successfully!', 'success');
                this.hideModal();
                this.refreshUsers();
            } else {
                this.showAlert('Error saving user.', 'error');
            }
        } catch (error) {
            this.showAlert('An unexpected error occurred.', 'error');
            console.error(error);
        }
    }
    // Remove all PHP code and closing tag from here.
}