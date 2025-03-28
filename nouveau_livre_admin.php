<?php
include('pdo.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $titre = htmlspecialchars(trim($_POST['titre']));
    $nom_auteur = htmlspecialchars(trim($_POST['nom_auteur']));
    $prenom_auteur = htmlspecialchars(trim($_POST['prenom_auteur']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $date_publication = $_POST['date_publication'];
    $note = intval($_POST['note']);
    $etat = htmlspecialchars(trim($_POST['etat']));
    $stock = intval($_POST['stock']);
    $resume = htmlspecialchars(trim($_POST['resume']));
    $prix = floatval($_POST['prix']);
    $img = htmlspecialchars(trim($_POST['img']));

    try {
        // Préparer la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO livre (titre, nom_auteur, prenom_auteur, genre, date_publication, note, etat, stock, resume, prix, img) 
                               VALUES (:titre, :nom_auteur, :prenom_auteur, :genre, :date_publication, :note, :etat, :stock, :resume, :prix, :img)");
        $stmt->execute([
            ':titre' => $titre,
            ':nom_auteur' => $nom_auteur,
            ':prenom_auteur' => $prenom_auteur,
            ':genre' => $genre,
            ':date_publication' => $date_publication,
            ':note' => $note,
            ':etat' => $etat,
            ':stock' => $stock,
            ':resume' => $resume,
            ':prix' => $prix,
            ':img' => $img
        ]);

        $message = "Le livre a été ajouté avec succès !";
    } catch (PDOException $e) {
        $message = "Erreur lors de l'ajout du livre : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Nouveau Livre</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-buttons {
            text-align: center;
        }
        .form-buttons button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-buttons button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="admin">
        <div class="top-bar">
            <div class="logo">
                <a href="Accueil.php">
                    <img src="Icon\logo.png" alt="Logo">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
            </div>
            <div class="icons-header-admin">
                <div class="icons">
                    <div class="deconnexion">
                        <a href="Admin_ou_lecteur.php" class="deconnecte">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="categories">
            <a href="Accueil_admin.php">Accueil</a>
            <a href="Stock_admin.php">Stock</a>
            <a href="nouveau_livre_admin.php" class="active">Nouveaux livres</a>
            <a href="rachat_de_livre_admin.php">Rachats de livres</a>
            <a href="vitrine_admin.php">Vitrine</a>
        </div>

        <div class="form-container">
            <h2>Ajouter un Nouveau Livre</h2>

            <?php if (!empty($message)): ?>
                <div class="message"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="nom_auteur">Nom de l'Auteur</label>
                    <input type="text" id="nom_auteur" name="nom_auteur" required>
                </div>
                <div class="form-group">
                    <label for="prenom_auteur">Prénom de l'Auteur</label>
                    <input type="text" id="prenom_auteur" name="prenom_auteur" required>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" required>
                </div>
                <div class="form-group">
                    <label for="date_publication">Date de Publication</label>
                    <input type="date" id="date_publication" name="date_publication" required>
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <input type="number" id="note" name="note" min="0" max="5" step="0.1" required>
                </div>
                <div class="form-group">
                    <label for="etat">État</label>
                    <input type="text" id="etat" name="etat" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" required>
                </div>
                <div class="form-group">
                    <label for="resume">Résumé</label>
                    <textarea id="resume" name="resume" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Prix</label>
                    <input type="number" id="prix" name="prix" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="img">Lien de l'Image</label>
                    <input type="text" id="img" name="img">
                </div>
                <div class="form-buttons">
                    <button type="submit">Ajouter le Livre</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>