<?php
// index.php
require_once 'config/database.php';

$page_title = "User Management System";

// Get users from database
$database = new Database();
$db = $database->connect();

try {
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $users = [];
    $error_message = "Error fetching users: " . $e->getMessage();
}

// Include header
include 'includes/header.php';
?>

<div class="container">
    <section id="hero" class="hero-section">
        <h1 class="hero-title">Welcome to User Management</h1>
        <p class="hero-subtitle">Manage users with style and efficiency</p>
    </section>

    <section id="users" class="users-section">
        <div class="section-header">
            <h2>Users</h2>
            <button id="addUserBtn" class="btn btn-primary">
                <span class="btn-icon">+</span>
                Add User
            </button>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div id="usersList" class="users-grid">
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <h3>No users found</h3>
                    <p>Start by adding your first user!</p>
                </div>
            <?php else: ?>
                <?php foreach($users as $user): ?>
                    <div class="user-card" data-user-id="<?php echo $user['id']; ?>">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <h3 class="user-name"><?php echo htmlspecialchars($user['name']); ?></h3>
                            <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                            <small class="user-date">
                                Joined: <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                            </small>
                        </div>
                        <div class="user-actions">
                            <button class="btn btn-sm btn-outline edit-user" data-id="<?php echo $user['id']; ?>">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-user" data-id="<?php echo $user['id']; ?>">
                                Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- User Modal -->
<div id="userModal" class="modal hidden">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New User</h3>
            <button id="closeModal" class="modal-close">&times;</button>
        </div>
        <form id="userForm" class="modal-body">
            <div class="form-group">
                <label for="userName">Name</label>
                <input type="text" id="userName" name="name" required>
            </div>
            <div class="form-group">
                <label for="userEmail">Email</label>
                <input type="email" id="userEmail" name="email" required>
            </div>
            <input type="hidden" id="userId" name="id">
        </form>
        <div class="modal-footer">
            <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
            <button type="submit" form="userForm" id="saveBtn" class="btn btn-primary">Save User</button>
        </div>
    </div>
</div>

<!-- Loading spinner -->
<div id="loadingSpinner" class="loading-spinner hidden">
    <div class="spinner"></div>
</div>

<!-- Pass PHP data to JavaScript -->
<script>
window.APP_CONFIG = {
    users: <?php echo json_encode($users); ?>,
    apiUrl: 'api/'
};
</script>

<script src="js/script.js"></script>

<?php include 'includes/footer.php'; ?>