<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkLogin();

$stmt = $pdo->prepare("SELECT * FROM websites WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$websites = $stmt->fetchAll(PDO::FETCH_ASSOC);

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Sites Web | Zynetik Web</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Mes Sites Web</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Gérez vos noms de domaine et hébergements web.</p>
                </div>

                <div style="margin-left: auto; display: flex; align-items: center;">
                    <div class="lang-switcher-header"></div>
                </div>
                <div class="user-profile">
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <div class="avatar"><?= $initials ?></div>
                </div>
            </header>

            <div class="site-grid">
                <?php if (count($websites) > 0): ?>
                    <?php foreach ($websites as $site): ?>
                    <div class="site-card reveal">
                        <div class="site-preview">
                            <i data-lucide="monitor"></i>
                        </div>
                        <div class="site-info">
                            <div class="site-name"><?= htmlspecialchars($site['site_name']) ?></div>
                            <a href="#" class="site-url"><?= htmlspecialchars($site['site_url']) ?></a>
                            <div class="site-stats">
                                <div class="site-stat-item"><i data-lucide="activity"></i> <?= $site['status'] == 'online' ? '100%' : '0%' ?></div>
                                <div class="site-stat-item"><i data-lucide="users"></i> 0/j</div>
                            </div>
                            <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem;">
                                <a href="manage_website.php?id=<?= $site['id'] ?>" class="btn-primary" style="flex: 1; padding: 0.6rem; font-size: 0.8rem; text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center;">Gérer</a>
                                <button class="btn-action" onclick="showToast('Paramètres du site: <?= htmlspecialchars($site['site_name']) ?>\nCette fonctionnalité arrive bientôt !', 'warning')"><i data-lucide="settings"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 5rem; background: var(--db-card); border-radius: 20px; border: 1px solid var(--glass-border);">
                        <i data-lucide="globe" style="width: 50px; height: 50px; color: var(--text-secondary); margin-bottom: 1.5rem;"></i>
                        <h3>Aucun site web trouvé</h3>
                        <p style="color: var(--text-secondary); margin-bottom: 2rem;">Commencez par créer votre premier site internet.</p>
                        <button class="btn-primary" style="padding: 0.8rem 2rem;"><i data-lucide="plus"></i> Ajouter un site</button>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
