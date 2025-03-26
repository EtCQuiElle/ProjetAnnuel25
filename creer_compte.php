<?php
// Configuration de la base de données
include('pdo.php');

// Variables pour stocker les erreurs
$erreurs = [];

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    
    // Validation du nom et prénom
    if (empty($nom) || strlen($nom) < 2) {
        $erreurs[] = "Le nom est invalide.";
    }

    if (empty($prenom) || strlen($prenom) < 2) {
        $erreurs[] = "Le prénom est invalide.";
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }

    // Validation de la date de naissance
    $date_actuelle = new DateTime();
    $date_naissance_obj = DateTime::createFromFormat('Y-m-d', $date_naissance);
    
    if (!$date_naissance_obj || $date_naissance_obj >= $date_actuelle) {
        $erreurs[] = "La date de naissance est invalide.";
    }

    // Vérification des mots de passe
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    // Hachage du mot de passe
    $mdp = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Normalisation du sexe
    switch($sexe) {
        case 'homme':
            $sexe_code = 'H';
            break;
        case 'femme':
            $sexe_code = 'F';
            break;
        default:
            $sexe_code = 'A';
    }

    // Vérification s'il y a des erreurs
    if (empty($erreurs)) {
        try {
            // Préparation de la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO utilisateurs 
                (nom, prenom, email, date_naissance, type, sexe, mdp) 
                VALUES 
                (:nom, :prenom, :email, :date_naissance, :type, :sexe, :mdp)");
            
            // Liaison des paramètres
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindValue(':type', 'client');
            $stmt->bindParam(':sexe', $sexe_code);
            $stmt->bindParam(':mdp', $mdp);
            
            // Exécution de la requête
            $stmt->execute();
            
            // Redirection vers admin_ou_lecteur.php
            header('Location: Admin_ou_lecteur.php');
            exit();
            
        } catch(PDOException $e) {
            // Gestion des erreurs d'insertion
            if ($e->getCode() == '23000') {
                $erreurs[] = "Un compte existe déjà avec cette adresse email.";
            } else {
                $erreurs[] = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-lecteur-page">
    <div class="background">
        <img src="Icon\Books_background.jpg" alt="Logo" class="background-logo">
    </div>
    <div class="form-container">
        <h2>Créer un compte</h2>
        
        <?php
        // Affichage des erreurs
        if (!empty($erreurs)) {
            echo '<div class="erreurs">';
            foreach ($erreurs as $erreur) {
                echo '<p>' . $erreur . '</p>';
            }
            echo '</div>';
        }
        ?>
        
        <form action="Admin_ou_lecteur.php" method="post">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required value="<?php echo isset($prenom) ? htmlspecialchars($prenom) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" required value="<?php echo isset($date_naissance) ? $date_naissance : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" required>
                    <option value="femme" <?php echo (isset($sexe) && $sexe == 'femme') ? 'selected' : ''; ?>>Femme</option>
                    <option value="homme" <?php echo (isset($sexe) && $sexe == 'homme') ? 'selected' : ''; ?>>Homme</option>
                    <option value="non_communique" <?php echo (isset($sexe) && $sexe == 'non_communique') ? 'selected' : ''; ?>>Non communiqué</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmation du mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            alert('Les mots de passe ne correspondent pas');
            event.preventDefault();
            return;
        }
    });
    </script>
</body>
</html>