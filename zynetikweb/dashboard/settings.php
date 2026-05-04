<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../register.html'); exit; }
require_once '../db.php';
require_once '../includes/lang_manager.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (!empty($fullName) && !empty($email)) {
            try {
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                $stmt->execute([$fullName, $email, $_SESSION['user_id']]);
                $_SESSION['full_name'] = $fullName;
                $_SESSION['email'] = $email;
                $message = "Profil mis à jour avec succès !";
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "Cet email est déjà utilisé.";
                } else {
                    $error = "Erreur lors de la mise à jour : " . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['update_password'])) {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        
        if (!empty($oldPassword) && !empty($newPassword)) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($oldPassword, $user['password'])) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
                $message = "Mot de passe mis à jour avec succès !";
            } else {
                $error = "L'ancien mot de passe est incorrect.";
            }
        }
    }
}

$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres | Zynetik Web</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;"><?= __t('db_settings') ?></h2>
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

            <div class="settings-container">
                <?php if ($message): ?>
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Profil -->
                <div class="settings-section reveal">
                    <h3 style="margin-bottom: 1.5rem;">Informations Personnelles</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label><?= __t('full_name') ?></label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($_SESSION['full_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?= __t('email_prof') ?></label>
                            <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn-primary" style="padding: 0.8rem 2rem;">Enregistrer</button>
                    </form>
                </div>

                <!-- Sécurité -->
                <div class="settings-section reveal">
                    <h3 style="margin-bottom: 1.5rem;">Sécurité</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Ancien mot de passe</label>
                            <input type="password" name="old_password" placeholder="••••••••" required>
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
                        </div>
                        <button type="submit" name="update_password" class="btn-primary" style="padding: 0.8rem 2rem;">Mettre à jour</button>
                    </form>
                </div>

                <!-- Zone de danger -->
                <div class="settings-section reveal danger-zone">
                    <h3 style="color: #ef4444; margin-bottom: 1rem;">Zone de danger</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">La suppression de votre compte est irréversible et entraînera la perte de toutes vos données.</p>
                    <button class="btn-primary" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444;">Supprimer mon compte</button>
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
