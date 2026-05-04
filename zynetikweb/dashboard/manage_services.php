<?php
require_once '../includes/auth.php';
require_once '../db.php';
checkRole(['staff', 'founder']);
if (!hasPermission('create_services')) {
    header('Location: index.php?error=no_permission');
    exit;
}

$message = '';
$error = '';

// Récupérer tous les utilisateurs pour le sélecteur
$stmt = $pdo->query("SELECT id, full_name, email FROM users ORDER BY full_name ASC");
$all_users = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_service'])) {
    $type = $_POST['type'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    
    if ($type === 'website') {
        $site_name = $_POST['site_name'] ?? '';
        $site_url = $_POST['site_url'] ?? '';
        $plan = $_POST['plan'] ?? 'Starter Pack';
        
        if (!empty($site_name) && !empty($site_url) && !empty($user_id)) {
            $stmt = $pdo->prepare("INSERT INTO websites (user_id, site_name, site_url, plan) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $site_name, $site_url, $plan]);
            $message = "Site web créé et assigné avec succès !";
        } else {
            $error = "Veuillez remplir tous les champs du site web.";
        }
    }
    // On pourrait ajouter server et database ici plus tard
}

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services | Administration</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Gestion des Services</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Créez et assignez des services aux utilisateurs.</p>
                </div>
            </header>

            <?php if ($message): ?>
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="admin-card">
                <form method="POST">
                    <input type="hidden" name="type" value="website">
                    
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <select name="user_id" required>
                            <option value="">Sélectionner un utilisateur</option>
                            <?php foreach ($all_users as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nom du Site (ex: Portfolio)</label>
                        <input type="text" name="site_name" placeholder="Mon Super Site" required>
                    </div>

                    <div class="form-group">
                        <label>URL du Site (ex: monsite.com)</label>
                        <input type="text" name="site_url" placeholder="monsite.zynetik.io" required>
                    </div>

                    <div class="form-group">
                        <label>Plan</label>
                        <select name="plan">
                            <option value="Starter Pack">Starter Pack</option>
                            <option value="Pro Node">Pro Node</option>
                            <option value="Elite Cloud">Elite Cloud</option>
                        </select>
                    </div>

                    <button type="submit" name="create_service" class="btn-primary" style="padding: 0.8rem 2rem;">Créer le Service</button>
                </form>
            </div>
        </main>
    </div>

    <script src="../script.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
