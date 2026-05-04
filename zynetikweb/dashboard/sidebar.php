<?php
require_once '../includes/auth.php';
require_once '../includes/lang_manager.php';
$current_page = basename($_SERVER['PHP_SELF']);
$initials = strtoupper(substr($_SESSION['full_name'], 0, 1) . (isset(explode(' ', $_SESSION['full_name'])[1]) ? substr(explode(' ', $_SESSION['full_name'])[1], 0, 1) : ''));
?>
<aside class="db-sidebar">
    <div class="db-logo">
        <svg viewBox="0 0 40 40" width="32" height="32" fill="none"><path d="M5 10H25L15 30H5L15 10Z" fill="#00d2ff"/><path d="M18 15H35L25 30H18L28 15Z" fill="#3a7bd5"/></svg>
        <div class="logo-text" style="font-weight: 700; font-size: 1.1rem; color: #fff;">Zynetik <span style="color: var(--accent-primary);">Panel</span></div>
    </div>

    <nav class="db-nav">
        <a href="index.php" class="db-nav-item <?= $current_page == 'index.php' ? 'active' : '' ?>"><i data-lucide="layout-dashboard"></i> <span><?= __t('db_overview') ?></span></a>
        <a href="websites.php" class="db-nav-item <?= $current_page == 'websites.php' ? 'active' : '' ?>"><i data-lucide="globe"></i> <span><?= __t('db_websites') ?></span></a>
        <a href="servers.php" class="db-nav-item <?= $current_page == 'servers.php' ? 'active' : '' ?>"><i data-lucide="server"></i> <span><?= __t('db_servers') ?></span></a>
        <a href="databases.php" class="db-nav-item <?= $current_page == 'databases.php' ? 'active' : '' ?>"><i data-lucide="database"></i> <span><?= __t('db_databases') ?></span></a>
        <a href="billing.php" class="db-nav-item <?= $current_page == 'billing.php' ? 'active' : '' ?>"><i data-lucide="credit-card"></i> <span><?= __t('db_billing') ?></span></a>
        <a href="settings.php" class="db-nav-item <?= $current_page == 'settings.php' ? 'active' : '' ?>"><i data-lucide="settings"></i> <span><?= __t('db_settings') ?></span></a>
        
        <?php if (isStaff()): ?>
            <div class="admin-group">
                <div class="admin-toggle" onclick="toggleAdmin()">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <i data-lucide="shield-check" style="width: 18px; height: 18px; color: var(--accent-primary);"></i>
                        <span>Administration</span>
                    </div>
                    <i data-lucide="chevron-down" id="admin-chevron" style="width: 16px; height: 16px; transition: transform 0.3s ease;"></i>
                </div>
                <div class="admin-content" id="admin-menu">
                    <a href="manage_users.php" class="db-nav-item <?= $current_page == 'manage_users.php' ? 'active' : '' ?>"><i data-lucide="users"></i> <span>Utilisateurs</span></a>
                    <?php if (isFounder()): ?>
                        <a href="manage_permissions.php" class="db-nav-item <?= $current_page == 'manage_permissions.php' ? 'active' : '' ?>"><i data-lucide="lock"></i> <span>Permissions</span></a>
                    <?php endif; ?>
                    <a href="manage_services.php" class="db-nav-item <?= $current_page == 'manage_services.php' ? 'active' : '' ?>"><i data-lucide="plus-circle"></i> <span>Créer Service</span></a>
                    <a href="list_services.php" class="db-nav-item <?= $current_page == 'list_services.php' ? 'active' : '' ?>"><i data-lucide="layers"></i> <span>Liste Services</span></a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <script>
        function toggleAdmin() {
            const menu = document.getElementById('admin-menu');
            const chevron = document.getElementById('admin-chevron');
            menu.classList.toggle('open');
            chevron.style.transform = menu.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
            localStorage.setItem('adminMenuOpen', menu.classList.contains('open'));
        }

        // Garder l'état au rechargement
        if (localStorage.getItem('adminMenuOpen') === 'true') {
            document.getElementById('admin-menu').classList.add('open');
            document.getElementById('admin-chevron').style.transform = 'rotate(180deg)';
        }
    </script>

    <div style="padding: 1rem; border-top: 1px solid var(--glass-border);">
        <a href="../logout.php" class="db-nav-item" style="color: #ef4444;"><i data-lucide="log-out"></i> <span><?= __t('logout') ?></span></a>
    </div>
</aside>
