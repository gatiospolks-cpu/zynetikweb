<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: ../register.html'); exit; }
$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . substr(explode(' ', $_SESSION['full_name'])[1] ?? '', 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturation | Zynetik Web</title>
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
                    <h2 style="font-size: 1.5rem; margin-bottom: 0.2rem;">Facturation</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Gérez vos abonnements et factures.</p>
                </div>

                <div class="user-profile">
                    <span style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <div class="avatar"><?= $initials ?></div>
                </div>
            </header>

            <div class="billing-grid">
                <div class="db-section reveal">
                    <h3 style="margin-bottom: 1.5rem;">Historique des factures</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Facture #</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                    <i data-lucide="info" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.5;"></i>
                                    <p>Vous n'avez pas encore de factures.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="db-section reveal">
                    <h3 style="margin-bottom: 1.5rem;">Moyen de paiement</h3>
                    <div class="payment-method">
                        <div class="visa-icon">VISA</div>
                        <div>
                            <div style="font-weight: 600;">**** **** **** 4242</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Expire: 12/26</div>
                        </div>
                    </div>
                    <button class="btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 0.8rem;">Changer de carte</button>
                    
                    <div style="margin-top: 2rem; padding: 1rem; background: rgba(0, 210, 255, 0.05); border-radius: 10px; border: 1px solid rgba(0, 210, 255, 0.1);">
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--accent-primary); margin-bottom: 0.5rem;">Prochain prélèvement</div>
                        <div style="font-size: 1.2rem; font-weight: 700;">45.00 €</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">Le 01 Juin 2024</div>
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
