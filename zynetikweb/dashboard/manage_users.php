<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkRole(['founder']); // Seul le fondateur peut gérer les rôles

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $target_user_id = $_POST['user_id'] ?? '';
    $new_role = $_POST['role'] ?? '';
    
    if (!empty($target_user_id) && !empty($new_role)) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $target_user_id]);
        $message = "Rôle mis à jour avec succès !";
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, full_name, email, role FROM users ORDER BY created_at DESC");
$all_users = $stmt->fetchAll();

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs | Administration</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>

</head>
<body>
    <div class="db-layout">
        <?php include 'sidebar.php'; ?>

        <main class="db-main">
            <header class="db-header">
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Gestion des Utilisateurs</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Modifiez les rôles et gérez les comptes.</p>
                </div>
            </header>

            <?php if ($message): ?>
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="user-table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle actuel</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['full_name']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span style="text-transform: capitalize; padding: 0.2rem 0.6rem; border-radius: 4px; background: rgba(0,210,255,0.1); color: var(--accent-primary); font-size: 0.8rem;">
                                    <?= htmlspecialchars($u['role']) ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <select name="role">
                                        <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="staff" <?= $u['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                        <option value="founder" <?= $u['role'] === 'founder' ? 'selected' : '' ?>>Founder</option>
                                    </select>
                                    <button type="submit" name="update_role" class="btn-action"><i data-lucide="save"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
