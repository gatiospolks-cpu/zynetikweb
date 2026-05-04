<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Rafraîchit les informations de l'utilisateur depuis la base de données.
 */
function refreshSession() {
    if (isset($_SESSION['user_id'])) {
        // On a besoin de PDO. On essaie de le récupérer.
        global $pdo;
        
        // Si PDO n'est pas encore défini (ex: avant require_once db.php), 
        // on l'inclut manuellement si le fichier existe.
        if (!isset($pdo)) {
            $dbPath = __DIR__ . '/../db.php';
            if (file_exists($dbPath)) {
                require_once $dbPath;
            }
        }

        if (isset($pdo)) {
            // 1. Récupérer le rôle de l'utilisateur
            $stmt = $pdo->prepare("SELECT role, full_name, email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            if ($user) {
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];

                // 2. Récupérer les permissions associées à ce rôle
                try {
                    $stmtPerm = $pdo->prepare("SELECT * FROM role_permissions WHERE role = ?");
                    $stmtPerm->execute([$user['role']]);
                    $rolePerms = $stmtPerm->fetch();

                    if ($rolePerms) {
                        $_SESSION['perms'] = [
                            'manage_users' => $rolePerms['perm_manage_users'],
                            'create_services' => $rolePerms['perm_create_services'],
                            'view_services' => $rolePerms['perm_view_services'],
                            'delete_services' => $rolePerms['perm_delete_services']
                        ];
                    } else {
                        // Par défaut si le rôle n'existe pas dans la table
                        $_SESSION['perms'] = ['manage_users' => 0, 'create_services' => 0, 'view_services' => 0, 'delete_services' => 0];
                    }
                } catch (\PDOException $e) {
                    // Si la table n'existe pas encore ou erreur, on met des perms par défaut vides
                    $_SESSION['perms'] = ['manage_users' => 0, 'create_services' => 0, 'view_services' => 0, 'delete_services' => 0];
                }
            } else {
                // Utilisateur supprimé de la base de données
                session_destroy();
                header('Location: ../register.html');
                exit;
            }
        }
    }
}

/**
 * Vérifie si l'utilisateur est connecté.
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../register.html');
        exit;
    }
    refreshSession(); // Toujours rafraîchir pour avoir le rôle à jour
}

/**
 * Vérifie si l'utilisateur a le rôle requis.
 * @param array $allowedRoles Liste des rôles autorisés.
 */
function checkRole($allowedRoles) {
    checkLogin();
    
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header('Location: index.php?error=access_denied');
        exit;
    }
}

/**
 * Vérifie si l'utilisateur est un fondateur.
 */
function isFounder() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'founder';
}

/**
 * Vérifie si l'utilisateur est un staff ou fondateur.
 */
function isStaff() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['staff', 'founder']);
}

/**
 * Vérifie si l'utilisateur a une permission spécifique.
 * Le fondateur a toujours toutes les permissions.
 */
function hasPermission($perm) {
    if (isFounder()) return true;
    return isset($_SESSION['perms'][$perm]) && $_SESSION['perms'][$perm] == 1;
}
?>
