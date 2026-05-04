<?php
session_start();

require_once 'db.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'register'; // On peut ajouter un champ caché 'action' dans le HTML
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($action === 'login') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user_data = $stmt->fetch();

        if ($user_data && password_verify($password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['full_name'] = $user_data['full_name'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['role'] = $user_data['role'];
            header('Location: dashboard/index.php');
            exit;
        } else {
            echo "Identifiants invalides.";
        }
    } else {
        $fullName = $_POST['fullName'] ?? '';
        if (!empty($fullName) && !empty($email) && !empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$fullName, $email, $hashedPassword]);
                
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['full_name'] = $fullName;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'user'; // Rôle par défaut
                
                header('Location: dashboard/index.php');
                exit;
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "Cet email est déjà utilisé.";
                } else {
                    echo "Erreur lors de l'inscription : " . $e->getMessage();
                }
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
}
?>
