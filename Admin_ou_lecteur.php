<?php
session_start();
include('pdo.php');
if ($pdo) {
    echo "Connexion réussie !";
} else {
    echo "Échec de la connexion.";
}

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : null;
    
    // Requête pour compter le nombre d'utilisateurs correspondant
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ? AND mdp = ? AND type = ?");
    $stmt->execute([$email, $password, $user_type]);
    $userCount = $stmt->fetchColumn();

    // Vérification des identifiants
    if ($userCount == 1) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = $user_type;

        if ($user_type == 'admin') {
            header('Location: accueil_admin.php');
        } else {
            header('Location: acces_client.php');
        }
        exit();
    } else {
        $error = "Identifiants incorrects. Veuillez vérifier votre email ou mot de passe.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-lecteur-page">
    <div class="background">
        <img src="Icon/Books_background.jpg" alt="Background" class="background-logo">
    </div>
    <div class="popup">
        <?php if (!empty($error)): ?>
            <div class="error-message" style="color: red; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <h2>Se connecter en tant que :</h2>
            <div class="user-type-selection">
                <button type="submit" class="popup-button" name="user_type" value="utilisateur">Lecteur</button>
                <button type="submit" class="popup-button" name="user_type" value="admin">Administrateur</button>
            </div>
        </form>

        <p class="create-account">Pas de compte ? <a href="creer_compte.php">Créer un compte</a></p>
        <p class="anonymous"> <a href="acces_client.php">Se connecter en tant qu'Invité</a></p>
    </div>
</body>
</html>