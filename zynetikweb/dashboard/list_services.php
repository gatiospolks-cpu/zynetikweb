<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkRole(['staff', 'founder']);
if (!hasPermission('view_services')) {
    header('Location: index.php?error=no_permission');
    exit;
}

// Gérer la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_website'])) {
    if (!hasPermission('delete_services')) {
        $error = "Vous n'avez pas la permission de supprimer des services.";
    } else {
        $site_id = $_POST['site_id'] ?? '';
        if (!empty($site_id)) {
            $stmt = $pdo->prepare("DELETE FROM websites WHERE id = ?");
            $stmt->execute([$site_id]);
            $message = "Le service a été supprimé avec succès.";
        }
    }
}

// Récupérer tous les sites web avec les infos utilisateurs
$stmt = $pdo->query("
    SELECT w.*, u.full_name, u.email 
    FROM websites w 
    JOIN users u ON w.user_id = u.id 
    ORDER BY w.id DESC
");
$all_websites = $stmt->fetchAll();

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Services | Administration</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Liste des Services</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Visualisez tous les services actifs sur la plateforme.</p>
                </div>
            </header>

            <?php if (isset($error) && $error): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="db-section">
                <div class="section-header">
                    <h3 style="font-size: 1.1rem;">Hébergements Web</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Propriétaire</th>
                            <th>Nom du Site</th>
                            <th>URL</th>
                            <th>Plan</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_websites as $site): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600;"><?= htmlspecialchars($site['full_name']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);"><?= htmlspecialchars($site['email']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($site['site_name']) ?></td>
                            <td><a href="#" style="color: var(--accent-primary);"><?= htmlspecialchars($site['site_url']) ?></a></td>
                            <td><?= htmlspecialchars($site['plan']) ?></td>
                            <td>
                                <span class="status-badge status-<?= $site['status'] ?>">
                                    <?= ucfirst($site['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-action" title="Gérer"><i data-lucide="external-link"></i></button>
                                    <?php if (hasPermission('delete_services')): ?>
                                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');" style="display:inline;">
                                        <input type="hidden" name="site_id" value="<?= $site['id'] ?>">
                                        <button type="submit" name="delete_website" class="btn-action" title="Supprimer" style="color: #ef4444;">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($all_websites)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                Aucun service actif trouvé.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
