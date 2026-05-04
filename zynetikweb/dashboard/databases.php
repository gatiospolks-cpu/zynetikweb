<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkLogin();

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bases de données | Zynetik Web</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Bases de données</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Gérez vos instances SQL et NoSQL.</p>
                </div>

                <div class="user-profile">
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <div class="avatar"><?= $initials ?></div>
                </div>
            </header>

            <div class="db-grid">
                <!-- DB 1 -->
                <div class="db-card reveal">
                    <div class="db-type-icon"><i data-lucide="database"></i></div>
                    <div class="db-name">prod_main_db</div>
                    <div class="db-info">
                        MySQL 8.0 • 1.2 GB / 5 GB<br>
                        Hébergé sur: Cloud-Node-01
                    </div>
                    
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 10px; border: 1px solid var(--glass-border);">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Accès Local</div>
                        <code style="display: block; font-size: 0.8rem; color: var(--accent-primary); word-break: break-all;">mysql://root@localhost/zynetikweb?charset=utf8mb4</code>
                    </div>

                    <div class="db-actions">
                        <button class="btn-primary" style="flex: 1; font-size: 0.8rem;">phpMyAdmin</button>
                        <button class="btn-action"><i data-lucide="key"></i></button>
                        <button class="btn-action"><i data-lucide="settings"></i></button>
                    </div>
                </div>

                <!-- DB 2 -->
                <div class="db-card reveal">
                    <div class="db-type-icon" style="color: #34d399; background: rgba(52, 211, 153, 0.1);"><i data-lucide="database"></i></div>
                    <div class="db-name">user_analytics_nosql</div>
                    <div class="db-info">
                        MongoDB • 840 MB / 10 GB<br>
                        Hébergé sur: Backup-Storage-X
                    </div>

                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 10px; border: 1px solid var(--glass-border);">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Accès Rapide</div>
                        <code style="display: block; font-size: 0.8rem; color: #34d399; word-break: break-all;">mongodb+srv://user:••••••••@cluster0.zynetik.io</code>
                    </div>

                    <div class="db-actions">
                        <button class="btn-primary" style="flex: 1; font-size: 0.8rem;">Compass</button>
                        <button class="btn-action"><i data-lucide="key"></i></button>
                        <button class="btn-action"><i data-lucide="settings"></i></button>
                    </div>
                </div>

                <!-- DB 3 -->
                <div class="db-card reveal">
                    <div class="db-type-icon" style="color: #6366f1; background: rgba(99, 102, 241, 0.1);"><i data-lucide="database"></i></div>
                    <div class="db-name">test_sandbox</div>
                    <div class="db-info">
                        PostgreSQL 14 • 12 MB / 1 GB<br>
                        Hébergé sur: Local-Dev
                    </div>
                    <div class="db-actions">
                        <button class="btn-primary" style="flex: 1; font-size: 0.8rem;">pgAdmin</button>
                        <button class="btn-action"><i data-lucide="settings"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
