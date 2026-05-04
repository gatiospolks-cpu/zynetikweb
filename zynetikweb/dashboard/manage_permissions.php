<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkRole(['founder']);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role_perms'])) {
    $target_role = $_POST['target_role'] ?? '';
    $p_users = isset($_POST['perm_users']) ? 1 : 0;
    $p_create = isset($_POST['perm_create']) ? 1 : 0;
    $p_view = isset($_POST['perm_view']) ? 1 : 0;
    $p_delete = isset($_POST['perm_delete']) ? 1 : 0;
    
    if (!empty($target_role)) {
        $stmt = $pdo->prepare("UPDATE role_permissions SET perm_manage_users = ?, perm_create_services = ?, perm_view_services = ?, perm_delete_services = ? WHERE role = ?");
        $stmt->execute([$p_users, $p_create, $p_view, $p_delete, $target_role]);
        $message = "Permissions du rôle " . ucfirst($target_role) . " mises à jour !";
    }
}

// Récupérer les permissions par rôle
$stmt = $pdo->query("SELECT * FROM role_permissions ORDER BY FIELD(role, 'founder', 'staff', 'user')");
$roles_perms = $stmt->fetchAll();

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuration des Rôles | Administration</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .role-card {
            background: var(--db-card);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .perm-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .perm-row:last-child { border-bottom: none; }
        .switch {
            position: relative;
            display: inline-block;
            width: 45px; height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255,255,255,0.1);
            transition: .4s; border-radius: 34px;
        }
        .slider:before {
            position: absolute; content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: var(--accent-primary); }
        input:checked + .slider:before { transform: translateX(21px); }
    </style>
</head>
<body>
    <div class="db-layout">
        <?php include 'sidebar.php'; ?>

        <main class="db-main">
            <header class="db-header">
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Configuration des Rôles</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Définissez les droits globaux pour chaque groupe d'utilisateurs.</p>
                </div>
            </header>

            <?php if ($message): ?>
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div style="max-width: 900px;">
                <?php foreach ($roles_perms as $rp): ?>
                <div class="role-card">
                    <form method="POST">
                        <input type="hidden" name="target_role" value="<?= $rp['role'] ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                            <h3 style="text-transform: capitalize; font-size: 1.3rem; color: var(--accent-primary);">
                                <i data-lucide="<?= $rp['role'] === 'founder' ? 'crown' : ($rp['role'] === 'staff' ? 'shield' : 'user') ?>" style="vertical-align: middle; margin-right: 10px;"></i>
                                <?= $rp['role'] ?>
                            </h3>
                            <?php if ($rp['role'] !== 'founder'): ?>
                                <button type="submit" name="update_role_perms" class="btn-primary" style="padding: 0.5rem 1.5rem;">Appliquer au groupe</button>
                            <?php else: ?>
                                <span style="color: var(--accent-primary); font-size: 0.8rem; font-weight: 700;">ACCÈS TOTAL ILLIMITÉ</span>
                            <?php endif; ?>
                        </div>

                        <div class="perm-row">
                            <div>
                                <div style="font-weight: 600;">Gérer les Utilisateurs</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Autoriser à changer les grades et bannir des membres.</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="perm_users" <?= $rp['perm_manage_users'] ? 'checked' : '' ?> <?= $rp['role'] === 'founder' ? 'disabled checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="perm-row">
                            <div>
                                <div style="font-weight: 600;">Créer des Services</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Autoriser à déployer des sites web et serveurs.</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="perm_create" <?= $rp['perm_create_services'] ? 'checked' : '' ?> <?= $rp['role'] === 'founder' ? 'disabled checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="perm-row">
                            <div>
                                <div style="font-weight: 600;">Voir tous les Services</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Accès à la liste globale des hébergements de tous les clients.</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="perm_view" <?= $rp['perm_view_services'] ? 'checked' : '' ?> <?= $rp['role'] === 'founder' ? 'disabled checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="perm-row">
                            <div>
                                <div style="font-weight: 600;">Supprimer des Services</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Droit de supprimer définitivement un service client.</div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="perm_delete" <?= $rp['perm_delete_services'] ? 'checked' : '' ?> <?= $rp['role'] === 'founder' ? 'disabled checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
