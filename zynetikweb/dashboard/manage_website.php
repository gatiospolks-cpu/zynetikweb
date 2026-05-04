<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkLogin();

$site_id = $_GET['id'] ?? '';
if (empty($site_id)) {
    header('Location: websites.php');
    exit;
}

// Récupérer les détails du site
$stmt = $pdo->prepare("SELECT * FROM websites WHERE id = ?");
$stmt->execute([$site_id]);
$site = $stmt->fetch();

if (!$site) {
    header('Location: websites.php');
    exit;
}

// Vérifier la propriété (sauf si staff/fondateur)
if ($site['user_id'] != $_SESSION['user_id'] && !isStaff()) {
    header('Location: websites.php?error=access_denied');
    exit;
}

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer <?= htmlspecialchars($site['site_name']) ?> | Zynetik Web</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .manage-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
        }
        .info-box {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .tech-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .tech-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .tech-item:last-child { border-bottom: none; }
        .tech-label { color: var(--text-secondary); font-size: 0.85rem; }
        .tech-value { font-family: monospace; color: var(--accent-primary); font-size: 0.9rem; }
        
        .action-card {
            background: var(--db-card);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .action-card:hover { border-color: var(--accent-primary); transform: translateY(-5px); }
        .action-card i { font-size: 2rem; color: var(--accent-primary); margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="db-layout">
        <?php include 'sidebar.php'; ?>

        <main class="db-main">
            <header class="db-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="websites.php" class="btn-action"><i data-lucide="arrow-left"></i></a>
                    <div>
                        <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;"><?= htmlspecialchars($site['site_name']) ?></h2>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Gestion de votre hébergement web</p>
                    </div>
                </div>
                <span class="status-pill pill-online">En ligne</span>
            </header>

            <div class="manage-grid">
                <div class="main-column">
                    <div class="db-section">
                        <h3 style="margin-bottom: 1.5rem;">Informations Techniques</h3>
                        <div class="tech-info">
                            <div class="tech-item">
                                <span class="tech-label">Domaine Principal</span>
                                <span class="tech-value"><?= htmlspecialchars($site['site_url']) ?></span>
                            </div>
                            <div class="tech-item">
                                <span class="tech-label">Adresse IP du Serveur</span>
                                <span class="tech-value">192.168.1.104</span>
                            </div>
                            <div class="tech-item">
                                <span class="tech-label">Hôte FTP</span>
                                <span class="tech-value">ftp.zynetik.io</span>
                            </div>
                            <div class="tech-item">
                                <span class="tech-label">Utilisateur FTP</span>
                                <span class="tech-value">u<?= $site['id'] ?>_zynetik</span>
                            </div>
                            <div class="tech-item">
                                <span class="tech-label">Chemin Racine</span>
                                <span class="tech-value">/home/u<?= $site['id'] ?>/public_html</span>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 2rem;">
                        <div class="action-card">
                            <i data-lucide="folder-open"></i>
                            <div style="font-weight: 600;">Gestionnaire</div>
                        </div>
                        <div class="action-card" onclick="showToast('Redémarrage des services web en cours...', 'success')">
                            <i data-lucide="refresh-cw"></i>
                            <div style="font-weight: 600;">Redémarrer</div>
                        </div>
                        <div class="action-card">
                            <i data-lucide="database"></i>
                            <div style="font-weight: 600;">Base SQL</div>
                        </div>
                    </div>
                </div>

                <div class="side-column">
                    <div class="info-box">
                        <h4 style="margin-bottom: 1rem; font-size: 0.9rem;">Détails de l'offre</h4>
                        <div style="font-size: 1.1rem; font-weight: 700; color: var(--accent-primary); margin-bottom: 0.5rem;"><?= htmlspecialchars($site['plan']) ?></div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Renouvellement le 14/06/2024</div>
                    </div>

                    <div class="info-box" style="border-color: rgba(34, 197, 94, 0.2);">
                        <h4 style="margin-bottom: 1rem; font-size: 0.9rem;">Certificat SSL</h4>
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: #22c55e; font-size: 0.85rem;">
                            <i data-lucide="shield-check" style="width: 16px;"></i>
                            <span>Actif (Let's Encrypt)</span>
                        </div>
                    </div>

                    <button class="btn-primary" style="width: 100%; padding: 1rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                        <i data-lucide="power" style="width: 16px; vertical-align: middle;"></i> Suspendre le site
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
