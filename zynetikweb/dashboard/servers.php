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
    <title>Mes Serveurs | Zynetik Web</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Mes Serveurs</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Surveillance en temps réel de votre infrastructure.</p>
                </div>

                <div class="user-profile">
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <div class="avatar"><?= $initials ?></div>
                </div>
            </header>

            <div class="server-list">
                <!-- Server 1 -->
                <div class="server-card reveal">
                    <div class="server-icon"><i data-lucide="hard-drive"></i></div>
                    <div class="server-details">
                        <div class="server-name">Cloud-Node-01 (Production)</div>
                        <div class="server-meta">
                            <span><i data-lucide="hash"></i> 192.168.1.104</span>
                            <span><i data-lucide="map-pin"></i> Paris, FR</span>
                            <span class="status-pill pill-online">En ligne</span>
                        </div>
                    </div>
                    <div class="resource-usage">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem;"><span>CPU</span><span>42%</span></div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 42%;"></div></div>
                    </div>
                    <div class="resource-usage">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem;"><span>RAM</span><span>2.4 GB / 8 GB</span></div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 30%;"></div></div>
                    </div>
                    <button class="btn-action"><i data-lucide="terminal"></i></button>
                </div>

                <!-- Server 2 -->
                <div class="server-card reveal">
                    <div class="server-icon"><i data-lucide="hard-drive"></i></div>
                    <div class="server-details">
                        <div class="server-name">Backup-Storage-X</div>
                        <div class="server-meta">
                            <span><i data-lucide="hash"></i> 10.0.0.15</span>
                            <span><i data-lucide="map-pin"></i> Lyon, FR</span>
                            <span class="status-pill pill-online">En ligne</span>
                        </div>
                    </div>
                    <div class="resource-usage">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem;"><span>CPU</span><span>12%</span></div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 12%;"></div></div>
                    </div>
                    <div class="resource-usage">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem;"><span>RAM</span><span>1.1 GB / 4 GB</span></div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 25%;"></div></div>
                    </div>
                    <button class="btn-action"><i data-lucide="terminal"></i></button>
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
