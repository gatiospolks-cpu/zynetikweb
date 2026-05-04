<?php
require_once '../includes/auth.php';
require_once '../db.php';
require_once '../includes/lang_manager.php';
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
    <title>Vue d'ensemble | Zynetik Web</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>

</head>
<body>
    <div class="db-layout">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="db-main">
            <header class="db-header">
                <div>
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;"><?= __t('db_welcome_back', $_SESSION['full_name']) ?></h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;"><?= __t('db_infra_status') ?></p>
                </div>

                <div style="margin-left: auto; display: flex; align-items: center;">
                    <div class="lang-switcher-header"></div>
                </div>

                <div class="user-profile">
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <div class="avatar"><?= $initials ?></div>
                </div>
            </header>

            <!-- Stats -->
            <div class="stats-grid stagger-children reveal">
                <div class="db-stat-card">
                    <div class="stat-icon"><i data-lucide="activity"></i></div>
                    <div class="stat-value"><?= count($websites) > 0 ? '99.98%' : '--%' ?></div>
                    <div class="stat-label">Uptime Serveurs</div>
                </div>
                <div class="db-stat-card">
                    <div class="stat-icon"><i data-lucide="eye"></i></div>
                    <div class="stat-value"><?= count($websites) > 0 ? '0' : '0' ?></div>
                    <div class="stat-label">Visiteurs ce mois</div>
                </div>
                <div class="db-stat-card">
                    <div class="stat-icon"><i data-lucide="zap"></i></div>
                    <div class="stat-value"><?= count($websites) > 0 ? '0.32s' : '--s' ?></div>
                    <div class="stat-label">Temps de réponse moyen</div>
                </div>
                <div class="db-stat-card">
                    <div class="stat-icon"><i data-lucide="shield-check"></i></div>
                    <div class="stat-value">24/24</div>
                    <div class="stat-label">Protection Active</div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="db-section reveal">
                <div class="section-header">
                    <h3 style="font-size: 1.2rem;"><?= __t('db_active_services') ?></h3>
                    <button class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.8rem;"><i data-lucide="plus"></i> <?= __t('db_new_service') ?></button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Service / Site</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Hébergement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($websites) > 0): ?>
                            <?php foreach ($websites as $site): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= htmlspecialchars($site['site_url']) ?></td>
                                <td><?= htmlspecialchars($site['plan']) ?></td>
                                <td><span class="status-badge status-<?= $site['status'] ?>"><?= $site['status'] == 'online' ? 'En ligne' : ($site['status'] == 'pending' ? 'En cours' : 'Hors ligne') ?></span></td>
                                <td><?= htmlspecialchars($site['site_name']) ?></td>
                                <td><button class="btn-action"><i data-lucide="external-link"></i></button></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                    Vous n'avez pas encore de services actifs. 
                                    <br><br>
                                    <button class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.8rem;"><i data-lucide="plus"></i> Commencer maintenant</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
